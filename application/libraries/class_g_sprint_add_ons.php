<?php
class G_Sprint_Add_Ons {	
	const ENABLED  = 'true';
	const DISABLED = 'false';
	public function __construct() {
		
	}
	
	public function addOnsInfo() {
		$sprint_add_ons= array(
			'employee_online_portal' => array(
				'label' => 'Employee Online Portal',
				'required_version' => '1.5.0000',
				'released_date' => '2015-02-06',
				'features' => array(
					"Can file and approve requests via online server", "Employee portal is now accessible online", "Only available for <b>v1.5.0000 and up</b>"
				)
			)
		);

		return $sprint_add_ons;
	}

	public function isAddOnEnabled($addon = '') {
		$return['is_addon_enabled'] = false;
		$return['message']          = 'Invalid addon key!';

		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/add_ons.xml';
		$xmlStr = file_get_contents($xmlUrl);
		$xmlStr = simplexml_load_string($xmlStr);

		$xml2   = new Xml;
		$arrXml = $xml2->objectsIntoArray($xmlStr);				

		if( array_key_exists($addon, $arrXml)){				
			$return['is_addon_enabled'] = $arrXml[$addon];
			$return['message']          = 'Valid addon key';
		}

		return $return;

	}

	public function getAddOnsList() {
		$data     = $this->addOnsInfo();
		$add_ons  = array();
		foreach( $data as $key => $value ){			
			$add_ons[$key] = $value['label'];
		}

		return $add_ons;
	}

	public function getAddOnInfo( $addon = '' ){
		$data         = $this->addOnsInfo();
		$addon_info = array();		
		
		if( array_key_exists(trim($addon), $data) ){			
			$addon_info = $data[$addon];
		}

		return $addon_info;
	}

	public function deactivateAddOn( $addon = '' ){
		$return['message']    = "<div class='alert alert-error'>Cannot deactivate selected addon!</div>";
		$return['is_success'] = false;

		if( !empty($addon) ){
			$addon  = trim($addon);
			if( array_key_exists($addon, $this->getAddOnsList()) ){ //verify if addon is valid		
				switch ($addon) {
					case 'employee_online_portal':						
						self::deactivateEmployeeOnlinePortal();
						self::updateAddOnsXml($addon, self::DISABLED); //Update xml settings
						break;					
					default:					
						break;
				}

				$return['message']    = "<div class='alert alert-info'>Selected addon was successfully deactivated!</div>";
				$return['is_success'] = true;
			}
		}

		return $return;
	}

	public function activateAddOn( $addon = '' ){		
		$return['message']    = "<div class='alert alert-error'>Cannot activate selected addon!</div>";
		$return['is_success'] = false;
		$addon  			  = trim($addon);

		if( array_key_exists($addon, $this->getAddOnsList()) ){ //verify if addon is valid								
			$addons = $this->addOnsInfo($addon);

			if( !empty($addon) ){							
				$v      = new G_Sprint_Version();
				$data   = $v->getAppVersion();			

				$version_part     = explode("/", $data);		
				$app_version      = trim($version_part[0]);		
				$required_version = trim($addons[$addon]['required_version']);
				$addon_label      = $addons[$addon]['label'];

				switch ($addon) {
					case 'employee_online_portal':						
						$return['message'] = self::activateEmployeeOnlinePortal();						
						self::updateAddOnsXml($addon, self::ENABLED); //Update xml settings
						break;					
					default:					
						break;
				}

				if( $app_version >= $required_version ){					
					$return['is_success'] = true;
					$return['message']    = "<div class='alert alert-info'>Addon <b>{$addon_label}</b> was successfully activated</div>";
				}else{
					$return['message']    = "Not compatible with current version.Update your sprintHR version to at least <b>{$required_version}</b>";	
				}
			}
		}

		return $return;   
	}

	public function deactivateEmployeeOnlinePortal(){
		$return = array();

		//Update and create tables     
		$return['drop_sync_data']                     = self::dropSyncDataTable();
		$return['drop_employee_online_sync_triggers'] = self::dropEmployeeOnlineSyncDataSQLTriggers();

		return $return;
	}

	private function updateAddOnsXml($addon_key = '', $value = self::DISABLED){
		//Update xml settings
		$return = false;
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/add_ons.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2   = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//addons');
			$obj[0]->{$addon_key} = $value;						
			$xmlStr->asXml($xmlUrl);
			$return = true;
		}

