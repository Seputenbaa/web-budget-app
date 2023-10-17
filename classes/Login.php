<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	// public function login(){
	// 	extract($_POST);

	// 	$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password') ");
	// 	if($qry->num_rows > 0){
	// 		foreach($qry->fetch_array() as $k => $v){
	// 			if(!is_numeric($k) && $k != 'password'){
	// 				if ($k == 'type') {
	// 					$type = $v;
	// 				}
	// 				$this->settings->set_userdata($k,$v);
	// 			}
	// 		}
	// 		$this->settings->set_userdata('login_type',1);
			
	// 		  return json_encode(array('status'=>'success','type'=>$type));
	// 	}else{
	// 	return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
	// 	}
	// }
	public function login(){
		
	
		extract($_POST);
	
		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					if ($k == 'login_type') {
						$type = $v;
	
						// Extra security check for $type being 1 or 0
						if ($type == 0) {
							$_SESSION['login_type'] = 'user';
						} else {
							$_SESSION['login_type'] = 'admin';
						}
					}
					$this->settings->set_userdata($k,$v);
				}
			}
	
			return json_encode(array('status'=>'success','login_type'=>$_SESSION['login_type']));
		} else {
			return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	
	
	public function logout(){
		
		session_destroy();
		redirect('admin/login.php');
	}
	

	function login_user(){
		extract($_POST);
		$qry = $this->conn->query("SELECT * from clients where email = '$email' and password = md5('$password') ");
		if($qry->num_rows > 0){
			foreach($qry->fetch_array() as $k => $v){
				$this->settings->set_userdata($k,$v);
			}
			$this->settings->set_userdata('login_type',1);
		$resp['status'] = 'success';
		
		}else{
		$resp['status'] = 'incorrect';
		}
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['_error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'login_user':
		echo $auth->login_user();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	default:
		echo $auth->index();
		break;
}

