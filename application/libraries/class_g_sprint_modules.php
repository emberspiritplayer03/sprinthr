<?php

class G_Sprint_Modules extends Sprint_Modules {
	
	protected $modules;

	const PAYROLL 		= 'payroll';
	const HR      		= 'hr';
	const DTR     		= 'dtr';
	const EMPLOYEE  	= 'employee';
	const AUDIT_TRAIL  	= 'audit_trail';
	
	public function __construct($module = '') {
		
		$array_modules = array();

		if( $module == self::PAYROLL ){			
			$array_modules = self::payrollModules();
		}elseif( $module == self::HR ){
			$array_modules = self::hrModules();
		}elseif( $module == self::DTR ){
			$array_modules = self::dtrModules();
		}elseif( $module == self::EMPLOYEE ){
			$array_modules = self::employeeModules();
		}elseif( $module == self::AUDIT_TRAIL ){
			$array_modules = self::auditTrailModules();
		}

		$this->modules = $array_modules;
	}

	/*
		Usage
		$access_module        = 'settings';
		$user_allowed_modules = array('settings' => 'No Access');
		$m = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$modules = $m->validateUserCanAccessModule($user_allowed_modules,$access_module);
	*/

	public function validateUserCanAccessModule($user_allowed_modules, $access_module) { 	   		
    	if( !empty($user_allowed_modules) && !empty($this->modules) && !empty($access_module) ){    		
    		$new_user_allowed_modules = array();
    		if( is_array($user_allowed_modules)){
	    		foreach($user_allowed_modules as $key => $value){ //Reconstruct allowed modules array, set module name as index and value to action
	    			$module_name = trim($value['module']);    			
	    			if( $module_name == G_Employee_User::OVERRIDE_HR_ACCESS || $module_name == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $module_name == G_Employee_User::OVERRIDE_DTR_ACCESS ){    				
	    				return true;
	    			}
	    			$new_user_allowed_modules[$value['module']] = $value['action'];
	    		}
    		}else{    			
				if( trim($user_allowed_modules) == G_Employee_User::OVERRIDE_HR_ACCESS || trim($user_allowed_modules) == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || trim($user_allowed_modules) == G_Employee_User::OVERRIDE_DTR_ACCESS ){    				
    				return true;
    			}
    		}
    		
    		if( array_key_exists($access_module, $new_user_allowed_modules)){
				$user_permitted_action = $new_user_allowed_modules[$access_module];    			
    			if( trim($user_permitted_action) == Sprint_Modules::PERMISSION_04 ){
    				include APP_PATH . 'errors/credentials_required.php';
					die ();	
    			}
			}else{
				include APP_PATH . 'errors/credentials_required.php';
				die ();	
			}  
    	}else{
    		include APP_PATH . 'errors/404.php';
			die ();	
    	}
    }

	/*
		Usage 
		$parent_module = 'payroll';
		$m = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
		$modules = $m->getSubModules($parent_module); // returns array
	*/

	public function getModuleList(){
		return $this->modules;
	}

	public function getSubModules( $index_name = '' ){
		$sub_modules = array();		
		
		if( !empty($this->modules) && !empty($index_name) ){
			$modules     = $this->modules;
			$sub_modules = $modules[$index_name];
		}

		return $sub_modules;
	}

	/*
		Usage
		$m = new G_Sprint_Modules(G_Sprint_Modules::PAYROLL);
		$modules = $m->getAllModules(); //Returns all modules set in constructor
	*/

	public function getAllModules() {
		return $this->modules;
	}

	/*
		Usage
		
		$parent_index = 'employees';
		$child_index  = 'personal_details'; //optional - if set will get parent child property else will return parent properties

		$m = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$modules = $m->getModuleProperties($parent_index, $child_index);
	*/

	public function getModuleProperties( $parent_index = '', $child_index = '' ){
		$module_properties = array();

		if( !empty($parent_index) ){
			$modules = $this->modules;
			if( !empty($child_index) ){				
				$module_properties = $modules[$parent_index]['children'][$child_index]; //return submodule properties
			}else{
				$module_properties = $modules[$parent_index]; //return parent properties
			}
		}

		return $module_properties;
	}  

	/*
		Usage
		
		$parent_index = 'employees';
		$child_index  = 'personal_details'; //optional - if set will return parent child property else will return parent properties

		$m = new G_Sprint_Modules(G_Sprint_Modules::HR);
		$actions = $m->getModuleActions($parent_index, $child_index);
	*/

	public function getModuleActions( $parent_index = '', $child_index = ''){
		$module_actions = array();		
		if( !empty($parent_index) ){
			$modules = $this->modules;
			if( !empty($child_index) ){				
				$module_actions = $modules[$parent_index]['children'][$child_index]['actions']; //return submodule actions
			}else{
				$module_actions = $modules[$parent_index]['actions']; //return parent actions
			}
		}

		return $module_actions;
	}
}
?>