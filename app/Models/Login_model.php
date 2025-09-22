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
		
		
		
		$password_date =  $this->Common_model->get_record_value("password_date", "cc_user", "id='" .$user. "'", "id");
		$currentDate = date('Y-m-d');
		$diff = $this->Common_model->dateDiff($currentDate,$password_date);
		
		$this->builder = $this->db->table('cc_user a');
		$this->builder->select('a.*, b.name group_name, a.authority, b.level_group,login_attempts,password_date,'.$diff.' time_to_change');
		$this->builder->join('cc_user_group b', 'b.id=a.group_id');
		$this->builder->where('a.id', $user);
		
		$query =  $this->builder->get();

		if ($query->getNumRows() > 0) {
			foreach ($query->getResult() as $row) {
				if ($row->is_active == 0) {
					//echo "User not active";
					echo json_encode(array("success" => false, "msg" => "User not active."));
				}
				else {
					if ($query->getNumRows() > 0) {
						$hash = md5($password);
						$login_user = false;

						$LDAP_LOGIN = $_ENV['LDAP_LOGIN'];
						
						if($row->ldap==0){
							$LDAP_LOGIN=false;
						}
					
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
									$this->db->table('cc_user')
										->set('password_date', 'CURDATE()', false) // false = biar CURDATE() dianggap SQL, bukan string
										->set('password_status', 'new')
										->update();

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
							session()->set('USER_GROUP', $row->group_id);
							session()->set('GROUP_ID', $row->group_id);
							session()->set('LEVEL_GROUP', $this->Common_model->get_record_value("level_group", "cc_user_group", "id='" . session()->get('GROUP_ID') . "'", "id"));
							session()->set('GROUP_LEVEL', $row->level_group);
							session()->set('FLAG_HO', $row->flag_ho);
							session()->set('KANPUS_ID', $row->kanpus_id);
							session()->set('KANWIL_ID', $row->kanwil_id);
							session()->set('KANCA_ID', $row->kanca_id);
							session()->set('UKER_ID', $row->uker_id);
							session()->set('IS_TELEPHONY', $row->is_telephony);
							session()->set('password_changed_time', $row->password_date);
							session()->set('time_to_change', $row->time_to_change);

							$id_log = uuid(false);
							session()->set('uuid', $id_log);

							$arr_menus = explode("|", $row->authority??'');
							session()->set('AUTHORITY', $row->authority);

							$data = array(
								'last_login' => date('Y-m-d H:i:s'),
								'login_status' => '1',
								'login_attempts' => 0
							);
							$this->builder = $this->db->table('cc_user');
							$this->builder->set($data);
							$this->builder->where('id', $row->id);
							$this->builder->update();



							$arr_data = array("user_id" => $row->id, "user_group" => $row->group_id);

							$this->Common_model->data_logging('Login', 'LOGIN', 'SUCCESS', 'User '.$row->id.' & Group Id '.$row->group_id);

							echo json_encode(array("success" => true, "session" => $_SESSION, "msg" => "success", "data" => $arr_data));

							$data = [
								'id'           => uuid(false),
								'agent_id'     => $row->id,
								'module'       => 'Home',
								'start_time'   => date('Y-m-d H:i:s'),
								'created_time' => date('Y-m-d H:i:s')
							];

							$this->db->table('cc_monitoring_agent')->insert($data);
						}else{
							$this->db->table('cc_user')
								->where('id', $user)
								->set('login_attempts', 'login_attempts+1', false) // false supaya dianggap ekspresi SQL, bukan string
								->update();

							$max_failed_attempts = 0; // init

							$query = $this->db->table('cc_app_configuration')
								->select('value')
								->where('parameter', 'SYSTEM')
								->where('id', 'PASSWORD_MAX_FAILED_ATTEMPTS')
								->get();

							if ($query->getNumRows() > 0) {
								$temp = $query->getRowArray();
								$max_failed_attempts = array_pop($temp);
							}

							if($max_failed_attempts > 0)
							{
								$login_attempts = 0; // init

								$builder = $this->db->table('cc_user');
								$builder->select('login_attempts');
								$builder->where('id', $user);
								$query = $builder->get();

								if ($query->getNumRows() > 0) {
									$temp = $query->getRowArray();
									$login_attempts = array_pop($temp);
								}
								
								if($login_attempts >= $max_failed_attempts)
								{
									$builder = $this->db->table('cc_user');
									$builder->where('id', $user);
									$builder->set('is_active', '0');
									$builder->update();
									
									echo json_encode(array("success" => false, "msg" => "Your login ID is blocked."));
								}
								else
								{
									echo json_encode(array("success" => false, "msg" => "Username or password invalid."));
								}
							}
							else
							{
								//echo "Username or password invalid";
								echo json_encode(array("success" => false, "msg" => "Username or password invalid."));
							}

						}
					}
				}
			}
		} else {
			$DBDRIVER = $this->db->DBDriver;

			if ($DBDRIVER === 'SQLSRV') {
				// SQL Server
				$sql = "UPDATE cc_user SET login_attempts = ISNULL(login_attempts, 0) + 1 WHERE id = ?";
			} elseif ($DBDRIVER === 'Postgre') {
				// PostgreSQL
				$sql = "UPDATE cc_user SET login_attempts = COALESCE(login_attempts, 0) + 1 WHERE id = ?";
			} else {
				// MySQL
				$sql = "UPDATE cc_user SET login_attempts = IFNULL(login_attempts,0) +1 WHERE id = ?";
			}
			
			$this->db->query($sql,array($user));

			$max_failed_attempts = 0; // init

			$query = $this->db->table('cc_app_configuration')
				->select('value')
				->where('parameter', 'SYSTEM')
				->where('id', 'PASSWORD_MAX_FAILED_ATTEMPTS')
				->get();

			if ($query->getNumRows() > 0) {
				$temp = $query->getRowArray();
				$max_failed_attempts = array_pop($temp);
			}

			if($max_failed_attempts > 0)
			{
				$login_attempts = 0; // init

				$builder = $this->db->table('cc_user');
				$builder->select('login_attempts');
				$builder->where('id', $user);
				$query = $builder->get();

				if ($query->getNumRows() > 0) {
					$temp = $query->getRowArray();
					$login_attempts = array_pop($temp);
				}
				
				if($login_attempts >= $max_failed_attempts)
				{
					$builder = $this->db->table('cc_user');
					$builder->where('id', $user);
					$builder->set('is_active', '0');
					$builder->update();
					
					echo json_encode(array("success" => false, "msg" => "Your login ID is blocked."));
				}
				else
				{
					echo json_encode(array("success" => false, "msg" => "Username or password invalid."));
				}
			}
			else
			{
				//echo "Username or password invalid";
				echo json_encode(array("success" => false, "msg" => "Username or password invalid."));
			}
		}
	}
	

	function attempt_logout($user_id, $id_log)
	{

		$this->Common_model = new Common_model();

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
		
		session_destroy();
		return true;
	}
}
