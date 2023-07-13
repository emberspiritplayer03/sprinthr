<?php
class G_Button_Builder {
    public function __construct() {

	}

	public static function validatePermission($global_user_action = array(),$module = '', $parent_index = '', $child_index = '') {

		$permissions = new G_Validate_Permission($global_user_action);
		$permissions->setModule($module);
		$permissions->setParentIndex($parent_index);
		$permissions->setChildIndex($child_index);
		$permissions->setShowErrorPage(false);
		return $permissions->getUserPermission();
	}

	/*
	 *	for Anchor tag <a>Link</a>
	 *	e.g :
			$btn_import_dtr_config = array(
        		'module'				=> 'hr',
        		'parent_index'			=> 'attendance',
        		'child_index'			=> 'daily_time_record',
        		'required_permission'	=> Sprint_Modules::PERMISSION_01,
        		'href' 					=> 'javascript:void(0);',
        		'onclick' 				=> 'javascript:importTimesheet();',
        		'id' 					=> '',
        		'class' 				=> 'gray_button float-right',
        		'icon' 					=> '<i class="icon-excel icon-custom"></i>',
        		'additional_attribute' 	=> 'data-toggle="modal" data-target="#adHdrModal"',
        		'wrapper_start'			=> '<li>',
        		'wrapper_end'			=> '</li>',
        		'caption' 				=> 'Import DTR'
        		);

	        $this->var['btn_import_dtr'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_dtr_config);
	 */
	public static function createAnchorTagWithPermissionValidation($global_user_action = array() , $config = array()) {
		$a = '';

		$onclick = '';
		$permission_action = self::validatePermission($global_user_action, $config['module'], $config['parent_index'], $config['child_index']);		
		$is_superuser = false;
		if( $permission_action == G_Employee_User::OVERRIDE_HR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $permission_action == G_Employee_User::OVERRIDE_DTR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS ){ 
			$is_superuser = true;
		}

		if( !empty($config['required_permission']) ) {
			$required_permission = $config['required_permission'];
		}else{
			$required_permission = Sprint_Modules::PERMISSION_02; //by default. View and Edit
		}

		if(trim($permission_action) == trim($required_permission) || $is_superuser || trim($permission_action) == trim(Sprint_Modules::PERMISSION_02)) {

			if( isset($config['onclick']) && $config['onclick'] != "" ){
				$onclick = "onclick='{$config['onclick']}'";
			}
			
			$a = "{$config['wrapper_start']}<a href='{$config['href']}' id='{$config['id']}' class='{$config['class']}' {$onclick} {$config['additional_attribute']} >{$config['icon']} {$config['caption']} </a>{$config['wrapper_end']}";
		}

		return $a;
	}

	/*
	 *	for Button tag <button>Link</button>
	 *	e.g :
			$btn_import_dtr_config = array(
        		'module'				=> 'hr',
        		'parent_index'			=> 'attendance',
        		'child_index'			=> 'daily_time_record',
        		'required_permission'	=> Sprint_Modules::PERMISSION_01,        		
        		'event' 				=> 'onmouseup',
        		'action' 				=> 'javascript:importTimesheet();',
        		'id' 					=> '',
        		'class' 				=> 'gray_button float-right',
        		'icon' 					=> '<i class="icon-excel icon-custom"></i>',
        		'additional_attribute' 	=> 'data-toggle="modal" data-target="#adHdrModal"',
        		'wrapper_start'			=> '<li>',
        		'wrapper_end'			=> '</li>',
        		'caption' 				=> 'Import DTR'
        		);

	        $this->var['btn_import_dtr'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_dtr_config);
	 */

	public static function createButtonWithPermissionValidation($global_user_action = array() , $config = array()) {
		$button = '';		
		$permission_action = self::validatePermission($global_user_action, $config['module'], $config['parent_index'], $config['child_index']);						
		$is_superuser = false;
		if( $permission_action == G_Employee_User::OVERRIDE_HR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $permission_action == G_Employee_User::OVERRIDE_DTR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS){ 
			$is_superuser = true;
		}

		if( !empty($config['required_permission']) ) {
			$required_permission = $config['required_permission'];
		}else{
			$required_permission = Sprint_Modules::PERMISSION_02; //by default. View and Edit
		}

		//echo $required_permission . " / ";

		if($permission_action == $required_permission || $is_superuser || $permission_action == Sprint_Modules::PERMISSION_02) {			
			$button = "{$config['wrapper_start']}<button id=\"{$config['id']}\" class=\"{$config['class']}\" type=\"button\" {$config['event']}=\"{$config['action']}\">{$config['icon']}{$config['caption']}</button>{$config['wrapper_end']}";
		}

		return $button;
	}

	public static function createAnchorTag($config = array()) {
		$a = "{$config['wrapper_start']}<a href='{$config['href']}' id='{$config['id']}' class='{$config['class']}' onclick='{$config['onclick']}' {$config['additional_attribute']} >{$config['icon']} {$config['caption']} </a>{$config['wrapper_end']}";	
		return $a;
	}

	/*
	 *	for Button tag <button>Link</button>
	 */
	public static function createButtonTagWithPermissionValidation($global_user_action = array() , $config = array()) {
		$button = '';

		$permission_action = self::validatePermission($global_user_action, $config['module'], $config['parent_index'], $config['child_index']);		
		$is_superuser = false;
		if( $permission_action == G_Employee_User::OVERRIDE_HR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $permission_action == G_Employee_User::OVERRIDE_DTR_ACCESS || $permission_action == G_Employee_User::OVERRIDE_AUDIT_TRAIL_ACCESS){ 
			$is_superuser = true;
		}
		
		if( !empty($config['required_permission']) ) {
			$required_permission = $config['required_permission'];
		}else{
			$required_permission = Sprint_Modules::PERMISSION_02; //by default. View and Edit
		}

		if($permission_action == $required_permission || $is_superuser || $permission_action == Sprint_Modules::PERMISSION_02) {
			$button = "{$config['wrapper_start']}<button type='{$config['type']}' id='{$config['id']}' class='{$config['class']}' onclick='{$config['onclick']}' {$config['additional_attribute']} >{$config['icon']} {$config['caption']} </button>{$config['wrapper_end']}";
		}

		return $button;
	}

	public static function createButtonTag($config = array()) {
		$button = "{$config['wrapper_start']}<button type='{$config['type']}' id='{$config['id']}' class='{$config['class']}' onclick='{$config['onclick']}' {$config['additional_attribute']} >{$config['icon']} {$config['caption']} </button>{$config['wrapper_end']}";
		return $button;
	}

}
?>