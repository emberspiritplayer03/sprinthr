<?php
class Sprint_Menu_Builder {
	protected $allowed_modules;	
	protected $core_module;
	protected $selected_module;

	public function __construct($modules = array(), $parent_module = '', $current_module = '') {
		$this->allowed_modules = $modules;
		$this->core_module     = $parent_module;
		$this->selected_module = $current_module;
	}

	private function buildSwitchButton( $core_module = '', $module_name = '', $caption = '' ){
		$mod     = new G_Sprint_Modules($core_module);
		$modules = $mod->getModuleList();				
		foreach( $modules as $key => $module ){					
			if( $key == $module_name ){
				$btn_url = $module['url'];
				break;
			}else{
				$children = $module['children'];
				if( !empty($children) && array_key_exists($module_name, $children) ){
					$btn_url = $children[$module_name]['url'];
					break;
				}  
			}
		}
		
		$switch_btn = "<a class=\"button_link blue_button view_profile\" href=\"{$btn_url}\">{$caption}</a>";
		return $switch_btn;
	}

	public function buildSwitchToMenu(){
		$switch_to       = "";						
		$hr_modules      = array();
		$payroll_modules = array();
		$dtr_modules     = array();
		$employee_modules = array();


		if( !empty($this->allowed_modules) ){	
			$modules = $this->allowed_modules;				
			
			if($modules['hr'] == G_Employee_User::OVERRIDE_HR_ACCESS || $modules['dtr'] == G_Employee_User::OVERRIDE_DTR_ACCESS || $modules['payroll'] == G_Employee_User::OVERRIDE_PAYROLL_ACCESS){ // if with override access return all 3 switch buttons - super admin
				
				$switch_btn = "<span class=\"switchto \">Switch To : </span>
					<a class=\"button_link blue_button view_profile\" href=\"" . url('employee') . "\">HR</a>
					<a class=\"button_link blue_button view_profile\" href=\"" . url('payroll_register/generation') . "\">PAYROLL</a>
					<a class=\"button_link blue_button view_profile\" href=\"" . url('dtr') . "\">DTR</a>";

				return $switch_btn;
			}

			foreach($modules['hr'] as $key => $value){
				$hr_modules[$value['module']] = trim($value['action']);
				if( trim($value['action']) != Sprint_Modules::PERMISSION_04 && !isset($hr_module) ){
					$hr_module  = trim($value['module']);
				}
			}

			foreach($modules['payroll'] as $key => $value){
				$payroll_modules[$value['module']] = trim($value['action']);
				if( trim($value['action']) != Sprint_Modules::PERMISSION_04 && !isset($payroll_module) ){					
					$payroll_module  = trim($value['module']);					
				}
			}

			foreach($modules['dtr'] as $key => $value){
				$dtr_modules[$value['module']] = trim($value['action']);
				if( trim($value['action']) != Sprint_Modules::PERMISSION_04 && !isset($dtr_module) ){
					$dtr_module  = trim($value['module']);
				}
			}

			foreach($modules['employee'] as $key => $value){
				$employee_modules[$value['module']] = trim($value['action']);
				if( trim($value['action']) != Sprint_Modules::PERMISSION_04 && !isset($employee_module) ){
					$employee_module  = trim($value['module']);
				}
			}

			$search_value     = array(Sprint_Modules::PERMISSION_01, Sprint_Modules::PERMISSION_02, Sprint_Modules::PERMISSION_03, G_Employee_User::OVERRIDE_HR_ACCESS, G_Employee_User::OVERRIDE_PAYROLL_ACCESS, G_Employee_User::OVERRIDE_DTR_ACCESS);
			$is_with_hr       = false;
			$is_with_payroll  = false;
			$is_with_dtr      = false;
			$is_with_employee = false;

			foreach( $search_value as $key => $value ){
				if( in_array($value, $hr_modules) ){					
					$is_with_hr = true;										
					break;
				}
			}

			foreach( $search_value as $key => $value ){
				if( in_array($value, $payroll_modules) ){										
					$is_with_payroll = true;					
					break;
				}
			}

			foreach( $search_value as $key => $value ){
				if( in_array($value, $dtr_modules) ){								
					$is_with_dtr = true;										
					break;
				}
			}

			foreach( $search_value as $key => $value ){
				if( in_array($value, $employee_modules) ){								
					$is_with_employee = true;										
					break;
				}
			}

			if( $is_with_hr ){				
				$hr_switch_to = self::buildSwitchButton(G_Sprint_Modules::HR, $hr_module, 'HR');				
			}

			if( $is_with_payroll ){
				$payroll_switch_to = self::buildSwitchButton(G_Sprint_Modules::PAYROLL, $payroll_module, 'PAYROLL');
			}

			if( $is_with_dtr ){
				$dtr_switch_to = self::buildSwitchButton(G_Sprint_Modules::DTR, $dtr_module, 'DTR');				
			}

			if( $is_with_employee ){				
				$employee_Switch_to = self::buildSwitchButton(G_Sprint_Modules::EMPLOYEE, $employee_module, 'EMPLOYEE');				
			}
			
			if( !empty($hr_switch_to) || !empty($payroll_switch_to) || !empty($dtr_switch_to) || !empty($employee_Switch_to) ){
				$switch_to = "<span class=\"switchto \">Switch To : </span>{$hr_switch_to} {$payroll_switch_to} {$dtr_switch_to} {$employee_Switch_to}";
			}
		}

		return $switch_to;
	}