		return $return;
	}

	public function activateEmployeeOnlinePortal(){
		$return = array();

		//Update and create tables
		$return['sync_data']    = self::createSyncDataTable();
		$return['sql_triggers'] = self::createSQLTriggers();

		return $return;

	}

	private function dropSyncDataTable(){
		$return['message']    = '';
		$return['is_created'] = true;

		//Request Approvers
		$table_name      	   = SYNC_DATA;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( $is_table_exists ){						
			$sql = " DROP TABLE IF EXISTS `g_sync_data`;";			
			Model::runSql($sql);

			$message[] = "Table {$table_name} was successfully deleted!";
		}else{
			$message[] = "Table {$table_name} doesn't exists!";
		}
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createSyncDataTable(){
		$return['message']    = '';
		$return['is_created'] = true;

		//Request Approvers
		$table_name      	   = SYNC_DATA;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_sync_data` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `table_name` varchar(50) NOT NULL,
				  `pk_id_local` bigint(20) NOT NULL,
				  `pk_id_live` bigint(20) NOT NULL,
				  `action` varchar(20) NOT NULL,
				  `is_sync` varchar(10) NOT NULL,
				  `date_created` varchar(50) NOT NULL,
				  `date_modified` varchar(50) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `pk_id_local` (`pk_id_local`),
				  KEY `pk_id_live` (`pk_id_live`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";			
			Model::runSql($sql);

			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	public function createSQLTriggers(){
		$tables = array("g_allowed_ip","g_employee","g_leave","g_requests","g_request_approvers","g_request_approvers_level","g_request_approvers_requestors","g_roles","g_role_actions","g_employee","g_employee_contact_details","g_employee_leave_available","g_employee_leave_request","g_employee_overtime","g_employee_overtime_request","g_employee_payslip","g_employee_user","g_employee_official_business_request");	

		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 					
		foreach( $tables as $table_name ){
			$sql = "				
				CREATE DEFINER=`root`@`localhost` TRIGGER `trig_{$table_name}_ai` AFTER INSERT ON `{$table_name}` 
				FOR EACH ROW BEGIN
					INSERT INTO g_sync_data
					SET table_name = '{$table_name}',
					pk_id_local = NEW.id,
					pk_id_live = 0,
					ACTION = \"insert\",
					is_sync = \"No\",
					date_created = NOW( ) ,
					date_modified = NOW( ) ;
				END";
			$result = $mysqli->query($sql);				
			$sql = "								
				CREATE DEFINER=`root`@`localhost` TRIGGER `trig_{$table_name}_au` AFTER UPDATE ON `{$table_name}` 
				FOR EACH ROW BEGIN
					IF( (SELECT COUNT(id) FROM g_sync_data WHERE table_name = '{$table_name}' AND pk_id_local = NEW.id AND pk_id_live = 0 AND ACTION = \"update\") <= 0 )
					THEN 
						INSERT INTO g_sync_data
						SET table_name = '{$table_name}',
						pk_id_local = NEW.id,
						pk_id_live = 0,
						ACTION = \"update\",
						is_sync = \"No\",
						date_created = NOW( ) ,
						date_modified = NOW( ) ;
					END IF;
				END";
			$result = $mysqli->query($sql);		

			$sql = "				
				CREATE DEFINER=`root`@`localhost` TRIGGER `trig_{$table_name}_ad` AFTER DELETE ON `{$table_name}` 
				FOR EACH ROW BEGIN
					IF( (SELECT COUNT(id) FROM g_sync_data WHERE table_name = '{$table_name}' AND pk_id_local = OLD.id AND pk_id_live = 0 AND ACTION = \"delete\") <= 0 )
					THEN 
						INSERT INTO g_sync_data
						SET table_name = '{$table_name}',
						pk_id_local = OLD.id,
						pk_id_live = 0,
						ACTION = \"delete\",
						is_sync = \"No\",
						date_created = NOW( ) ,
						date_modified = NOW( ) ;
					END IF;
				END
			";
			$result = $mysqli->query($sql);				
		}	

		$sql = "				
			CREATE DEFINER=`root`@`localhost` TRIGGER `triggerUpdateFpLogsOnInsert` AFTER INSERT ON `g_employee_attendance` 
			FOR EACH ROW BEGIN
				UPDATE g_fp_attendance_log
				SET is_transferred = 1
				WHERE `date` = NEW.date_attendance AND user_id = NEW.employee_id AND is_transferred = 0;
			END
		";
		$result = $mysqli->query($sql);	

		$sql = "				
			CREATE DEFINER=`root`@`localhost` TRIGGER `triggerUpdateFpLogsOnUpdate` AFTER UPDATE ON `g_employee_attendance` 
			FOR EACH ROW BEGIN
				UPDATE g_fp_attendance_log
				SET is_transferred = 1
				WHERE date = NEW.date_attendance AND user_id = NEW.employee_id AND is_transferred = 0;
			END
		";
		$result = $mysqli->query($sql);	

		$return['message']    = 'SQL Triggers was successfully created';
		$return['is_created'] = true;

		return $return;
	}

	public function dropEmployeeOnlineSyncDataSQLTriggers(){
		$tables = array("g_allowed_ip","g_employee","g_leave","g_requests","g_request_approvers","g_request_approvers_level","g_request_approvers_requestors","g_roles","g_role_actions","g_employee","g_employee_contact_details","g_employee_leave_available","g_employee_leave_request","g_employee_overtime","g_employee_overtime_request","g_employee_payslip","g_employee_user","g_employee_official_business_request");
		
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 					
		foreach( $tables as $table_name ){
			$sql = "DROP TRIGGER IF EXISTS `trig_{$table_name}_ai`;";			
			$result = $mysqli->query($sql);				

			$sql = "DROP TRIGGER IF EXISTS `trig_{$table_name}_au`;";												
			$result = $mysqli->query($sql);		

			$sql = "DROP TRIGGER IF EXISTS `trig_{$table_name}_ad`;";	
			$result = $mysqli->query($sql);				
		}	

		$return['message']    = 'SQL Triggers was successfully removed';
		$return['is_created'] = true;

		return $return;
	}

	public function isAddOnActivated($addon = ''){
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].MAIN_FOLDER. 'files/xml/settings/add_ons.xml';
		$return = false;
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);
			$xml    = new Xml;
			$arrXml = $xml->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//addons');
			$is_activated = $obj[0]->{$addon};				
		}

		$ad = new G_Sprint_Add_Ons();
		if( $is_activated == 'true' ){					
			$return = true;
		}

		return $return;
	}
}
?>