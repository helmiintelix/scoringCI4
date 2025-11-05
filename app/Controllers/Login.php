<?php

namespace App\Controllers;

class Login extends BaseController
{
    // public function index(): string
    // {
    //     return view('welcome_message');
    // }
  

    public function index()
	{
		
		$data['key'] = getenv('AesKey');

		// $this->session->destroy();
        if (!$this->session->get('logged_in')) {
            return view('new_login_view', $data); 
			
		} else {
			return redirect()->route('Main');
		}

		
	}
	function company_create()
	{
		try {
			$company['name'] = $this->input->get_post("name_company");
			if ($company['name']) {
				$this->db->set('token', 'UUID()', FALSE);

				$this->db->insert('user_company', $company);
				$return = $this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($company, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
				return $return;
			} else {
				$return = $this->output
					->set_status_header(400)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode("Company cannot be null"));
				return $return;
			}
		} catch (Error $e) {
			$this->output
				->set_status_header(400)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($e, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
		}
	}

	private function decryptAES(string $encryptedData, string $key): string|false {
		$data = json_decode($encryptedData, true);
		if (!isset($data['iv'], $data['data'])) {
			return false;
		}

		$iv = implode(array_map("chr", $data['iv']));
		$encrypted = implode(array_map("chr", $data['data']));

		// Pastikan panjang key 16 byte (128 bit)
		$key = mb_convert_encoding($key, 'UTF-8');
		if (strlen($key) < 16) {
			$key = str_pad($key, 16, "\0");
		} elseif (strlen($key) > 16) {
			$key = substr($key, 0, 16);
		}

		$method = 'aes-128-gcm';

		// --- Pisahkan ciphertext dan tag ---
		// AES-GCM hasil enkripsi = ciphertext + 16-byte auth tag
		$tagLength = 16;
		$tag = substr($encrypted, -$tagLength);
		$ciphertext = substr($encrypted, 0, -$tagLength);

		// --- Dekripsi dengan autentikasi ---
		$decryptedData = openssl_decrypt(
			$ciphertext,
			$method,
			$key,
			OPENSSL_RAW_DATA,
			$iv,
			$tag
		);

		return $decryptedData === false ? false : $decryptedData;
	}

	function post_login()
	{
		$encryptedData = $this->input->getPost('data');
		$key = getenv('AesKey');
		
		if (!$encryptedData) {
			echo json_encode([
				'status' => 'error',
				'message' => 'No data provided'
			]);
			exit;
		}
		
		
		$decryptedData = $this->decryptAES($encryptedData, $key);

		if ($decryptedData === false) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Decryption failed'
			]);
			exit;
		}

		if ($decryptedData === false) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Decryption failed'
			]);
			exit;
		}
	
		$data = json_decode($decryptedData, true);
	
		if (!isset($data['username'], $data['password'])) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Invalid decrypted data'
			]);
			exit;
		}
	
		$username = $data['username'];
		$password = $data['password'];
	

		$NEED_CAPTCHA =$_ENV['CAPTCHA'];
		if($NEED_CAPTCHA=='false'){
			
			if ((empty($username)) || empty($password)) {
				echo json_encode(array("success" => false, "msg" => "Please input all fields!"));
				return;
			}
			
		}else if($NEED_CAPTCHA=='true'){
			if ((empty($username)) || empty($password) || empty($_POST['recaptcha'])) {
				echo json_encode(array("success" => false, "msg" => "Please input all fields!"));
				return;
			}

		}
		if ($_POST) {
			echo $this->_is_postback($username, $password);
		} else {
			$this->_not_is_postback();
		}
	}

	function _not_is_postback()
	{
		return view('new_login_view', '');
	}

	function _is_postback($username, $password)
	{
		$data['company'] =$this->input->getPost("token_office");
		$data['token'] = $this->input->getPost("token_login");
      
		$result = $this->Login_model->post_login($username, $password, $data);

		return $result;
	}

	function logout()
	{
		$result = $this->Login_model->attempt_logout(session()->get('USER_ID'),session()->get('uuid'));
		
		if ($result) {
			
			return redirect()->route('login');
		}
	}

}