	public function buildHeaderMenu($value){		
		$modules 		= $this->allowed_modules;
		$is_super_admin = false;

		if( !is_array($modules) && !empty($modules) ){
			$is_super_admin = true;
		}else{
			foreach( $modules as $m ){
				$module = trim($m['module']);					
				if( $module == G_Employee_User::OVERRIDE_HR_ACCESS || $module == G_Employee_User::OVERRIDE_PAYROLL_ACCESS || $module == G_Employee_User::OVERRIDE_DTR_ACCESS || $module == G_Employee_User::OVERRIDE_EMPLOYEE_ACCESS ){
					$is_super_admin = true;
				}
			}
		}
		
		if( $is_super_admin ){
			$menu = self::superAdminHeaderMenu($value);
		}else{
			$menu = self::userHeaderMenu($value);
		}
		
		return $menu;
	}

	public function superAdminHeaderMenu($value) {
		$menu = "";

		if( !empty($this->core_module) ){

			$sm = new G_Sprint_Modules($this->core_module);
			$all_modules       = $sm->getModuleList();
			$transparent_image = BASE_FOLDER . "themes/" . THEME . "/themes-images/transparent.png";

			foreach( $all_modules as $key => $module ){
				$sub_modules = $module['children'];
				$caption     = $module['caption'];
				$url         = $module['url'];
				$is_visible  = $module['is_visible'];
				$class       = $module['attributes']['class'];
				$id          = $module['attributes']['id'];

				$string_sub_menu 		     = "";
				$transparent_image_add_class = "";
				$selected_parent_menu        = "";
								
				if( $this->selected_module == $key ){
					$selected_parent_menu = "class=\"selected\"";
				}

				if( !empty($sub_modules) ){ //Construct sub menu
					$array_sub_menu  = array();
					foreach( $sub_modules as $sub_key => $sub_value ){
						$sub_caption    = $sub_value['caption'];
						$sub_url        = $sub_value['url'];
						$sub_is_visible = $sub_value['is_visible'];
						$sub_class      = $sub_value['attributes']['class'];
						$sub_id         = $sub_value['attributes']['id'];

						if( $sub_is_visible == Sprint_Modules::YES ){
							$array_sub_menu[] = "<li><a id=\"{$sub_id}\" href=\"{$sub_url}\">{$sub_caption}</a>";
						}
					}

					if( !empty($array_sub_menu) ){
						$total_sub_menu     = count($array_sub_menu);
						$new_array_sub_menu = array();								
						$count = 1;
						foreach( $array_sub_menu as $sub_menu_value ){
							if( $count == 1 ){
								$sub_menu_value = str_replace("<li>", "<li class=\"first\">", $sub_menu_value);
							}elseif( $count == $total_sub_menu ){
								$sub_menu_value = str_replace("<li>", "<li class=\"last\">", $sub_menu_value);
							}
							$new_array_sub_menu[] = $sub_menu_value;
							$count++;
						}

						$string_sub_menu 			 = implode("</li>", $new_array_sub_menu);
						$transparent_image_add_class = "class=\"menudropicon\"";

						$string_sub_menu = "<div class=\"sbmnhldr\"><span class=\"sbmntp_arrw\"></span><ul class=\"submenu\">{$string_sub_menu}</ul>";
					}
				}


				$target = "<a id=\"{$id}\" href=\"{$url}\"><span class=\"{$class}\"></span>{$caption}<img src=\"{$transparent_image}\" {$transparent_image_add_class} border=\"0\" /></a>";

				$menu .= "<li {$selected_parent_menu}>{$target}{$string_sub_menu}</li>";				
			}
		}

		$menu = "<ul class=\"mainmenu\">{$menu}</ul>";
		return $menu;
	}
	
