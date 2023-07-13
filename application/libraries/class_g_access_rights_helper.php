<?php
class G_Access_Rights_Helper {
		
	public static function isIdExist(G_Access_Rights $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_ACCESS_RIGHTS ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function getUnserializeRights($ar) {
		if($ar) {
			$rights = unserialize($ar->getRights());
			$hr_module = $rights['sub_module_access'][HR];
			
			$r['hr_dashboard_main_settings']= $hr_module[DASHBOARD]['main_settings'];
			$r['hr_dashboard_gen_info']		= $hr_module[DASHBOARD]['general_information'];
			$r['hr_dashboard_employee'] 	= $hr_module[DASHBOARD]['employee'];
			$r['hr_dashboard_recruitment'] 	= $hr_module[DASHBOARD]['recruitment'];
			
			$r['hr_recruitment_main_settings']	= $hr_module[RECRUITMENT]['main_settings'];
			$r['hr_recruitment_candidate']		= $hr_module[RECRUITMENT]['candidate'];
			$r['hr_recruitment_job_vacancy'] 	= $hr_module[RECRUITMENT]['job_vacancy'];
			$r['hr_recruitment_examination'] 	= $hr_module[RECRUITMENT]['examination'];
			
			$r['hr_employee_main_settings']	= $hr_module[EMPLOYEE_MODULE]['main_settings'];
			$r['hr_employee_employee_management'] 	= $hr_module[EMPLOYEE_MODULE]['employee_management'];
			$r['hr_employee_account_management'] 	= $hr_module[EMPLOYEE_MODULE]['account_management'];
			$r['hr_employee_deduction_management'] 	= $hr_module[EMPLOYEE_MODULE]['deduction_management'];
			$r['hr_employee_schedule']		= $hr_module[EMPLOYEE_MODULE]['schedule'];
			$r['hr_employee_leave'] 		= $hr_module[EMPLOYEE_MODULE]['leave'];
			$r['hr_employee_overtime'] 		= $hr_module[EMPLOYEE_MODULE]['overtime'];
			$r['hr_employee_attendance'] 	= $hr_module[EMPLOYEE_MODULE]['attendance'];
			$r['hr_employee_performance'] 	= $hr_module[EMPLOYEE_MODULE]['performance'];

			return $r;
		}
	}
	
	public static function verifyUserAccessRights($employee_id,$module_access,$sub_module_access) {
		
		return $_SESSION['sprint_hr']['access_rights']['can_manage'] = true;
		
		//$employee_id = 7;
		unset($_SESSION['sprint_hr']['access_rights']['can_manage']);
		$user = G_User_Finder::findByEmployeeId($employee_id);
	
		if($user) {
			$ar_group 	= G_Access_Rights_Finder::findByUserGroupIdAndPolicyType($user->getId(),G_Access_Rights::GROUP);
			$ar_user 	= G_Access_Rights_Finder::findByUserGroupIdAndPolicyType($user->getId(),G_Access_Rights::USER);
			
			if($ar_group) { $ar = $ar_group; }
			else if($ar_user) { $ar = $ar_user; }
			
			if($ar) {
				$rights = unserialize($ar->getRights());
				foreach($sub_module_access as $key=>$value):
					$module 		= $rights['sub_module_access'][$module_access][$key];
					$specific_mod	= $value;
				endforeach;
				 
				foreach($module as $key=>$value):
					if($key == "main_settings") {
						if($value == NO_ACCESS) {
							include APP_PATH . 'errors/credentials_required.php';
							die();
						}
					}
					if($specific_mod == $key) {
						if($value == CAN_MANAGE) {
							$_SESSION['sprint_hr']['access_rights']['can_manage'] = true;
						} else if($value == HAS_ACCESS) {
							
						} else {
							include APP_PATH . 'errors/credentials_required.php';
							die();
						}
					}
				endforeach;
				
				return $return;
			}
		} else {
			// CHECK DEFAULT VALUES	
			self::verifyDefaulAccessRights($module_access,$sub_module_access);
		}
		
	}
	
	public static function verifyDefaulAccessRights($module,$sub_module_access) {
		unset($_SESSION['sprint_hr']['access_rights']['can_manage']);
		$access = $GLOBALS['sprint_hr']['module_access'][$module];
		if($access == NO_ACCESS) {
			include APP_PATH . 'errors/credentials_required.php';
			die();	
		} else {
		
			//echo '<pre>';
			foreach($sub_module_access as $key=>$value):
				$mod 		= $GLOBALS['sprint_hr']['sub_module_access'][$module][$key];
				$specific_mod	= $value;
			endforeach;
			foreach($mod as $key=>$value):
				if($key == "main_settings") {
					if($value == NO_ACCESS) {
						include APP_PATH . 'errors/credentials_required.php';
						die();
					}
				}
				if($specific_mod == $key) {
					if($value == CAN_MANAGE) {
						$_SESSION['sprint_hr']['access_rights']['can_manage'] = true;
					} else if($value == HAS_ACCESS) {
					} else {
						include APP_PATH . 'errors/credentials_required.php';
						die();
					}
				}
			endforeach;
			
		}
	}

	public static function verifyAccessRights1($employee_id,$mod,$action) {
		
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
			echo Utilities::getUserAccessRights1($employee_id,$mod);
		}else {
			echo 'not module';
		}
		
		
	}
	
	public static function getUserAccessRights1($employee_id,$mod)
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
}
?>