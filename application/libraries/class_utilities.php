<?php 
/*Updated: January 5,2012
* By Marvin Dungog
*
*/

class Utilities {


	public function __construct($id) {
		$this->id = $id;
		self::getObjectVariable();
	}
	
	
	public static function createFormToken() {
		//$token =  md5(uniqid(rand(), true));
		$token = md5(microtime(TRUE) . rand(0, 100000));
		$session = new WG_Session(array('namespace' => 'user'));
		$session->set('ft', $token);
		$session->set('ft_expires', time() + 1800);	//expires in 30 mins
		
		return $token;
	}

	public static function displayArray($data = array()){
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	public static function encryptArrayId($key_name = "", $data = array()) {		
    	$new_data = array();
		foreach( $data as $key => $value ){
			if( $key == $key_name ){
				$new_data[$key] = Utilities::encrypt($value);
			}else{
				$new_data[$key] = $value;
			}
		}		
		return $new_data;
    }

    public static function encryptArrayIds($key_name = "", $data = array()) {    	
    	$new_data = array();
		foreach( $data as $key => $value ){
			foreach($value as $sub_key => $sub_value){
				if( $sub_key == $key_name ){
					$new_data[$key][$sub_key] = Utilities::encrypt($sub_value);
				}else{
					$new_data[$key][$sub_key] = $sub_value;
				}
			}
		}		
		return $new_data;
    }
	
		
	public static function verifyFormToken($token='') {
		$supplied_token = $token;
		
		// Ensure that a token has been previously set.
		if (!isset($_SESSION['user']['ft'], $_SESSION['user']['ft_expires']))
		{
			include APP_PATH . 'errors/500.php';
			die ();
		}
		
		$token = $_SESSION['user']['ft'];
		$expires = $_SESSION['user']['ft_expires'];
		
		// Clear the tokens, they are single use.
		$_SESSION['user']['ft'] = NULL;
		$_SESSION['user']['ft_expires'] = 0;			
		
		if ($expires < time() || $token != $supplied_token)
		{
			include APP_PATH . 'errors/500.php';
			die ();
		}
	}
	
	public static function isFormTokenValid($token='') {
		$supplied_token = $token;
		$return = true;
		// Ensure that a token has been previously set.
		if (!isset($_SESSION['user']['ft'], $_SESSION['user']['ft_expires']))
		{
			//include APP_PATH . 'errors/500.php';
			//die ();
			$return = false;
		}
		
		$token = $_SESSION['user']['ft'];
		$expires = $_SESSION['user']['ft_expires'];
		
		// Clear the tokens, they are single use.
		$_SESSION['user']['ft'] = NULL;
		$_SESSION['user']['ft_expires'] = 0;			
		
		if ($expires < time() || $token != $supplied_token)
		{
			//include APP_PATH . 'errors/500.php';
			//die ();
			$return = false;
		}
		
		return $return;
	}
	
	public static function createPageToken() {
		$token = md5(microtime(TRUE) . rand(0, 100000));
		$session = new WG_Session(array('namespace' => 'user'));
		$session->set('pt', $token);
		//$session->set('pt_expires', time() + 1800);	//expires in 30 mins
		
		return $token;
	}
	
	public static function verifyPageToken() {
		$supplied_token = $token;
		
		// Ensure that a token has been previously set.
		if (!isset($_SESSION['user']['ft'], $_SESSION['user']['fk_expires']))
		{
			die ('token is required!');
		}
		
		$token = $_SESSION['user']['action_token'];
		$expires = $_SESSION['user']['token_expires'];
		
		// Clear the tokens, they are single use.
		$_SESSION['user']['ft'] = NULL;
		$_SESSION['user']['ft_expires'] = 0;			
		
		if ($expires < time() || $token != $supplied_token)
		{
			include APP_PATH . 'errors/500.php';
			die ();
		}
	}
	
	//if you want to secure your ajax request
	function ajaxRequest() {
		if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
		 //Request identified as ajax request
			include APP_PATH . 'errors/500.php';
			die();
		}
	
	}
	
	function verifyObject($object)
	{
		 if (!is_object($object)) {
			include APP_PATH . 'errors/500.php';
			exit;
   		 }	
	}
	
	function isObject($object)
	{
		 if (!is_object($obj)) {
			$return  = false;
   		 }else {
			$return = true; 
			}
		return $return;
	}
	
	public static function encrypt($text) {
		$crypt = new Crypt;
		return $crypt->encrypt($text);
	}
	
	public static function decrypt($text) {
		$crypt = new Crypt;
		return $crypt->decrypt($text);
	}
	
	public static function encryptPassword($text) {
		$crypt = new Crypt;
		return $crypt->encrypt_string($text);
	}
	
	public static function createHash($text) {
		$crypt = new Crypt;
		return $crypt->createHash($text);	
	}
	
	public static function verifyHash($text,$hash) {
		$crypt = new Crypt;
		if(!$crypt->verifyHash($text,$hash)) {
			include APP_PATH . 'errors/500.php';
			die();	
		}	
	}
	
	public static function isHashValid($text,$hash) {
		$return = true;
		$crypt = new Crypt;
		if(!$crypt->verifyHash($text,$hash)) {
			$return = false;
		}	
		return $return;	
	}
	
	public static function error500() {
		include APP_PATH . 'errors/500.php';
		die();
	}
	
	public static function verifyAccessRights($employee_id,$mod,$action) {
		
		$module = array('dashboard','compensation','employment_status');

		$is_module = 'false';
		foreach($module as $key=>$val) {
			if($val==$mod) {
//				echo $val;
				$is_module = 'true';
			}	
		}
		
		if($is_module==true) {
			
			//load user access
			echo Utilities::getUserAccessRights($employee_id,$mod);
		}else {
			echo 'not module';
		}
		
		
	}
	
	public static function getUserAccessRights($employee_id,$mod)
	{
		$user_access = array('dashboard'=>1,'employee_profile'=> array('compensation'=>0,'employment_status'=>1));
		foreach($user_access as $key=>$value) {
			if(is_array($value)) {
				foreach($value as $key=>$val) {						
					if($key==$mod) {
					
						if($val==2) {
							$return = "can manage";	
						}else if($val==1) {
							$return = "has access";
						}else if($val==0) {
						
							include APP_PATH . 'errors/credentials_required.php';
							die();
						}else {
							$return = "no access";
						}
						
					}
				}
			}else {
				if($key==$mod) {
						if($val==2) {
							$return = "can manage";	
						}else if($val==1) {
							$return = "has access";
						}else if($val==0) {
							$return =  "no access";
						}else {
							$return = "no access";
						}
				}	
			}		
		}
		return $return;
	}
	
	public static function checkModulePackageAccess($package,$module) {
		
		$access = $GLOBALS['module_package'][$package];
		if($access[$module] != true) {
			include APP_PATH . 'errors/credentials_required.php';
			die();
		}
	}

	
}
?>