	public function userHeaderMenu($value) {
		$menu = "";

		if( !empty($this->allowed_modules) && !empty($this->core_module) ){

			$user_modules = array();			
			foreach($this->allowed_modules as $key => $module){ // Reconstruct allowed modules, shift module name to index
				$user_modules[$module['module']] = $module['action'];
			}

			$sm = new G_Sprint_Modules($this->core_module);
			$all_modules       = $sm->getModuleList();
			$transparent_image = BASE_FOLDER . "themes/" . THEME . "/themes-images/transparent.png";

			foreach( $all_modules as $key => $module ){
				$sub_modules = $module['children'];
				$caption     = $module['caption'];
				$url         = $module['url'];
				$is_visible  = $module['is_visible'];
				$class       = $module['attributes']['class'];
				$id          = $module['attributes']['id'];

				$string_sub_menu 		     = "";
				$transparent_image_add_class = "";
				$selected_parent_menu        = "";

				if( array_key_exists($key, $user_modules) && $is_visible == Sprint_Modules::YES ){
					$action = $user_modules[$key];					
					if( $this->selected_module == $key ){
						$selected_parent_menu = "class=\"selected\"";
					}

					if( trim($action) != Sprint_Modules::PERMISSION_04 ){

						if( !empty($sub_modules) ){ //Construct sub menu
							$array_sub_menu  = array();
							foreach( $sub_modules as $sub_key => $sub_value ){
								$sub_caption    = $sub_value['caption'];
								$sub_url        = $sub_value['url'];
								$sub_is_visible = $sub_value['is_visible'];
								$sub_class      = $sub_value['attributes']['class'];
								$sub_id         = $sub_value['attributes']['id'];

								if( array_key_exists($sub_key, $user_modules) && $sub_is_visible == Sprint_Modules::YES ){
									$sub_action = $user_modules[$sub_key];
									if( $sub_action != Sprint_Modules::PERMISSION_04 ){
										$array_sub_menu[] = "<li><a id=\"{$sub_id}\" href=\"{$sub_url}\">{$sub_caption}</a>";
									}
								}
							}

							if( !empty($array_sub_menu) ){
								$total_sub_menu     = count($array_sub_menu);
								$new_array_sub_menu = array();								
								$count = 1;
								foreach( $array_sub_menu as $sub_menu_value ){
									if( $count == 1 ){
										$sub_menu_value = str_replace("<li>", "<li class=\"first\">", $sub_menu_value);
									}elseif( $count == $total_sub_menu ){
										$sub_menu_value = str_replace("<li>", "<li class=\"last\">", $sub_menu_value);
									}
									$new_array_sub_menu[] = $sub_menu_value;
									$count++;
								}

								$string_sub_menu 			 = implode("</li>", $new_array_sub_menu);
								$transparent_image_add_class = "class=\"menudropicon\"";

								$string_sub_menu = "<div class=\"sbmnhldr\"><span class=\"sbmntp_arrw\"></span><ul class=\"submenu\">{$string_sub_menu}</ul>";
							}
						}


						$target = "<a id=\"{$id}\" href=\"{$url}\"><span class=\"{$class}\"></span>{$caption}<img src=\"{$transparent_image}\" {$transparent_image_add_class} border=\"0\" /></a>";

						$menu .= "<li {$selected_parent_menu}>{$target}{$string_sub_menu}</li>";
					}
				}
			}
		}

		$menu = "<ul class=\"mainmenu\">{$menu}</ul>";
		return $menu;
	}
}
?>