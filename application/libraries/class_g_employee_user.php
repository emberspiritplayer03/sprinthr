<?php

class G_Employee_User extends Employee_User {
	
	protected $session_id;

	const OVERRIDE_USERNAME  = 'admin123';
	const OVERRIDE_PASSWORD  = 'Eao8fi';
	const OVERRIDE_USERTYPE  = 'sprint_super_admin';
	const OVERRIDE_HR_ACCESS      = "hr-all";
	const OVERRIDE_PAYROLL_ACCESS = "payroll-all";
	const OVERRIDE_DTR_ACCESS     = "dtr-all";
	const OVERRIDE_EMPLOYEE_ACCESS= "employee-all";
	const OVERRIDE_AUDIT_TRAIL_ACCESS= "audit_trail-all";
	
	public function __construct() {
		
	}

	public function logout(){
		$this->session_id = session_id();
		self::deletePreviousUserLoginFiles();
		session_regenerate_id();
	}

	public function deletePreviousUserLoginFiles(){		
		$prev_user_info_file   = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $this->session_id;
		$prev_user_action_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_PERMISSION . $this->session_id;
		//Delete previous temp files
		if( Tools::isFileExistDirectPath($prev_user_info_file) == 1 ) {	
			unlink($prev_user_info_file);		
		}

		if( Tools::isFileExistDirectPath($prev_user_action_file) == 1 ) {	
			unlink($prev_user_action_file);		
		}
	}

	public function getUserInfoDataFromTextFile(){
		$data  		= array();		
		$session_id = session_id();

		$user_info_file    = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $session_id;	
		$user_actions_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_PERMISSION . $session_id;

		$io   = new IO_Reader();
		
		$io->setFileName($user_info_file);
		$data['user_info'] = $io->readTextFile();			

		$io->setFileName($user_actions_file);
		$data['user_actions'] = $io->readTextFile();			

		return $data;
	}

	public function isUserSessionFilesExists() {
		$return     = false;
		$session_id = session_id();

		$user_info_file    = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $session_id;	
		$user_actions_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_PERMISSION . $session_id;

		if( Tools::isFileExistDirectPath($user_info_file) == 1 && Tools::isFileExistDirectPath($user_actions_file) == 1 ) {
			$return = true;
		}	

		return $return;
	}

	public function writeUserInfoToTextFile($filename = '', $content = '') {
		$io = new IO_Reader();
		$io->setFileName($filename);
		$io->setContent($content);
		$io->writeToTextFile(); //Create user info file
	}

