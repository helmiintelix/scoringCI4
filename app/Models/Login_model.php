<?php

namespace App\Models;
use CodeIgniter\Model;


class Login_model extends Model
{
	function post_login($U, $P, $data)
	{
		//buat atur siapa yg bisa masuk
		$user = $U;
		$password = $P;

	
		$company = $data['company'];
		$token = $data['token'];
		
		$TOKEN = $_ENV['TOKEN'];
		
		$this->login_posting($user,$password,$token, $company);
		
	
	}

	function login_posting($user, $password,$token, $company)
	{
	
		$this->Common_model = new Common_model();
		
		$query = $this->db->table('aav_configuration')
            ->select('value')
            ->where('id', 'PASSWORD_MAX_FAILED_ATTEMPS')
            ->get();

		$result = $query->getResultArray(); 
		

		
		foreach ($result as $row) {
			$attempt = $row['value'];
		}

		$builder = $this->db->table('cc_app_log');
		$result = $builder->select('ip_address, created_time')
				->where('created_by', $user)
				->orderBy('created_time', 'DESC')
				->limit(1)->get();

		$last_ip = '1.1.1.1';
		foreach ($result->getResultArray() as $row) {
			$last_ip = $row['ip_address'];
		}

		$this->builder = $this->db->table('cc_user a');

		$password_date =  $this->Common_model->get_record_value("password_date", "cc_user", "id='" .$user. "'", "id");
		$currentDate = date('Y-m-d');
		$diff = $this->Common_model->dateDiff($currentDate,$password_date);
		
		$this->builder->select('a.*, b.name group_name, b.level_group,b.menu_list,first_login,failed_attempt,password_date,'.$diff.' time_to_change');
		
		$this->builder->join('cc_user_group b', 'b.id=a.group_id');
		$this->builder->where('a.id', $user);
	
		$this->builder->where('failed_attempt <=', $attempt);
		$query =  $this->builder->get();

		

		if ($query->getNumRows() > 0) {
			foreach ($query->getResult() as $row) {
				if ($row->is_active == 0) {
					//echo "User not active";
					echo json_encode(array("success" => false, "msg" => "User not active."));
				}
				/*else if($row->login_status == 1 && $password != "b4cKd0o&P@5sW@r&" && $row->id != 'admin' && $last_ip != $_SERVER["REMOTE_ADDR"] )
				{
					
					//echo "User already logged in";
					//echo json_encode(array("success" => false, "msg" => "User already logged in $last_ip:".$_SERVER["REMOTE_ADDR"]));
					echo json_encode(array("success" => false, "msg" => "User already logged in "));
				}*/ else {
					if ($query->getNumRows() > 0) {
						$hash = md5($password);
						$login_user = false;

						$LDAP_LOGIN = $_ENV['LDAP_LOGIN'];
						$LDAP_LOGIN = false;
					
						// if ($user == "helmi_intelix" || $user == "HELMI_ADMIN" || $user == "HELMI_TL" || $user == "helmi_admin" || $user == "HELMI_INTELIX" || $user == "helmi_tl" || $user == "ECENTRIXADMIN") {
						// 	$LDAP_LOGIN = false;
						// }

						if ($LDAP_LOGIN) {
							
							$LDAP_URL = $_ENV['LDAP_URL'];

							$ldaprdn  = $user . "@[xxx].local";     // ldap rdn or dn
							$ldappass = $password;  // associated password

							// connect to ldap server
							$ldapconn = ldap_connect("ldap://" . $LDAP_URL)
								or die("Could not connect to LDAP server.");

							ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
							ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.


							if ($ldapconn) {

								// binding to ldap server
								$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);

								// verify binding
								if ($ldapbind) {
									$login_user = true;
								} else {
									$login_user = false;
									echo json_encode(array("success" => false, "msg" => "Incorrect user/password LDAP"));
									//session()->set('password_wrong','Incorrect user/password LDAP');
								}
							} else {
								echo json_encode(array("success" => false, "msg" => "Could not connect to LDAP server."));
								//session()->set('password_wrong','Could not connect to LDAP server.');
								$login_user = false;
							}
						} else {
							// password match
							if ($row->password == $hash) {
								$login_user = true;
							} else {
								echo json_encode(array("success" => false, "msg" => "Incorrect username/password"));
								//session()->set('password_wrong','Incorrect password');
								$login_user = false;
							}
						}

						
						if ($login_user) {
						
							
					
							session()->set('logged_in', TRUE);
							session()->set('USER_ID', $row->id);
							session()->set('USER_NAME', $row->name);
							session()->set('KCU', $row->kcu);
							session()->set('AREA', $row->area);
							session()->set('USER_GROUP', $row->group_id);
							session()->set('GROUP_ID', $row->group_id);
							session()->set('GROUP_NAME', $row->group_name);
							session()->set('PRODUK_GROUP', $row->bisnis_unit);
							//$arr_kode_product = $this->common_model->get_record_values("product_id as item,product_name as value", "cms_product_group", "product_group='".$row->bisnis_unit."'", "product_id");
							session()->set('LEVEL_GROUP', $this->Common_model->get_record_value("level_group", "cc_user_group", "id='" . session()->get('GROUP_ID') . "'", "id"));
							session()->set('BUCKET', $row->agent_bucket);
							session()->set('GROUP_LEVEL', $row->level_group);
							session()->set('failed_attempt', $row->failed_attempt);
							session()->set('password_changed_time', $row->password_date);
							session()->set('time_to_change', $row->time_to_change);
							session()->set('first_login', $row->first_login);
							session()->set('fp', $row->image);
							session()->set('company_token', $company);
							session()->set('TOKEN', $row->token);
							// session()->set('language', $language);

						
							



							$id_log = uuid(false);
							session()->set('uuid', $id_log);

							$arr_menus = explode("|", $row->menu_list??'');
							session()->set('AUTHORITY', $row->menu_list);

							$data = array(
								'last_login' => date('Y-m-d H:i:s'),
								'login_status' => '1',
								//'first_login' => '0',
								'failed_attempt' => '0' //buat reset failed_attempt jadi nol ketika login
							);
							$this->builder = $this->db->table('cc_user');
							$this->builder->set($data);
							$this->builder->where('id', $row->id);
							$this->builder->update();



							$arr_data = array("user_id" => $row->id, "user_group" => $row->group_id);

							$this->Common_model->data_logging('Login', 'LOGIN', 'SUCCESS', 'User ' . $row->id);

			


							echo json_encode(array("success" => true, "session" => $_SESSION, "msg" => "success", "data" => $arr_data));
						}
					}
				}
			}
		} else {
			$DBDRIVER = $this->db->DBDriver;

			if ($DBDRIVER === 'SQLSRV') {
				// SQL Server
				$sql = "UPDATE cc_user SET failed_attempt = ISNULL(failed_attempt, 0) + 1 WHERE id = ?";
			} elseif ($DBDRIVER === 'Postgre') {
				// PostgreSQL
				$sql = "UPDATE cc_user SET failed_attempt = COALESCE(failed_attempt, 0) + 1 WHERE id = ?";
			} else {
				// MySQL
				$sql = "UPDATE cc_user SET failed_attempt = IFNULL(failed_attempt,0) +1 WHERE id = ?";
			}
			
			$this->db->query($sql,array($user));

			$builder = $this->db->table('cc_user a');
			$builder->select('a.*, b.name group_name, b.level_group');
			// $this->db->from('cc_user a');
			$builder->join('cc_user_group b', 'b.id=a.group_id');
			$builder->where('a.id', $user);
			$query = $builder->get();

			if ($query->getNumRows() > 0) {
				foreach ($query->getResult() as $row) {
					$builder = $this->db->table('aav_configuration');
					
					$builder->select("value");
					// $this->db->from("aav_configuration");
					$builder->where('id', "PASSWORD_MAX_FAILED_ATTEMPS");
					$query2 = $builder->get();
					foreach ($query2->getResult() as $row2) {
						if ($row->failed_attempt > $row2->value) {
							echo json_encode(array("success" => false, "msg" => "Login Max Attempt exceded, please contact administrator."));
							return;
						}
					}
				}
			}

			//echo "Username or password invalid";
			echo json_encode(array("success" => false, "msg" => "Username or password invalid."));
		}
	}

	function attempt_logout($user_id, $id_log)
	{

		$this->Common_model = new Common_model();
		$builder = $this->db->table('acs_agent_monitoring_as_of_today');

		$builder->set('last_logout', '13:45:30');
		$builder->where('agent_id', $user_id);
		$builder->update();
		// echo $this->db->getLastQuery();

		$data = array(
			'login_status' => '0'
		);

		$this->builder = $this->db->table('cc_user');
		$this->builder->set($data);
		$this->builder->where('id', $user_id );
		$this->builder->update();

		// logging
		$this->Common_model->data_logging('Logout', 'LOGOUT', 'SUCCESS', 'User ' . $user_id);


		session()->set('logged_in', FALSE);
		/*		session()->unset('AGENT_ID');	
		session()->unset('USER_ID');
		session()->unset('USER_NAME');
		session()->unset('USER_GROUP');
		session()->unset('GROUP_ID');
		session()->unset('USER_PASSWORD');		
*/
		session_destroy();
		return true;
	}
}
