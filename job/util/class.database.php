<?php
class Database{
	private $_connection;
	private static $_instance;

    private $_db = array(
        'hostname' => '8.215.16.86',
		'port' => 3406,
        'username' => 'ecentrix',
        
		//'password' => 'yvWD5FQZfq8xP82yVumNkDBAnrgtYEUw',
		'password' => 'eCxFsJQcAX6Gcnc',
		'key' 	   => '25927841569421c860659a0f964dfb02',
		'secret'   => 'eCentriX',
		'database' => 'cms_collection_v1_3',
        'dbdriver' => 'mysqli'
    );
    private $_encrypt_method = "AES-256-CBC";
    private $_host, $_username, $_password, $_database, $_port;       

	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
        $key = hash('sha256', $this->_db['key']);
        $iv = substr(hash('sha256', $this->_db['secret']), 0, 16);
        //$password = openssl_decrypt(base64_decode($this->_db['password']), $this->_encrypt_method, $key, 0, $iv);    
        $this->_host = $this->_db['hostname'];
        $this->_username = $this->_db['username'];
        //$this->_password = $password;
		$this->_password = $this->_db['password'];
        $this->_database = $this->_db['database'];            
        $this->_port = $this->_db['port'];            

		$this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database, $this->_port);
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        if ($this->_connection->connect_error){
			send_email('CRITICAL','Failed to connect databases');
            trigger_error("Failed to connect to to MySQL: " . $this->_connection->connect_error, E_USER_ERROR);
		}
    }
    
    private function __close() {
        return $this->_connection->close();
    }
    
	public function getConnection() {
		return $this->_connection;
	}
    
    public function encrypt_decrypt($action, $string)
	{
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = $this->_db['key'];
		$secret_iv = $this->_db['secret'];
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ($action == 'encrypt')
		{
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else
		{
			if ($action == 'decrypt')
			{
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		}
		return $output;
	}
}