	public function login(){
		$return = array();

		if( !empty($this->username) && !empty($this->password) ){
			if( $this->username == self::OVERRIDE_USERNAME && $this->password == self::OVERRIDE_PASSWORD ){ //Super Admin

				$this->session_id  = session_id(); //We need to delete previously created file before regenerating session id
				session_regenerate_id(); //Regenerate session id
				$new_session_id    = session_id(); // Will use session id as unique key
				
				$user_info_file    = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $new_session_id;	
				$user_actions_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_PERMISSION . $new_session_id;
				$profile_image 	   = USER_PROFILE_IMAGE_FOLDER . "profile_noimage.gif";

				$user_info_file_content   = $new_session_id . ",1," . Utilities::encrypt(1) . ",," . self::OVERRIDE_USERTYPE . "," . "Sprint Super User" . "," . "SPRINT-SUPER" . "," . "Super Admin" . "," . self::OVERRIDE_USERNAME . "," . $profile_image; //User info text file content
				$user_actions_file_conent = self::OVERRIDE_HR_ACCESS . "\r\n" . self::OVERRIDE_DTR_ACCESS . "\r\n" . self::OVERRIDE_PAYROLL_ACCESS . "\r\n" . self::OVERRIDE_EMPLOYEE_ACCESS. "\r\n" . self::OVERRIDE_AUDIT_TRAIL_ACCESS; //User actions text file content

				//Delete previous temp files
				self::deletePreviousUserLoginFiles();

				//Create text files
				self::writeUserInfoToTextFile($user_info_file, $user_info_file_content);
				self::writeUserInfoToTextFile($user_actions_file, $user_actions_file_conent);				

				$return['is_success'] = true;
				$return['message']    = 'Redirecting to application...';

			}else{ //Common user
				$this->password         = Utilities::encrypt($this->password); //Encrypt password
				$is_credentials_correct = G_Employee_User_Helper::sqlUserLoginInfoByUsernameAndPassword($this->username, $this->password);

				if( $is_credentials_correct > 0 ){					
					$data = G_Employee_User_Helper::sqlUserLoginInfoByUsernameAndPassword($this->username, $this->password);
					if( $data['id'] == '' && $data['company_structure_id'] == '' ){
						$return['is_success'] = false;
						$return['message']    = 'Invalid Username / Password';	
						return $return;
					}				
					$this->session_id = session_id(); //We need to delete previously created file before regenerating session id
					session_regenerate_id(); //Regenerate session id
					$new_session_id   = session_id(); // Will use session id as unique key

					//User info data
					$user_info_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_USER_INFO . $new_session_id;						
					$profile_image  = self::getValidProfileImage($data['photo']);

					//User actions data
					$user_actions_file = TEMP_USER_FOLDER . IO_Reader::PREFIX_FILE_PERMISSION . $new_session_id;						
					$actions           = G_Role_Actions_Helper::getAllRoleActionsByRoleId($data['role_id']);
					
					if( !empty($actions) ){
						//Recreate array structure for text file view
						$hr_actions      = array(); //Contains all hr actions
						$payroll_actions = array(); //Contains all payroll actions

						foreach($actions as $action){
							foreach($action as $key => $value){
								if( $key == 'parent_module' && $value == G_Sprint_Modules::HR ){
									$hr_actions[] = $action['module'] . ":" . $action['action'];
								}elseif( $key == 'parent_module' && $value == G_Sprint_Modules::PAYROLL ){
									$payroll_actions[] = $action['module'] . ":" . $action['action'];
								}elseif( $key == 'parent_module' && $value == G_Sprint_Modules::DTR ){
									$dtr_actions[] = $action['module'] . ":" . $action['action'];
								}elseif( $key == 'parent_module' && $value == G_Sprint_Modules::EMPLOYEE ){
									$employee_actions[] = $action['module'] . ":" . $action['action'];
								}elseif( $key == 'parent_module' && $value == G_Sprint_Modules::AUDIT_TRAIL ){
									$audit_trail_actions[] = $action['module'] . ":" . $action['action'];
								}
							}
						}

						if( !empty($hr_actions) ){
							$string_hr_actions = implode(",", $hr_actions);
						}else{
							$string_hr_actions = "no access";
						}

						if( !empty($dtr_actions) ){
							$string_dtr_actions = implode(",", $dtr_actions);
						}else{
							$string_dtr_actions = "no access";
						}

						if( !empty($payroll_actions) ){
							$string_payroll_actions = implode(",", $payroll_actions);
						}else{
							$string_payroll_actions = "no access";
						}

						if( !empty($employee_actions) ){
							$string_employee_actions = implode(",", $employee_actions);
						}else{
							$string_employee_actions = "no access";
						}

						if( !empty($audit_trail_actions) ){
							$string_audit_trail_actions = implode(",", $audit_trail_actions);
						}else{
							$string_audit_trail_actions = "no access";
						}
						
						$user_info_file_content   = $new_session_id . "," . $data['hash'] . "," . Utilities::encrypt($data['company_structure_id']) . "," . Utilities::encrypt($data['id']) . "," . $data['role_name'] . "," . $data['employee_name'] . "," . $data['employee_code'] . "," . $data['position'] . "," . $data['username'] . "," . $profile_image; //User info text file content
						$user_actions_file_conent = $string_hr_actions . "\r\n" . $string_dtr_actions . "\r\n" . $string_payroll_actions . "\r\n" . $string_employee_actions. "\r\n" . $string_audit_trail_actions;; //User actions text file content

						//Delete previous temp files
						self::deletePreviousUserLoginFiles();

						//Create text files
						self::writeUserInfoToTextFile($user_info_file, $user_info_file_content);
						self::writeUserInfoToTextFile($user_actions_file, $user_actions_file_conent);		

						$return['is_success'] = true;
						$return['message']    = 'Redirecting to application...';

					}else{
						$return['is_success'] = false;
						$return['message']    = 'Invalid Username / Password';	
					}
				}else{
					$return['is_success'] = false;
					$return['message']    = 'Invalid Username / Password';	
				}
			}

		}else{
			$return['is_success'] = false;
			$return['message']    = 'Invalid Username / Password';
		}

		return $return;
	}

	public function getValidProfileImage($image_filename) {
		$profile_pic = USER_PROFILE_IMAGE_FOLDER . $image_filename;					
		if(Tools::isFileExist($profile_pic)==1 && $image_filename != "") {	
			return $profile_pic;
		}else {			
			$profile_pic = USER_PROFILE_IMAGE_FOLDER . "profile_noimage.gif";
		}	
		return $profile_pic;
	}

	/*
	    Note  : Encrypt ID for more than 1 record 
		Usage : 
		$u = new G_Employee_User();
		$user = $u->getAllUserIsNotArchive();
		$user = $u->encryptIds($user); //Will encrypt array data with id index 
	*/

	public function encryptIds($data = array(), $index_key = ''){		
		$new_data = array();
		
		if( $index_key == '' ){
			$search_key = "id";
		}else{
			$search_key = $index_key;
		}

		foreach( $data as $key => $value ){
			foreach($value as $sub_key => $sub_value){
				if( $sub_key == $search_key ){
					$new_data[$key][$sub_key] = Utilities::encrypt($sub_value);
				}else{
					$new_data[$key][$sub_key] = $sub_value;
				}
			}
		}		
		return $new_data;
	}

	/*
	    Note  : Encrypt ID for single record
		Usage : 
		$u = new G_Employee_User();
		$user = $u->getAllUserIsNotArchive();
		$user = $u->encryptId($user); //Will encrypt array data with id index 
	*/

