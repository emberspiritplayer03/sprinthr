<?php
class G_Validate_Permission {

	protected $module;
	protected $parent_index;
	protected $child_index;
	protected $show_error_page;

	protected $user_role;

	public function __construct($data) {
		$this->user_role = $data;
	}

	public function setModule($value) {
		$this->module = $value;
	}

	public function setParentIndex($value) {
		$this->parent_index = $value;
	}

	public function setChildIndex($value) {
		$this->child_index = $value;
	}

	public function setShowErrorPage($value) {
		$this->show_error_page = $value;
	}

	public function getUserPermission() {

		$m = new G_Sprint_Modules($this->module);
		$actions = $m->getModuleActions($this->parent_index, $this->child_index);

		//get user role 
		$user_roles = $this->user_role;
		if(!is_array($user_roles) && !empty($user_roles)) {
			$user_roles = trim($user_roles);
			if( $user_roles == G_Employee_User::OVERRIDE_HR_ACCESS || $user_roles == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $user_roles == G_Employee_User::OVERRIDE_DTR_ACCESS || $user_roles == G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS || $user_roles == G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS ){    				
				return true;
			}
		}

		if(!empty($this->child_index)) {
			$index = $this->child_index;
		}else{
			$index = $this->parent_index;
		}
		
		foreach($user_roles as $key => $value) {
			//SUPER USER
			$module_name = trim($value['module']);  
			if( $module_name == G_Employee_User::OVERRIDE_HR_ACCESS || $module_name == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $module_name == G_Employee_User::OVERRIDE_DTR_ACCESS || $module_name == G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS || $user_roles == G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS){    				
				return true;
			}

			$return = self::checkPermission($value['module'], $index, $value['action'], $actions);
			if($return['is_success']) {
				break;
			}

		}		
		if(!$return['is_success']) {
			if($this->show_error_page) {
				include APP_PATH . 'errors/credentials_required.php';
				die();
			}
		}

		return $return['action'];		
	}

	//old checkpermission
	/*public function checkPermission($module = '', $index = '', $action = '', $actions = array()) {
		$return['is_success'] 	= false;
		$return['action']		= '';
		if(trim($module) == trim($index)) {
			if(in_array(trim($action), $actions)) {
				if(trim($action) == Sprint_Modules::PERMISSION_04) {

				}else{
					$return['is_success'] 	= true;
					$return['action']		= $action;
					return $return;
					
				}
			}
		} 
		return $return;
	}*/
	

	public function checkPermission($module = '', $index = '', $action = '', $actions = array()) {
		$return['is_success'] 	= false;
		$return['action']		= '';
		if(trim($module) == trim($index)) {
			if(in_array(trim($action), $actions)) {
				if(trim($action) == Sprint_Modules::PERMISSION_04) {
					if($module == 'reports'){

						$u = new G_Employee_User();
						$user_data = $u->getUserInfoDataFromTextFile();
						$payroll_data  = $user_data['user_actions'][2];
						foreach($payroll_data as $key => $value){				
							if( trim($value) != "no access" && trim($value) != G_Employee_User::OVERRIDE_PAYROLL_ACCESS){
								$mod_actions = array();
								$mod_actions = explode(":", $value);
								$user_actions['payroll'][$key]['module'] = $mod_actions[0]; 
								$user_actions['payroll'][$key]['action'] = $mod_actions[1]; 
							}
						}

						   if( !empty($user_actions['payroll']) ){
								$global_user_action = $user_actions['payroll'];
							}

							$permissions = new G_Validate_Permission($global_user_action);
							$permissions->setModule(G_Sprint_Modules::PAYROLL);
							$permissions->setParentIndex('reports');
							$permissions->setChildIndex($child_index);
							$permissions->setShowErrorPage($show_error_page);
							return $permissions->getUserPermission();
						
					}
				}else{
					$return['is_success'] 	= true;
					$return['action']		= $action;
					return $return;
					/*$return = true;
					$action = $value['action'];*/
				}
			}
		} 
		return $return;
	}




	
}
?>