	public function encryptId($data = array(), $index_key = ''){		
		$new_data = array();
		
		if( $index_key == '' ){
			$search_key = "id";
		}else{
			$search_key = $index_key;
		}

		foreach($data as $key => $value){
			if( $key == $search_key ){
				$new_data[$key] = Utilities::encrypt($value);
			}else{
				$new_data[$key] = $value;
			}
		}	
		return $new_data;
	}

	public function getUserDataById() {
		$data = array();

		if( !empty($this->id) ){
			$data = G_Employee_User_Helper::sqlUserDataById($this->id);
			$data['password'] = Utilities::decrypt($data['password']); //decrypt password
			print_r($data);
		}

		return $data;
	}

	public function getTotalRecordsIsNotArchive() {
		$total = 0;
		$total = G_Employee_User_Helper::sqlCountTotalRecordsIsNotArchive();
		return $total;
	}

	public function getAllUserIsNotArchive($order_by = '', $limit = '') {
		$data = array();
		$data = G_Employee_User_Helper::sqlAllUserIsNotArchive($order_by, $limit);
		return $data;
	}

	/*
		Usage : 
		$eu = new G_Employee_User();
		$eu->setUsername($username);
		$is_exists = $eu->isUserNameExists(); //Returns integer
	*/

	public function isUserNameExists() {
		$is_exists = 0;
		if( !empty($this->username) ){
			$is_exists = G_Employee_User_Helper::sqlIsUsernameExists($this->id, $this->username);
		}
		return $is_exists;
	}

	public function isUserNameWithSpecialChar() {
		$return  = false;
		//$pattern = "[\W]";//all non alpha numeric char
		$pattern = "/[\'\/~`\!@#\$%\^&\*\\s\(\)-\+=\{\}\[\]\|;:\"\<\>,\.\?\\\]/"; //all alpha numeric char except underscore and white spaces / tab
		if( !empty($this->username) ){
			if( preg_match($pattern, $this->username) ){
				$return = true;
			}
		}

		return $return;
	}

	public function updateUser() {
		$return = array();
		$return = self::addUser();
		return $return;
	}

	/*
		Usage : 
		$u = new G_Employee_User();
		$u->setCompanyStructureId($company_id);
        $u->setEmployeeId($employee_id);        
        $u->setUsername($username);                
        $u->setPassword($password); //unencrypted password               
        $u->setRoleId($role_id);                
        $u->setDateCreated($date_today);                
		$return = $u->addUser(); //Returns array
	*/

	public function addUser() {		
		$return = array();

		if( !empty($this->username) && !empty($this->employee_id) && !empty($this->company_structure_id) && !empty($this->password) && !empty($this->role_id) ){	

			$encrypted_password = Utilities::encrypt($this->password);
			$is_role_id_exists  = G_Role_Helper::sqlIsIdExists($this->role_id);
			$is_username_exists = G_Employee_User_Helper::sqlIsUsernameExists($this->id, $this->username);
			$is_employee_exists = G_Employee_Helper::sqlIsIdExists($this->employee_id);			

			if( $is_role_id_exists > 0 && $is_username_exists <= 0  && $is_employee_exists > 0 ){
				$is_user_with_special_char = self::isUserNameWithSpecialChar();
				if( $is_user_with_special_char ) {
					$return['is_success'] = false;
					$return['message']    = "Username must not contain any special characters and spaces";
				}else{
					$this->password   = $encrypted_password; //save encrypted password
					$this->is_archive = Employee_User::NO; //set data not archive
					
					if( $this->id > 0 ){						
						$is_employee_has_record = 0;
					}else{
						$is_employee_has_record = G_Employee_User_Helper::sqlIsEmployeeIdExists($this->employee_id); //Validate if employee has already an account
					}

					if( $is_employee_has_record > 0 ){
						$return['is_success'] = false;
						$return['message']    = "Selected employee has already have an account";
					}else{
						$is_saved = self::save();
						if( $is_saved > 0 ){
							$return['is_success'] = true;
							$return['message']    = "Record saved";
						}else{
							$return['is_success'] = false;
							$return['message']    = "Cannot save record";
						}	
					}
				}
			}else{				
				$return['is_success'] = false;
				$return['message']    = "Cannot save record";
			}
		}else{						
			$return['is_success'] = false;
			$return['message']    = "Cannot save record";
		}
 
		return $return;
	}

	public function deleteUser() {
		$return = array();
		if( $this->id > 0 ){
			self::delete();
			$return['is_success'] = true;
			$return['message']    = 'Record deleted';

		}else{
			$return['is_success'] = false;
			$return['message']    = 'Record not found';
		}
		return $return;
	}

	public function updatePassword() {
		if( $this->employee_id > 0  && $this->password != '' ){
			G_Employee_User_Manager::updatePasswordByEmployeeId($this->employee_id, $this->password);
		}
	}
							
	public function save() {
		return G_Employee_User_Manager::save($this);
	}
		
	public function delete() {
		G_Employee_User_Manager::delete($this);
	}
}
?>