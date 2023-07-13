<?php

class Sprint_Tables {	

	public $table_name;
	public $table_indexes;

	const INIT_FILE = 'ini_file';
	
	public function __construct($value) {
		$this->table_name = $value;
	}
	
	public function setTableName($value) {
		$this->table_name = $value;
	}	

	public function setIndexes( $array_indexes = array() ){
		$this->table_indexes = $array_indexes;
	}

	/*
		Usage:
		$table_name = EMPLOYEE;
		$sprint = new Sprint_Tables();
		$sprint->setTableName($table_name);
		$sprint->dropTableIndexes();
		$sprint->rebuildTableIndexes(); //Will restore indexes drop in dropTableIndexes
	*/

	public function dropTableIndexes(){
		if( !empty($this->table_name) ){
			$table_name = $this->table_name;
			$mysqli = new Mysqli_Connect('local'); 		
			$sql    = "SHOW INDEX FROM `{$table_name}`";
			$result = $mysqli->query($sql,true);
			
			foreach($result as $row){
				$index_name      = $row['Key_name'];

				if( trim(strtoupper($index_name)) != 'PRIMARY' ){
					$array_indexes[$row['Key_name']][] = "`" . $row['Column_name'] . "`";
					$sql = "ALTER TABLE  `{$table_name}` DROP INDEX  `{$index_name}`;"; //Drop all indexes
					$mysqli->query($sql);
				}

			}
			$this->table_indexes = $array_indexes;			
		}	

	}

	public function rebuildTableIndexes(){
		if( !empty($this->table_name) && !empty($this->table_indexes) ){			
			$indexes    = $this->table_indexes;
			$table_name = $this->table_name;	
			$mysqli = new Mysqli_Connect('local'); 				
			foreach($indexes as $key => $columns){
				$index_name  = $key;
				$column_name = implode(",", $columns);
				$sql = "ALTER TABLE `{$table_name}` ADD INDEX `{$index_name}` ({$column_name})";			
				$mysqli->query($sql);				
			}
		}
	}
	
	public function truncateSelectedTable()
	{
		$sql = 'TRUNCATE TABLE ' . $this->table_name;		
		Model::runSql($sql);
	}

	public function factoryResetByAppVersion(){				
		if(self::isFactoryResetEnabled() == 'true') {
			$v    = new G_Sprint_Version();
			$data = $v->getAppVersion();

			$version_part = explode("/", $data);		
			$app_version  = trim($version_part[0]);
			if( $app_version != "" ){
				
				/*$addon = 'employee_online_portal';
				$ad    = new G_Sprint_Add_Ons();
				$is_activated = $ad->isAddOnActivated($addon);
				$ad->dropEmployeeOnlineSyncDataSQLTriggers();*/

				self::truncateAllTables();				
				self::loadDefaultValuesByVersion($app_version);
				self::updateTablesByAppVersion($app_version);
				self::disableFactoryReset();	
				self::createFactoryResetFile();		

				$addon = 'employee_online_portal';
				$ad    = new G_Sprint_Add_Ons();
				$data  = $ad->deactivateAddOn($addon);	

				if( $is_activated ){	
					$ad->createSQLTriggers();
				}

				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function createFactoryResetFile()
	{
		//Create file that will trigger first login		
		$filename = TEMP_USER_FOLDER . self::INIT_FILE;
		$content  = '';			
		
		$io = new IO_Reader();
		$io->setFileName($filename);
		$io->setContent($content);
		$io->writeToTextFile(); //Create user info file
	}

	public function factoryReset() 
	{
		if(self::isFactoryResetEnabled() == 'true') {
			self::truncateAllTables();
			self::loadDefaultValues();
			self::disableFactoryReset();
			return true;
		}else{
			return false;
		}
	}

	public function isFactoryResetEnabled() {
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/factory_reset.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2   = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//Settings');
			return $obj[0]->enable;				
		}else{
			return 'false';
		}
	}

	private function createSettingsEmpoyeeBenefitsTable(){
		$table_name      	    = G_SETTINGS_EMPLOYEE_BENEFITS;
		$is_table_exists        = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$total_existing_records = Sprint_Tables_Helper::sqlCountTotalRecords($table_name);
		$create_default_values  = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists && $total_existing_records > 0 ){			
			$create_default_values = false;
			$return['message']     = 'Table already updated!';
		}

		$sql = "
			CREATE TABLE IF NOT EXISTS `g_settings_employee_benefits` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `code` varchar(50) CHARACTER SET latin1 NOT NULL,
			  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
			  `description` varchar(200) CHARACTER SET latin1 NOT NULL,
			  `amount` double NOT NULL,
			  `is_auto_load` varchar(5) CHARACTER SET latin1 NOT NULL,
			  `is_taxable` varchar(5) CHARACTER SET latin1 NOT NULL,
			  `is_archive` varchar(5) CHARACTER SET latin1 NOT NULL,
			  `date_created` varchar(80) CHARACTER SET latin1 NOT NULL,
			  `date_last_modified` varchar(80) CHARACTER SET latin1 NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`),
			  KEY `code` (`code`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		";
		Model::runSql($sql);
		
		//If newly created table load default values
		if( $create_default_values ){
			self::loadDefaultSettingEmployeeBenefits($c);
		}

		return $return;
	}

	private function createExcludedEmployeeDeduction(){
		$table_name      	  = EXCLUDED_EMPLOYEE_DEDUCTION;
		$is_table_exists      = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$return['is_created'] = true;

		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_excluded_employee_deduction` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `employee_id` bigint(20) NOT NULL,
				  `payroll_period_id` int(11) NOT NULL,
				  `new_payroll_period_id` int(11) NOT NULL,
				  `variable_name` varchar(90) NOT NULL,
				  `amount` float NOT NULL,
				  `action` varchar(50) NOT NULL,
				  `date_created` varchar(50) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$return['message']     = "Table {$table_name} was successfully created!";
		}else{
			$return['message']     = "Table {$table_name} already exists!";
		}

		//Update g_employee_deductions table
		$table_name      	   = G_EMPLOYEE_DEDUCTIONS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "
				ALTER TABLE `g_employee_deductions` ADD `is_moved_deduction` INT NOT NULL COMMENT '0 = No, 1 = Yes'
			";
			Model::runSql($sql);
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_deductions` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` int(11) NOT NULL,
				  `employee_id` varchar(240) CHARACTER SET latin1 NOT NULL,
				  `title` varchar(200) CHARACTER SET latin1 NOT NULL,
				  `remarks` text CHARACTER SET latin1 NOT NULL,
				  `amount` double NOT NULL,
				  `payroll_period_id` int(11) NOT NULL,
				  `apply_to_all_employee` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `status` varchar(20) CHARACTER SET latin1 NOT NULL,
				  `is_taxable` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `date_created` varchar(30) CHARACTER SET latin1 NOT NULL,
				  `is_moved_deduction` int(11) NOT NULL COMMENT '0 = No, 1 = Yes',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}

		return $return;
	}

	private function createSprintManualDb(){	
		$return['is_created'] = true;
		$return['message']    = "Database successfully created";

		//Create database if not exists	
		$sql = "
			CREATE DATABASE IF NOT EXISTS `sprinthr_wiki` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
		";
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 			
		$result = $mysqli->query($sql);	

		//Import SQL File		
		$import_file   = $_SERVER['DOCUMENT_ROOT'] . BASE_FOLDER . "manual/";	
		$sql_file      = $import_file . "sprinthr_wiki.sql";
		$wiki_database = 'sprinthr_wiki';

		$mdb = new G_Database();
		$mdb->setUserName(DB_USERNAME);
		$mdb->setHostName(DB_HOST);
		$mdb->setDbName($wiki_database);
		$mdb->setPassword(DB_PASSWORD);		
		$is_success = $mdb->importWikiDatabase($sql_file);

		if( $is_success ){
			$return['is_created'] = false;
			$return['message']    = 'Cannot create database';
		}

		return $return;
	}

	private function updateEmployeePayslipTableStructure(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_PAYSLIP;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "
				ALTER TABLE `{$table_name}` ADD `overtime` DOUBLE NOT NULL,
					ADD `number_of_declared_dependents` INT NOT NULL,
					ADD `taxable_benefits` DOUBLE( 15, 2 ) NOT NULL,
					ADD `non_taxable_benefits` DOUBLE( 15, 2 ) NOT NULL,
					ADD `tardiness_amount` DOUBLE( 15, 2 ) NOT NULL;
			";
			Model::runSql($sql);
			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_payslip` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employee_id` int(11) NOT NULL,
				  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `basic_pay` double(15,2) NOT NULL,
				  `declared_dependents` int(11) NOT NULL DEFAULT '0',
				  `gross_pay` double(15,2) NOT NULL,
				  `total_earnings` float NOT NULL,
				  `tardiness_amount` double(15,2) NOT NULL DEFAULT '0.00',
				  `total_deductions` float NOT NULL,
				  `net_pay` double(15,2) NOT NULL,
				  `overtime` double(15,2) NOT NULL,
				  `taxable_benefits` double(15,2) unsigned zerofill NOT NULL,
				  `taxable` double(15,2) unsigned zerofill NOT NULL,
				  `non_taxable_benefits` double(15,2) NOT NULL,
				  `non_taxable` double(15,2) NOT NULL,
				  `withheld_tax` double(15,2) NOT NULL,
				  `month_13th` double(15,2) NOT NULL,
				  `sss` double(15,2) NOT NULL,
				  `pagibig` double(15,2) NOT NULL,
				  `philhealth` double(15,2) NOT NULL,
				  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
				  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
				  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
				  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
				  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateDeductionBreakDown(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_SETTINGS_DEDUCTION_BREAKDOWN;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		
		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` ADD `is_taxable` VARCHAR(3) NOT NULL DEFAULT 'No' AFTER `is_active`";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` ADD `salary_credit` INT NOT NULL DEFAULT 0 COMMENT '0 = basic pay / 1 = gross pay' AFTER `is_taxable`";
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_settings_deduction_breakdown` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(30) CHARACTER SET latin1 NOT NULL,
				  `breakdown` varchar(50) CHARACTER SET latin1 NOT NULL,
				  `is_active` varchar(10) CHARACTER SET latin1 NOT NULL,
				  `is_taxable` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
				  `salary_credit` int(2) NOT NULL DEFAULT '0' COMMENT '0 = basic pay / 1 = gross pay',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
			";
			Model::runSql($sql);
			self::loadDefaultSettingsDeductionBreakdown();
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);

		return $return;
	}

	private function addRegularNightShiftRate(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = ATTENDANCE_RATE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		
		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` ADD `regular_overtime_nightshift_differential` FLOAT NOT NULL DEFAULT 0 AFTER `holiday_legal_restday_overtime`";
			Model::runSql($sql);

			$sql = "
				UPDATE `{$table_name}` 
				SET regular_overtime_nightshift_differential = 137
			";			
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_attendance_rate` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `nightshift_rate` float NOT NULL COMMENT '%',
				  `regular_overtime` float NOT NULL COMMENT '% (example: 125%)',
				  `nightshift_overtime` float NOT NULL COMMENT '%',
				  `restday` float NOT NULL COMMENT '%',
				  `restday_overtime` float NOT NULL COMMENT '%',
				  `holiday_special` float NOT NULL COMMENT '%',
				  `holiday_special_overtime` float NOT NULL COMMENT '%',
				  `holiday_legal` float NOT NULL COMMENT '%',
				  `holiday_legal_overtime` float NOT NULL COMMENT '%',
				  `holiday_special_restday` float NOT NULL COMMENT '%',
				  `holiday_special_restday_overtime` float NOT NULL COMMENT '%',
				  `holiday_legal_restday` float NOT NULL COMMENT '%',
				  `holiday_legal_restday_overtime` float NOT NULL COMMENT '%',
				  `regular_overtime_nightshift_differential` float NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
			";
			Model::runSql($sql);

			$sql = "
				INSERT INTO `g_attendance_rate` (`id`, `nightshift_rate`, `regular_overtime`, `nightshift_overtime`, `restday`, `restday_overtime`, `holiday_special`, `holiday_special_overtime`, `holiday_legal`, `holiday_legal_overtime`, `holiday_special_restday`, `holiday_special_restday_overtime`, `holiday_legal_restday`, `holiday_legal_restday_overtime`, `regular_overtime_nightshift_differential`) VALUES
(1, 110, 125, 125, 130, 130, 130, 130, 200, 130, 150, 130, 260, 130, 137);
			";
			Model::runSql($sql);
			
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);

		return $return;
	}

	private function updateEmployeeTableWithWorkingDays(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = EMPLOYEE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		
		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` ADD `year_working_days` INT NOT NULL AFTER `is_confidential`";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` ADD `week_working_days` VARCHAR(110) NOT NULL AFTER `year_working_days` ";
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee` (
				  `id` bigint(30) NOT NULL AUTO_INCREMENT,
				  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_device_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `company_structure_id` int(11) NOT NULL,
				  `department_company_structure_id` int(11) NOT NULL DEFAULT '2',
				  `employment_status_id` int(11) NOT NULL,
				  `employee_status_id` int(11) NOT NULL,
				  `eeo_job_category_id` int(11) NOT NULL,
				  `section_id` int(11) NOT NULL,
				  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `middlename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `extension_name` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `birthdate` date NOT NULL,
				  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  `number_dependent` int(11) NOT NULL,
				  `sss_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `tin_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `hired_date` date NOT NULL,
				  `leave_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'resignation, terminated, endo dates',
				  `resignation_date` date NOT NULL,
				  `endo_date` date NOT NULL,
				  `terminated_date` date NOT NULL,
				  `e_is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
				  `is_tax_exempted` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `is_confidential` int(1) NOT NULL COMMENT '0 = No, 1 = Yes',
				  `year_working_days` int(11) NOT NULL,
				  `week_working_days` varchar(110) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id-fname-lname-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname-lastname` (`firstname`,`lastname`,`id`),
				  KEY `id-first-last-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname` (`firstname`),
				  KEY `lastname` (`lastname`),
				  KEY `employee_code` (`employee_code`),
				  KEY `id` (`id`,`hash`,`firstname`,`lastname`,`employee_code`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `nationality` (`nationality`),
				  KEY `id-employee_code` (`id`,`employee_code`),
				  KEY `employee_status_id` (`employee_status_id`),
				  KEY `hired_date-leave_date` (`hired_date`,`leave_date`),
				  KEY `hired_date` (`hired_date`),
				  KEY `leave_date` (`leave_date`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);

		return $return;
	}

	private function updatePagibigTable(){
		$table_name = G_PAGIBIG;
		$sql = "
			TRUNCATE TABLE `{$table_name}`;
		";
		Model::runSql($sql);
		$c = G_Company_Structure_Finder::findById(1);	
		self::loadDefaultPagibigTable($c);
		$return['message'] = "Table {$table_name} was successfully updated!";

		return $return;
	}

	private function updateContributionsTable(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = SSS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `monthly_salary_credit` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `from_salary` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `to_salary` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `employee_share` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `company_share` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `company_ec` float(10,2) NOT NULL";
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			self::loadDefaultSssTable();
			$message[] = "Table {$table_name} was successfully created!";
		}

		$table_name      	   = PHILHEALTH;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `salary_base` float(10,2) NOT NULL";
			Model::runSql($sql);
			
			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `from_salary` decimal(15,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `to_salary` decimal(15,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `monthly_contribution` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `employee_share` float(10,2) NOT NULL";
			Model::runSql($sql);

			$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `company_share` float(10,2) NOT NULL";
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			self::loadDefaultPhilhealthTable();
			$message[] = "Table {$table_name} was successfully created!";
		}



		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateEmployeeConfidentialNoneConfidential(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = EMPLOYEE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` ADD `section_id` INT NOT NULL AFTER `eeo_job_category_id`";
			Model::runSql($sql);

			$sql = "ALTER TABLE {$table_name} ADD `is_confidential` INT( 1 ) NOT NULL COMMENT '0 = No, 1 = Yes'";
			Model::runSql($sql);

			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee` (
				  `id` bigint(30) NOT NULL AUTO_INCREMENT,
				  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_device_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `company_structure_id` int(11) NOT NULL,
				  `department_company_structure_id` int(11) NOT NULL DEFAULT '2',
				  `employment_status_id` int(11) NOT NULL,
				  `employee_status_id` int(11) NOT NULL,
				  `eeo_job_category_id` int(11) NOT NULL,
				  `section_id` int(11) NOT NULL,
				  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `middlename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `extension_name` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `birthdate` date NOT NULL,
				  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  `number_dependent` int(11) NOT NULL,
				  `sss_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `tin_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `hired_date` date NOT NULL,
				  `leave_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'resignation, terminated, endo dates',
				  `resignation_date` date NOT NULL,
				  `endo_date` date NOT NULL,
				  `terminated_date` date NOT NULL,
				  `e_is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
				  `is_tax_exempted` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `is_confidential` int(1) NOT NULL COMMENT '0 = No, 1 = Yes',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id-fname-lname-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname-lastname` (`firstname`,`lastname`,`id`),
				  KEY `id-first-last-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname` (`firstname`),
				  KEY `lastname` (`lastname`),
				  KEY `employee_code` (`employee_code`),
				  KEY `id` (`id`,`hash`,`firstname`,`lastname`,`employee_code`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `nationality` (`nationality`),
				  KEY `id-employee_code` (`id`,`employee_code`),
				  KEY `employee_status_id` (`employee_status_id`),
				  KEY `hired_date-leave_date` (`hired_date`,`leave_date`),
				  KEY `hired_date` (`hired_date`),
				  KEY `leave_date` (`leave_date`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";

			self::loadDefaultEmployeeWithUserAccount();
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateOvertimeRequestTableStructure(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_OVERTIME;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$field_name      = 'date_created';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE {$table_name} ADD `date_created` VARCHAR( 50 ) NOT NULL";
				Model::runSql($sql);
				$message[]    = "Table {$table_name} was successfully updated!";
			}else{
				$message[]  = "Table {$table_name} already updated!";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_overtime` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employee_id` int(11) NOT NULL,
				  `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `reason` text COLLATE utf8_unicode_ci NOT NULL,
				  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending, Approved, Disapproved',
				  `is_archived` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
				  `date_created` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `employee_id-date` (`employee_id`,`date`),
				  KEY `date` (`date`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateOfficialBusinessTableStructure(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$sql = "ALTER TABLE {$table_name} ALTER COLUMN `date_created` VARCHAR( 50 ) NOT NULL ";
			Model::runSql($sql);
			$message[]    = "Table {$table_name} was successfully updated!";

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_official_business_request` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` int(11) NOT NULL,
				  `employee_id` int(11) NOT NULL,
				  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `comments` text COLLATE utf8_unicode_ci NOT NULL,
				  `is_approved` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
				  `created_by` int(11) NOT NULL,
				  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `employee_id-date` (`employee_id`,`date_start`,`date_end`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateFpLogsStructure(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_ATTENDANCE_LOG;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$field_name      = 'is_transferred';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `is_transferred` int(1) unsigned zerofill NOT NULL COMMENT '1 = transferred / 0 = new data'";
				Model::runSql($sql);
				$message[]    = "Table {$table_name} was successfully updated!";
			}else{
				$message[]  = "Table {$table_name} already updated!";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `employee_code` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `employee_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `time` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
				  `is_transferred` int(1) unsigned zerofill NOT NULL COMMENT '1 = transferred / 0 = new data',
				  `sync` int(11) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`),
				  KEY `code,date,type` (`employee_code`,`date`,`type`),
				  KEY `date` (`date`),
				  KEY `employee_code` (`employee_code`),
				  KEY `code-date-time` (`employee_code`,`date`,`time`),
				  KEY `date-time` (`date`,`time`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function updateFpTableStructure(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_ATTENDANCE_LOG;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$field_name      = 'sync';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `sync` INT NOT NULL DEFAULT '1' COLLATE utf8_unicode_ci";
				Model::runSql($sql);
				$message[]    = "Table {$table_name} was successfully updated!";
			}else{
				$message[]  = "Table {$table_name} already updated!";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `employee_code` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `employee_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `time` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
				  `sync` int(11) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`),
				  KEY `code,date,type` (`employee_code`,`date`,`type`),
				  KEY `date` (`date`),
				  KEY `employee_code` (`employee_code`),
				  KEY `code-date-time` (`employee_code`,`date`,`time`),
				  KEY `date-time` (`date`,`time`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$table_name      	   = G_ATTENDANCE_SUMMARY;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$field_name      = 'sync';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `sync` INT NOT NULL DEFAULT '1' COLLATE utf8_unicode_ci";
				Model::runSql($sql);
				$message[]    = "Table {$table_name} was successfully updated!";
			}else{
				$message[]  = "Table {$table_name} already updated!";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_attendance_summary` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employee_id` int(11) NOT NULL,
				  `employee_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
				  `actual_date_in` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
				  `actual_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `actual_date_out` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `actual_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `actual_total_hours_worked` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'compute by biometrics',
				  `done` int(11) NOT NULL COMMENT 'for biometrics',
				  `sync` int(11) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		$table_name      	   = G_FINGERPRINT;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		if( $is_table_exists ){			
			$field_name      = 'sync';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `sync` INT NOT NULL DEFAULT '1' COLLATE utf8_unicode_ci";
				Model::runSql($sql);
				$message[]    = "Table {$table_name} was successfully updated!";
			}else{
				$message[]  = "Table {$table_name} already updated!";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_fingerprint` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `employee_code` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT 'employee_code example 100203-023',
				  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
				  `template` longblob NOT NULL,
				  `finger` varchar(64) CHARACTER SET latin1 NOT NULL,
				  `integer_representation` int(11) NOT NULL,
				  `sync` int(11) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}

		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createNetTaxableTable(){
		$return['message']    = '';
		$return['is_created'] = true;
		
		$table_name      	   = NET_TAXABLE_TABLE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "								
				CREATE TABLE IF NOT EXISTS `g_net_taxable_table` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `over` float NOT NULL,
				  `not_over` float NOT NULL,
				  `amount` float NOT NULL,
				  `rate_percentage` float NOT NULL,
				  `excess_over` float NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";			

			Model::runSql($sql);

			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}

		self::loadDefaultNetTaxableTable();
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createBreaktimeScheduleTable(){
		$return['message']    = '';
		$return['is_created'] = true;
		
		//Breaktime Header
		$table_name      	    = BREAK_TIME_SCHEDULE_HEADER;
		$is_table_header_exists = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_header_exists ){						
			$sql = "								
				CREATE TABLE IF NOT EXISTS `g_break_time_schedule_header` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `schedule_in` time NOT NULL,
				  `schedule_out` time NOT NULL,
				  `break_time_schedules` text NOT NULL,
				  `applied_to` text NOT NULL,
				  `date_start` varchar(80) NOT NULL,
				  `date_created` varchar(80) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";			
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}

		//Breaktime Details
		$table_name      	     = BREAK_TIME_SCHEDULE_DETAILS;
		$is_table_details_exists = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_details_exists ){						
			$sql = "								
				CREATE TABLE IF NOT EXISTS `g_break_time_schedule_details` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `header_id` int(11) NOT NULL,
				  `obj_id` int(11) NOT NULL,
				  `obj_type` varchar(2) NOT NULL COMMENT 'a = all, e = employee, d = department',
				  `break_in` time NOT NULL,
				  `break_out` time NOT NULL,
				  `to_deduct` int(2) NOT NULL DEFAULT '0' COMMENT '0 = no / 1 = yes',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";			
			Model::runSql($sql);

			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}

		if( !$is_table_details_exists && !$is_table_header_exists){
			self::loadDefaultBreaktimeSchedule();
		}		
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createOTAllowanceTable(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = G_OVERTIME_ALLOWANCE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "												
				CREATE TABLE IF NOT EXISTS `g_overtime_allowance` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `object_id` bigint(20) NOT NULL,
				  `object_type` varchar(5) NOT NULL,
				  `ot_allowance` float NOT NULL,
				  `multiplier` int(11) NOT NULL,
				  `max_ot_allowance` float NOT NULL,
				  `date_start` varchar(50) NOT NULL,
				  `description` text NOT NULL,
				  `date_created` varchar(50) NOT NULL,
				  PRIMARY KEY (`id`)
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

	private function createGroupRestDay(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = GROUP_RESTDAY;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "												
				CREATE TABLE IF NOT EXISTS `g_group_restday` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `group_id` int(11) NOT NULL COMMENT 'department / section / group id',
				  `date` varchar(10) NOT NULL,
				  PRIMARY KEY (`id`)
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

	private function createSprintVariablesDefaultWorkingDays(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = SPRINT_VARIABLES;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "												
				CREATE TABLE IF NOT EXISTS `g_sprint_variables` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `variable_name` varchar(100) DEFAULT NULL,
				  `value` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";			
			Model::runSql($sql);
		}

		$message[] = "Default Variables was successfully created";	

		$sv = new G_Sprint_Variables();
		$sv->loadDefaultWorkingDays();
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createSprintVariablesCETASEA(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = SPRINT_VARIABLES;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "												
				CREATE TABLE IF NOT EXISTS `g_sprint_variables` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `variable_name` varchar(100) DEFAULT NULL,
				  `value` varchar(255) DEFAULT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";			

			Model::runSql($sql);
		}

		$message[] = "Default Variables was successfully created";

		$sv = new G_Sprint_Variables();
		$sv->loadDefaultCetaAndSea();
		$sv->loadDefaultMinimumRate();
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createIPAllowedTable(){
		$return['message']    = '';
		$return['is_created'] = true;

		$table_name      	   = ALLOWED_IP;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_allowed_ip` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `ip_address` varchar(50) NOT NULL,
				  `employee_id` bigint(20) NOT NULL,
				  `date_modified` varchar(50) NOT NULL,
				  `date_created` varchar(50) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
			";			
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
			
		}else{
			$message[] = "Table {$table_name} already exists!";
		}
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createAttendanceTriggers(){		
		//Add triggers
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 			
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
		
		$return['message']    = 'Triggers was successfully created';
		$return['is_created'] = true;

		
		return $return;
	}

	private function dropAttendanceTriggers(){		
		//Drop triggers
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 	
		$sql = "DROP TRIGGER IF EXISTS `triggerUpdateFpLogsOnInsert`;";	
		$result = $mysqli->query($sql);	

		$sql = "DROP TRIGGER IF EXISTS `triggerUpdateFpLogsOnUpdate`;";	
		$result = $mysqli->query($sql);	
	}

	private function createRequestApproversTables(){
		$return['message']    = '';
		$return['is_created'] = true;

		//Request Approvers
		$table_name      	   = REQUEST_APPROVERS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);				
		if( !$is_table_exists ){						
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_request_approvers` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `title` varchar(100) DEFAULT NULL,
				  `approvers_name` text,
				  `requestors_name` text,
				  `date_created` varchar(80) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `title` (`title`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;
			";			
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}

		//Request Approvers Level
		$table_name      	   = REQUEST_APPROVERS_LEVEL;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);		

		if( !$is_table_exists ){			
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_request_approvers_level` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `request_approvers_id` bigint(20) DEFAULT NULL,
				  `employee_id` bigint(20) DEFAULT NULL,
				  `employee_name` varchar(160) DEFAULT NULL,
				  `level` smallint(6) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `employee_name` (`employee_name`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

			";
			Model::runSql($sql);
			$message[] = "Table {$table_name} was successfully created!";
		}else{
			$message[] = "Table {$table_name} already exists!";
		}

		//Request Approvers Requestors
		$table_name      	   = REQUEST_APPROVERS_REQUESTORS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);		

		if( !$is_table_exists ){			
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_request_approvers_requestors` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `request_approvers_id` bigint(20) DEFAULT NULL,
				  `employee_department_group_id` bigint(20) DEFAULT NULL,
				  `employee_department_group` varchar(5) DEFAULT NULL,
				  `description` varchar(160) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `request_approvers_id` (`request_approvers_id`),
				  KEY `employee_department_group` (`employee_department_group`),
				  KEY `description` (`description`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;
			";

			Model::runSql($sql);
			$message[] = "Table {$table_name} already exists!";
		}

		//Requests
		$table_name      	   = REQUESTS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);		

		if( !$is_table_exists ){			
			$sql = "				
				CREATE TABLE IF NOT EXISTS `g_requests` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `requestor_employee_id` bigint(20) DEFAULT NULL,
				  `request_id` bigint(20) DEFAULT NULL,
				  `request_type` varchar(10) DEFAULT NULL,
				  `approver_employee_id` bigint(20) DEFAULT NULL,
				  `approver_name` varchar(180) DEFAULT NULL,
				  `status` varchar(40) DEFAULT NULL,
				  `is_lock` varchar(10) DEFAULT NULL,
				  `remarks` text,
				  `action_date` varchar(180) DEFAULT NULL,
				  PRIMARY KEY (`id`),
				  KEY `requestor_employee_id` (`requestor_employee_id`,`request_id`,`request_type`,`approver_employee_id`,`status`),
				  KEY `approver_name` (`approver_name`),
				  KEY `request_type` (`request_type`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;
			";
			
			Model::runSql($sql);
			$message[] = "Table {$table_name} already exists!";
		}
		
		$return['message'] = implode("<br />", $message);
		
		return $return;
	}

	private function createEmployeeTableWithTaxExempted(){
		$table_name      	    = EMPLOYEE;
		$field_name             = 'is_tax_exempted';
		$is_table_exists        = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$is_table_field_exits   = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
		$total_existing_records = Sprint_Tables_Helper::sqlCountTotalRecords($table_name);
		$create_default_values  = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists && $total_existing_records > 0 ){			
			$create_default_values = false;
			$return['message']     = 'Table already updated!';
		}

		if( $is_table_exists && !$is_table_field_exits ){
			$sql = "ALTER TABLE `{$table_name}` ADD `is_tax_exempted` VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL";
			Model::runSql($sql);
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee` (
				  `id` bigint(30) NOT NULL AUTO_INCREMENT,
				  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_device_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
				  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `company_structure_id` int(11) NOT NULL,
				  `department_company_structure_id` int(11) NOT NULL DEFAULT '2',
				  `employment_status_id` int(11) NOT NULL,
				  `employee_status_id` int(11) NOT NULL,
				  `eeo_job_category_id` int(11) NOT NULL,
				  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `middlename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `extension_name` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
				  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				  `birthdate` date NOT NULL,
				  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  `number_dependent` int(11) NOT NULL,
				  `sss_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `tin_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
				  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
				  `is_tax_exempted` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `hired_date` date NOT NULL,
				  `leave_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'resignation, terminated, endo dates',
				  `resignation_date` date NOT NULL,
				  `endo_date` date NOT NULL,
				  `terminated_date` date NOT NULL,
				  `e_is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `id-fname-lname-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname-lastname` (`firstname`,`lastname`,`id`),
				  KEY `id-first-last-code` (`id`,`firstname`,`lastname`,`employee_code`),
				  KEY `firstname` (`firstname`),
				  KEY `lastname` (`lastname`),
				  KEY `employee_code` (`employee_code`),
				  KEY `id` (`id`,`hash`,`firstname`,`lastname`,`employee_code`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `nationality` (`nationality`),
				  KEY `id-employee_code` (`id`,`employee_code`),
				  KEY `employee_status_id` (`employee_status_id`),
				  KEY `hired_date-leave_date` (`hired_date`,`leave_date`),
				  KEY `hired_date` (`hired_date`),
				  KEY `leave_date` (`leave_date`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
			";
			Model::runSql($sql);
		}	
		
		$sql = "UPDATE `{$table_name}` SET `is_tax_exempted` ='No' WHERE `is_tax_exempted` = ''";
		Model::runSql($sql);

		//If newly created table load default values
		if( $create_default_values ){
			$c = G_Company_Structure_Finder::findById(1);
			if( $c ){
				self::loadDefaultSettingsEmployee($c);
			}
		}

		return $return;
	}

	private function createEmpoyeeBenefitsMainTable(){
		$table_name      	   = G_EMPLOYEE_BENEFITS_MAIN;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists ){
			$create_default_values = false;
			$return['message']     = 'Table already updated!';
		}

		$sql = "
			CREATE TABLE IF NOT EXISTS `g_employee_benefits_main` (
			  `id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `company_structure_id` smallint(6) NOT NULL,
			  `employee_department_id` bigint(20) NOT NULL,
			  `benefit_id` int(11) NOT NULL,
			  `applied_to` varchar(50) CHARACTER SET latin1 NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `company_structure_id` (`company_structure_id`),
			  KEY `id` (`id`),
			  KEY `employee_department_id` (`employee_department_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		";
		Model::runSql($sql);
		return $return;
	}

	private function createPayrollVariablesTable(){
		$table_name      	   = PAYROLL_VARIABLES;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$total_existing_records = Sprint_Tables_Helper::sqlCountTotalRecords($table_name);
		$create_default_values = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists && $total_existing_records > 0 ){	
			$create_default_values = false;
			$return['message']     = 'Table already updated!';
		}

		$sql = "
			CREATE TABLE IF NOT EXISTS `g_payroll_variables` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `number_of_days` double NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `id` (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		";
		Model::runSql($sql);

		//If newly created table load default values
		if( $create_default_values ){
			self::loadDefaultPayrollSettings();
		}

		return $return;
	}

	private function createEmployeeContributionsTable(){
		$table_name      	   = G_EMPLOYEE_CONTRIBUTION;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists ){			
			$field_name      = 'to_deduct';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `to_deduct` VARCHAR(180) COLLATE utf8_unicode_ci NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = 'Table already updated!';
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_contribution` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employee_id` int(11) NOT NULL,
				  `sss_ee` float NOT NULL,
				  `pagibig_ee` float NOT NULL,
				  `philhealth_ee` float NOT NULL,
				  `sss_er` float NOT NULL,
				  `pagibig_er` float NOT NULL,
				  `philhealth_er` float NOT NULL,
				  `to_deduct` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
		}

		$employees = G_Employee_Finder::findAll();
		foreach( $employees as $e ){
			$esalary = G_Employee_Basic_Salary_History_Finder::findCurrentSalary($e); 
			if( $esalary ){
				$salary = $esalary->getBasicSalary();
			}else{
				$salary = 0;
			}

			$contri = G_Employee_Contribution_Finder::findByEmployeeId($e->getId());
			if( empty($contri) ){
				$e->addContribution($salary);
			}
		}
		
		return $return;
	}

	private function earningsDeductionsA(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_EARNINGS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'department_section_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `department_section_id` VARCHAR( 240 ) NOT NULL AFTER `employee_id` ";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'employment_status_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `employment_status_id` VARCHAR( 240 ) NOT NULL AFTER `department_section_id` ";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_earnings` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` int(11) NOT NULL,
				  `employee_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
				  `department_section_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
				  `employment_status_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
				  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
				  `amount` double NOT NULL,
				  `payroll_period_id` int(11) NOT NULL,
				  `apply_to_all_employee` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  `is_taxable` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `date_created` datetime NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `employee_id` (`employee_id`),
				  KEY `payroll_period_id` (`payroll_period_id`),
				  KEY `id` (`id`,`company_structure_id`,`employee_id`,`payroll_period_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}

		$table_name      	   = G_EMPLOYEE_DEDUCTIONS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'department_section_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `department_section_id` VARCHAR( 240 ) NOT NULL AFTER `employee_id` ";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'employment_status_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `employment_status_id` VARCHAR( 240 ) NOT NULL AFTER `department_section_id` ";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}
		}else{

		}
		
		return $return;
	}

	private function defaultSettingsLeaveGeneral(){
		$sql = "INSERT INTO `g_settings_leave_general` (`id`, `convert_leave_criteria`, `leave_id`) VALUES(1, 2, 3);";
		Model::runSql($sql);
	}

	private function employeeLeaveCreditAutoIncrement(){
		$return['is_created'] = true;

		$table_name      	   = 'g_settings_leave_general';
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_settings_leave_general` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `convert_leave_criteria` int(2) NOT NULL,
				  `leave_id` int(2) NOT NULL COMMENT 'from ''g_leave'' table',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);

			$sql = "INSERT INTO `g_settings_leave_general` (`id`, `convert_leave_criteria`, `leave_id`) VALUES(1, 2, 3);";
			Model::runSql($sql);

			$return['message']    .= " Table Name : {$table_name} was successfully created ";
		}else{
			$return['message']    .= " Table Name : {$table_name} already exists ";
		}

		$table_name      	   = 'g_settings_leave_credit';
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_settings_leave_credit` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employment_years` varchar(5) NOT NULL,
				  `default_credit` int(11) NOT NULL,
				  `leave_id` int(11) NOT NULL COMMENT 'from ''Leave Type'' table',
				  `employment_status_id` int(11) NOT NULL COMMENT 'from ''Employment Status'' table',
				  `is_archived` varchar(5) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
			$return['message']    .= " Table Name : {$table_name} was successfully created ";
		}else{
			$return['message']    .= " Table Name : {$table_name} already exists ";
		}
		return $return;
	}

	private function anvizBiometricsTables(){
		$return['is_created'] = true;

		$table_name      	   = G_ATTENDANCE_LOG;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$sql = "ALTER TABLE `{$table_name}` ADD `employee_device_id` int(11) NOT NULL COMMENT 'for anviz device'";
			Model::runSql($sql);
			$return['message']    .= " Table Name : {$table_name} was successfully updated ";
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `employee_code` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `employee_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `time` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
				  `sync` int(11) NOT NULL DEFAULT '1',
				  `is_transferred` int(1) unsigned zerofill NOT NULL COMMENT '1 = transferred / 0 = new data',
				  `employee_device_id` int(11) NOT NULL COMMENT 'for anviz device',
				  PRIMARY KEY (`id`),
				  KEY `code,date,type` (`employee_code`,`date`,`type`),
				  KEY `date` (`date`),
				  KEY `employee_code` (`employee_code`),
				  KEY `code-date-time` (`employee_code`,`date`,`time`),
				  KEY `date-time` (`date`,`time`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$return['message']    .= " Table Name : {$table_name} was successfully created";
		}

		$table_name      	   = 'g_anviz_users';
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_anviz_users` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				  `device_id` int(11) NOT NULL ,
				  `employee_id` int(11) NOT NULL,
				  `employee_code` varchar(32) NOT NULL,
				  `device_employee_id` int(11) NOT NULL,
				  `fullname` varchar(32) NOT NULL,
				  `nickname` varchar(32) NOT NULL,
				  `password` varchar(32) NOT NULL,
				  `verification_type` int(11) NOT NULL,
				  `user_type` int(11) NOT NULL,
				  `card1` int(11) NOT NULL,
				  `card2` int(11) NOT NULL,
				  `card3` int(11) NOT NULL,
				  `kgroup` int(11) NOT NULL,
				  `sync` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			";			
			Model::runSql($sql);

			$return['message']    .= " Table Name : {$table_name} was successfully created ";
		}else{
			$return['message']    .= " Table Name : {$table_name} already exists ";
		}

		$table_name      	   = 'g_anviz_raw_logs';
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_anviz_raw_logs` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				  `device_id` int(11) NOT NULL,
				  `time` varchar(64) NOT NULL,
				  `type` int(11) NOT NULL,
				  `machine_id` int(11) NOT NULL,
				  `backup_id` int(11) NOT NULL,
				  `status` int(11) NOT NULL COMMENT '0=error/ no employee, 1=completed'
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			";
			Model::runSql($sql);

			$return['message']    .= " Table Name : {$table_name} was successfully created ";
		}else{
			$field_name      = 'status';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `status` int(11) NOT NULL COMMENT '0=error/ no employee, 1=completed";
				Model::runSql($sql);
				$return['message']    .= " Table Name : {$table_name} updated!";
			}else{
				$return['message']    = 'Table already updated!';
			}			
		}

		$table_name      	   = 'g_anviz_connection';
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_anviz_connection` (
				`id` int(11) NOT NULL,
				  `terminal_no` int(11) NOT NULL,
				  `device_id` int(11) NOT NULL,
				  `device_name` varchar(32) NOT NULL,
				  `firmware` varchar(32) NOT NULL,
				  `ip_address` varchar(32) NOT NULL,
				  `mask` varchar(32) NOT NULL,
				  `gateway` varchar(32) NOT NULL,
				  `server_ip` varchar(32) NOT NULL,
				  `mac_address` varchar(32) NOT NULL,				 
				  `total_users` int(11) NOT NULL,
				  `total_logs` int(11) NOT NULL,
				  `total_fingerprint` int(11) NOT NULL,
				  `status` varchar(32) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1;
			";
			Model::runSql($sql);
			$return['message']    .= " Table Name : {$table_name} was successfully created ";
		}else{
			$field_name      = 'total_users';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `total_users` int(11) NOT NULL";
				Model::runSql($sql);
				$return['message']    .= " Table Name : {$table_name} updated!";
			}else{
				$return['message']    = 'Table already updated!';
			}		

			$field_name      = 'total_logs';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `total_logs` int(11) NOT NULL";
				Model::runSql($sql);
				$return['message']    .= " Table Name : {$table_name} updated!";
			}else{
				$return['message']    = 'Table already updated!';
			}	

			$field_name      = 'total_fingerprint';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `total_fingerprint` int(11) NOT NULL";
				Model::runSql($sql);
				$return['message']    .= " Table Name : {$table_name} updated!";
			}else{
				$return['message']    = 'Table already updated!';
			}		

			$return['message']    .= " Table Name : {$table_name} already exists ";
		}

		return $return;
	}

	private function updateTotalEarningsDeductionsDecimals(){
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_PAYSLIP;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'total_earnings';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( $is_field_exists ){				
				$sql = "ALTER TABLE {$table_name} MODIFY COLUMN `total_earnings` decimal(10,2)";
				Model::runSql($sql);
			}else{				
				$sql = "ALTER TABLE {$table_name} ADD `total_earnings` float  decimal(10,2) NOT NULL";
				Model::runSql($sql);
			}

			$field_name      = 'total_deductions';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( $is_field_exists ){				
				$sql = "ALTER TABLE {$table_name} MODIFY COLUMN `total_deductions` decimal(10,2)";
				Model::runSql($sql);
			}else{				
				$sql = "ALTER TABLE {$table_name} ADD `total_deductions` float  decimal(10,2) NOT NULL";
				Model::runSql($sql);
			}

			$return['message']    = "Field Name : {$field_name} updated";
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_payslip` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `employee_id` int(11) NOT NULL,
				  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `basic_pay` double(15,2) NOT NULL,
				  `gross_pay` double(15,2) NOT NULL,
				  `total_earnings` float decimal(10,2) NOT NULL,
				  `total_deductions` float decimal(10,2) NOT NULL,
				  `net_pay` double(15,2) NOT NULL,
				  `taxable` double(15,2) NOT NULL,
				  `non_taxable` double(15,2) NOT NULL,
				  `withheld_tax` double(15,2) NOT NULL,
				  `month_13th` double(15,2) NOT NULL,
				  `sss` double(15,2) NOT NULL,
				  `pagibig` double(15,2) NOT NULL,
				  `philhealth` double(15,2) NOT NULL,
				  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
				  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
				  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
				  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
				  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
				  `overtime` double NOT NULL,
				  `number_of_declared_dependents` int(11) NOT NULL,
				  `taxable_benefits` double(15,2) NOT NULL,
				  `non_taxable_benefits` double(15,2) NOT NULL,
				  `tardiness_amount` double(15,2) NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
			$return['message']    .= " Table Name : {$table_name} was successfully created";
		}
		return $return;
	}

	private function employeeBenefits(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_BENEFITS_MAIN;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'description';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `description` VARCHAR(80) NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'criteria';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `criteria` TEXT;";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'custom_criteria';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `custom_criteria` TEXT;";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_benefits_main` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` smallint(6) NOT NULL,
				  `employee_department_id` bigint(20) NOT NULL,
				  `benefit_id` int(11) NOT NULL,
				  `description` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
				  `criteria` text COLLATE utf8_unicode_ci NOT NULL,
				  `custom_criteria` text COLLATE utf8_unicode_ci NOT NULL,
				  `applied_to` varchar(50) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `id` (`id`),
				  KEY `employee_department_id` (`employee_department_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
		}

		$table_name      	   = G_SETTINGS_EMPLOYEE_BENEFITS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){	

			$field_name      = 'cutoff';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `cutoff` INT(1);";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'is_auto_load';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` DROP `is_auto_load`;";
				Model::runSql($sql);
			}

			$field_name      = 'multiplied_by';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `multiplied_by` VARCHAR( 25 )";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_settings_employee_benefits` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `code` varchar(50) CHARACTER SET latin1 NOT NULL,
				  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
				  `description` varchar(200) CHARACTER SET latin1 NOT NULL,
				  `amount` double NOT NULL,
				  `is_taxable` varchar(5) CHARACTER SET latin1 NOT NULL,
				  `cutoff` int(1) NOT NULL DEFAULT '1' COMMENT '1 = first cutoff / 2 = second cutoff / 3 = both',
				  `multiplied_by` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
				  `is_archive` varchar(5) CHARACTER SET latin1 NOT NULL,
				  `date_created` varchar(80) CHARACTER SET latin1 NOT NULL,
				  `date_last_modified` varchar(80) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `id` (`id`),
				  KEY `code` (`code`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}
		
		return $return;
	}

	private function sprintVariablesDefaultCustomValue(){
		$sv = new G_Sprint_Variables();
		$sv->loadDefaultWorkingDays();
		$sv->loadDefaultCetaAndSea();
		$sv->loadDefaultMinimumRate();	
	}	

	private function sprintVariablesCustomValue(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = SPRINT_VARIABLES;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'custom_value_a';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `custom_value_a` text NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_sprint_variables` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `variable_name` varchar(100) DEFAULT NULL,
				  `value` varchar(255) DEFAULT NULL,
				  `custom_value_a` text NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);

			$sv = new G_Sprint_Variables();
			$sv->loadDefaultWorkingDays();
			$sv->loadDefaultCetaAndSea();
			$sv->loadDefaultMinimumRate();	
		}
		
		return $return;
	}

	private function overtimeAllowanceWithAppliedDayType(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_OVERTIME_ALLOWANCE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'applied_day_type';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_day_type` text NOT NULL AFTER `object_type`";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'description_day_type';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `description_day_type` text NOT NULL AFTER `description`";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_overtime_allowance` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `object_id` bigint(20) NOT NULL,
				  `object_type` varchar(5) NOT NULL,
				  `applied_day_type` text NOT NULL,
				  `ot_allowance` float NOT NULL,
				  `multiplier` int(11) NOT NULL,
				  `max_ot_allowance` float NOT NULL,
				  `date_start` varchar(50) NOT NULL,
				  `description` text NOT NULL,
				  `description_day_type` text NOT NULL,
				  `date_created` varchar(50) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}
		
		return $return;
	}

	private function breaktimeManagementWithDayType(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = BREAK_TIME_SCHEDULE_DETAILS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){					
			$field_name      = 'applied_to_legal_holiday';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_to_legal_holiday` int(2) NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}
			
			$field_name      = 'applied_to_special_holiday';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_to_special_holiday` int(2) NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'applied_to_restday';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_to_restday` int(2) NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

			$field_name      = 'applied_to_regular_day';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_to_regular_day` int(2) NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = "Field Name : {$field_name} already exists";
			}

		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_break_time_schedule_details` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `header_id` int(11) NOT NULL,
				  `obj_id` int(11) NOT NULL,
				  `obj_type` varchar(2) NOT NULL COMMENT 'a = all, e = employee, d = department',
				  `break_in` time NOT NULL,
				  `break_out` time NOT NULL,
				  `to_deduct` int(2) NOT NULL DEFAULT '0' COMMENT '0 = no / 1 = yes',
				  `applied_to_legal_holiday` int(2) NOT NULL,
				  `applied_to_special_holiday` int(2) NOT NULL,
				  `applied_to_restday` int(2) NOT NULL,
				  `applied_to_regular_day` int(2) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}
		
		return $return;
	}

	private function officialBusinessRequestIsApprovedAddLength(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'is_approved';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `is_approved` varchar(15)";				
				Model::runSql($sql);
				$return['message']    = "Field Name : {$field_name} was successfully created";
			}else{
				$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN is_approved varchar(15)";
				Model::runSql($sql);
				$return['message']    = "Field Name : {$field_name} was successfully updated";
			}
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_official_business_request` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` int(11) NOT NULL,
				  `employee_id` int(11) NOT NULL,
				  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
				  `comments` text COLLATE utf8_unicode_ci NOT NULL,
				  `is_approved` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
				  `created_by` int(11) NOT NULL,
				  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `employee_id-date` (`employee_id`,`date_start`,`date_end`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
			";
			Model::runSql($sql);
		}
		
		return $return;
	}

	private function loadDefaultPayslipTemplates(){
		$sql = "
			INSERT INTO `g_payslip_template` (`id`, `template_name`, `is_default`) VALUES
			(1, 'Template 01', 'No'),
			(2, 'Template 02', 'No'),
			(3, 'Template 03', 'No'),
			(4, 'Template 04', 'Yes');
		";
		Model::runSql($sql);
	}

	private function payslipTemplates(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_PAYSLIP_TEMPLATE;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( !$is_table_exists ){			
		
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_payslip_template` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `template_name` varchar(320) NOT NULL,
				  `is_default` varchar(5) NOT NULL COMMENT 'Yes or No',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
			";
			Model::runSql($sql);

			self::loadDefaultPayslipTemplates();
		}
		
		return $return;
	}

	private function sprintVariablesWithNightShiftHours(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$sv = new G_Sprint_Variables();		
		$sv->loadDefaultNightShiftHours();
		
		return $return;
	}

	private function updateOTNDRate(){
		$sql = "
			UPDATE g_attendance_rate SET regular_overtime_nightshift_differential = 125
		";
		Model::runSql($sql);		
	}

	private function employeeEarningsWithCriteria(){
		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		$table_name      	   = G_EMPLOYEE_EARNINGS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		if( $is_table_exists ){			
			$field_name      = 'apply_to_all_employee';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` DROP COLUMN `apply_to_all_employee`";				
				Model::runSql($sql);				
			}

			$field_name      = 'employee_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` DROP COLUMN `employee_id`";				
				Model::runSql($sql);				
			}

			$field_name      = 'department_section_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` DROP COLUMN `department_section_id`";				
				Model::runSql($sql);				
			}

			$field_name      = 'employment_status_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` DROP COLUMN `employment_status_id`";				
				Model::runSql($sql);				
			}

			$field_name      = 'amount';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `amount` float(11,2)";						
				Model::runSql($sql);				
			}else{
				$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `amount` float(11,2)";										
				Model::runSql($sql);
			}

			$field_name      = 'id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( $is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` MODIFY COLUMN `id` bigint(11) NOT NULL AUTO_INCREMENT";						
				Model::runSql($sql);								
			}

			$field_name      = 'object_id';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `object_id` int(11) NOT NULL";						
				Model::runSql($sql);								
			}

			$field_name      = 'applied_to';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `applied_to` int(10) NOT NULL";						
				Model::runSql($sql);								
			}

			$field_name      = 'earning_type';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `earning_type` int(11) NOT NULL COMMENT '1 = percentage / 2 = amount'";						
				Model::runSql($sql);								
			}

			$field_name      = 'percentage';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `percentage` float(11,2) NOT NULL";						
				Model::runSql($sql);								
			}

			$field_name      = 'percentage_multiplier';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `percentage_multiplier` int(11) NOT NULL COMMENT '1 = monthly / 2 = daily'";						
				Model::runSql($sql);								
			}

			$field_name      = 'description';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `description` text COLLATE utf8_unicode_ci NOT NULL";						
				Model::runSql($sql);								
			}

			$field_name      = 'object_description';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			if( !$is_field_exists ){				
				$sql = "ALTER TABLE `{$table_name}` ADD `object_description` text COLLATE utf8_unicode_ci NOT NULL";						
				Model::runSql($sql);								
			}


		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_employee_earnings` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `company_structure_id` int(11) NOT NULL,				  
				  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
				  `amount` float(11,2) DEFAULT NULL,
				  `payroll_period_id` int(11) NOT NULL,
				  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
				  `is_taxable` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
				  `date_created` datetime NOT NULL,
				  `object_id` int(11) NOT NULL,
				  `applied_to` int(10) NOT NULL,
				  `earning_type` int(11) NOT NULL COMMENT '1 = percentage / 2 = amount',
				  `percentage` float(11,2) NOT NULL,
				  `percentage_multiplier` int(11) NOT NULL COMMENT '1 = monthly / 2 = daily',
				  `description` text COLLATE utf8_unicode_ci NOT NULL,
				  `object_description` text COLLATE utf8_unicode_ci NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `company_structure_id` (`company_structure_id`),
				  KEY `payroll_period_id` (`payroll_period_id`),
				  KEY `id` (`id`,`company_structure_id`,`payroll_period_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
			";
			Model::runSql($sql);
		}
		
		return $return;
	}

	private function createFpAttendanceSummaryTable(){
		$table_name      	   = G_ATTENDANCE_LOG;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists ){
			$field_name      = 'remarks';
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);

			if( !$is_field_exists ){
				$sql = "ALTER TABLE `{$table_name}` ADD `remarks` VARCHAR(128) CHARACTER SET latin1 NOT NULL";
				Model::runSql($sql);
			}else{
				$return['message']    = 'Table already updated!';
			}
			
		}else{
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
				  `id` bigint(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `employee_code` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `employee_name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `date` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `time` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
				  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`id`),
				  KEY `code,date,type` (`employee_code`,`date`,`type`),
				  KEY `date` (`date`),
				  KEY `employee_code` (`employee_code`),
				  KEY `code-date-time` (`employee_code`,`date`,`time`),
				  KEY `date-time` (`date`,`time`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
		}

		return $return;
	}

	private function createRolesManagement(){
		$roles_table     	   		  = ROLES;
		$roles_action_table    		  = ROLE_ACTIONS;
		$is_table_roles_exists        = Sprint_Tables_Helper::sqlIsTableNameExists($roles_table);
		$is_table_role_actions_exists = Sprint_Tables_Helper::sqlIsTableNameExists($roles_action_table);
		$create_default_values 		  = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_roles_exists ){
			$field_name      = 'role_id';
			$table_name      = G_EMPLOYEE_USER;
			$is_field_exists = Sprint_Tables_Helper::sqlIsFieldTableExists($field_name, $table_name);
			$create_default_values = false;

			if( !$is_field_exists ){
				$sql = "ALTER TABLE `{$table_name}` ADD `role_id` bigint(11) NOT NULL";
				Model::runSql($sql);
				$return['message']     = 'Table successfully updated!';
			}else{				
				$return['message']     = 'Table already updated!';
			}
		}

		if( !$is_table_role_actions_exists ){
			$sql = "
				CREATE TABLE IF NOT EXISTS `g_role_actions` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `role_id` int(11) NOT NULL,
				  `parent_module` varchar(30) CHARACTER SET latin1 NOT NULL,
				  `module` varchar(80) CHARACTER SET latin1 NOT NULL,
				  `action` varchar(80) CHARACTER SET latin1 NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
			";
			Model::runSql($sql);
		}

		$sql = "
			CREATE TABLE IF NOT EXISTS `g_roles` (
			  `id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
			  `description` varchar(180) CHARACTER SET latin1 NOT NULL,
			  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
			  `date_created` varchar(50) CHARACTER SET latin1 NOT NULL,
			  `last_modified` varchar(80) CHARACTER SET latin1 NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		";
		Model::runSql($sql);
		
		//If newly created table load default values
		if( $create_default_values ){
			self::loadDefaultSettingUserRoles();
		}

		return $return;
	}

	private function createNotificationTable(){
		$table_name      	   = G_NOTIFICATIONS;
		$is_table_exists       = Sprint_Tables_Helper::sqlIsTableNameExists($table_name);
		$create_default_values = true;

		$return['message']    = 'Successfully created';
		$return['is_created'] = true;

		if( $is_table_exists ){
			$create_default_values = false;
			$return['message']     = 'Table already updated!';
		}

		$sql = "
			CREATE TABLE IF NOT EXISTS `g_notifications` (
			  `id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `event_type` varchar(150) CHARACTER SET latin1 NOT NULL,
			  `description` varchar(180) CHARACTER SET latin1 NOT NULL,
			  `status` varchar(20) CHARACTER SET latin1 NOT NULL,
			  `item` int(11) NOT NULL,
			  `date_modified` varchar(50) CHARACTER SET latin1 NOT NULL,
			  `date_created` varchar(50) CHARACTER SET latin1 NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		";
		Model::runSql($sql);

		return $return;
	}

	/*Version 1.1.0033*/
	private function version110033(){		
		$log = array();
		//Roles management
		$log['Settings : Roles Management'] = $this->createRolesManagement();

		//Notifications
		$log['Notifications'] = $this->createNotificationTable();

		$log['errors']     = '';
		$log['is_success'] = true;

		return $log;
	}

	/*Version 1.2.0017*/
	private function version120017(){
		$log = array();
		//Settings Employee Benefits
		$log['Settings : Employee Benefits'] = $this->createSettingsEmpoyeeBenefitsTable();

		//Employee Benefits Main
		$log['Employee : Benefits']     = $this->createEmpoyeeBenefitsMainTable();

		//Payroll Variables
		$log['Settings : Payroll Variables'] = $this->createPayrollVariablesTable();

	    //Employee Contribution
		$log['Employee : Contributions']     = $this->createEmployeeContributionsTable();						

		//FP Attendance Summary
		$log['Attendance : FP Attendance']      = $this->createFpAttendanceSummaryTable();	

		$log['errors']     = '';
		$log['is_success'] = true;

		return $log;
	}

	/*Version 1.3.0000*/
	private function version130000(){
		$log = array();
		//Employee - added new field
		$log['HR : Employee'] = $this->createEmployeeTableWithTaxExempted();
		$log['Help']          = $this->createSprintManualDb();

		$log['errors']     = '';
		$log['is_success'] = true;
		
		return $log;
	}

	/*Version 1.4.0000*/
	private function version140000(){
		$log = array();
		//
		$log['FP Attendance']     		   = $this->updateFpTableStructure();
		$log['Request Approvers'] 	       = $this->createRequestApproversTables();
		$log['Employee Overtime Requests'] = $this->updateOvertimeRequestTableStructure();
		$log['Official Business']          = $this->updateOfficialBusinessTableStructure();
		$log['errors']     = '';
		$log['is_success'] = true;		

		return $log;
	}

	/*Version 1.5.0006*/
	private function version150006(){
		$log = array();
		
		$log['Net Taxable'] 	 = $this->createNetTaxableTable();
		$log['FP Logs']     	 = $this->updateFpLogsStructure();		
		$log['Employee Payslip'] = $this->updateEmployeePayslipTableStructure();
		$log['Allowed IP']       = $this->createIPAllowedTable();		
		$log['Attendance Triggers'] = $this->createAttendanceTriggers();
		
		$log['errors']           = '';
		$log['is_success']       = true;		

		return $log;
	}

	/*Version 1.6.0014*/
	private function version160014(){
		$log = array();
		
		$log['OT Allowance'] 	   = $this->createOTAllowanceTable();
		$log['Breaktime Schedule'] = $this->createBreaktimeScheduleTable();		
		$log['Contributions']      = $this->updateContributionsTable();
		$log['Employee : Confidential and none confidential'] = $this->updateEmployeeConfidentialNoneConfidential();		
		
		$log['errors']           = '';
		$log['is_success']       = true;		

		return $log;
	}

	/*Version 1.7.0014*/
	private function version170014(){
		$log = array();
		
		$log['Group Restday'] 	 = $this->createGroupRestDay();
		$log['Sprint Variables'] = $this->createSprintVariablesDefaultWorkingDays();		
		$log['Employee Table']   = $this->updateEmployeeTableWithWorkingDays();
		$log['Pagibig Table']    = $this->updatePagibigTable();
		$log['Deduction Breakdown']  = $this->updateDeductionBreakDown();
		$log['Attendance Rate']      = $this->addRegularNightShiftRate();

		$log['errors']           = '';
		$log['is_success']       = true;		

		return $log;
	}

	/*Version 1.8.0006*/
	private function version180006(){
		$log = array();
		
		$log['CETA SEA'] 	 		      = $this->createSprintVariablesCETASEA();
		$log['Hold move deductions']      = $this->createExcludedEmployeeDeduction();				

		$log['errors']           = '';
		$log['is_success']       = true;		

		return $log;
	}

	/*Version 1.9.0000*/
	private function version190000(){
		$log = array();
		
		$log['Earnings / Deductions'] = $this->earningsDeductionsA();
		$log['errors']           = '';
		$log['is_success']       = true;		

		return $log;
	}

	/*Version 1.9.0007*/
	private function version190007(){
		$log = array();
		
		$log['Employee Benefits'] = $this->employeeBenefits();
		$log['Sprint Variables']  = $this->sprintVariablesCustomValue();
		$log['Employee Leave']    = $this->employeeLeaveCreditAutoIncrement();
		$log['Anviz Biometrics']  = $this->anvizBiometricsTables();
		$log['Employee Payslip']  = $this->updateTotalEarningsDeductionsDecimals();
		$log['errors']            = '';
		$log['is_success']        = true;		

		return $log;
	}

	/*Version 1.9.2*/
	private function version192(){
		$log = array();
		
		$log['Overtime Allowance'] = $this->overtimeAllowanceWithAppliedDayType();
		$log['Employee OB']  	   = $this->officialBusinessRequestIsApprovedAddLength();
		$log['Employee Earnings']  = $this->employeeEarningsWithCriteria();
		$log['Sprint Variables']   = $this->sprintVariablesWithNightShiftHours();	
		$log['Payslip Templates']  = $this->payslipTemplates();
		$log['ND Rate']			   = $this->updateOTNDRate();
		$log['errors']             = '';
		$log['is_success']         = true;		

		return $log;
	}

	/*Version 1.9.4*/
	private function version194(){
		$log = array();
		
		$log['Breaktime Management'] = $this->breaktimeManagementWithDayType();		
		$log['errors']             = '';
		$log['is_success']         = true;		

		return $log;
	}

	public function updateTablesByAppVersion($version = ''){
		$return = array();
		$log    = array();		

		$data  	 = array();				
		$v = new G_Sprint_Version();
		$data = $v->getAppVersion();

		$version_part = explode("/", $data);		
		$app_version  = trim($version_part[0]);

		//Check if selected version is higher than current version		
		if( $app_version > $version ){
			$log['message']    = "<div class='alert alert-error'>Your app version is higher than selected version. Cannot downgrade version.</div>"; 
			$log['is_success'] = false;		
			return $log;
		}

		$log['errors']     = 'Version selected is not available!'; 
		$log['is_success'] = false;		
		$log['message']    = 'Version selected is not available!'; 

		if( !empty($version) ){
			//Create DB backup
			$mdb = new G_Database();
			$mdb->setUserName(DB_USERNAME);
			$mdb->setHostName(DB_HOST);
			$mdb->setDbName(DB_DATABASE);
			$mdb->setPassword(DB_PASSWORD);	
			$mdb->setBackupName('sprinthr_backup');	
			$mdb->backupDatabase();

			$v = new G_Sprint_Version();
			$versions = $v->getVersionList();

			if( in_array($version, $versions) ){
				switch ($version) {
					case '1.1.0033':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['errors']     = '';
						$log['is_success'] = true;
						break;

					case '1.2.0017':					
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['errors']     = '';
						$log['is_success'] = true;
						break;

					case '1.3.0006':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.3.0011':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.4.0009':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.5.0006':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.6.0014':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.7.0014':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;

					case '1.8.0006':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;
					case '1.8.0009':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;
					case '1.9.0000':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['status']['1.9.0000'] = $this->version190000();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;
					case '1.9.0007':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['status']['1.9.0000'] = $this->version190000();
						$log['status']['1.9.0007'] = $this->version190007();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;
					case '1.9.2':
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['status']['1.9.0000'] = $this->version190000();
						$log['status']['1.9.0007'] = $this->version190007();
						$log['status']['1.9.2']    = $this->version192();
						$log['errors']     = ''; 
						$log['is_success'] = true;
						break;
					case '1.9.3':				
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['status']['1.9.0000'] = $this->version190000();
						$log['status']['1.9.0007'] = $this->version190007();
						$log['status']['1.9.2']    = $this->version192();
						$log['status']['1.9.3']['message']    = "Version was successfully";	
						$log['status']['1.9.3']['is_created'] = true;
						$log['is_success'] = true;
						break;
					case '1.9.4':				
						$log['status']['1.1.0033'] = $this->version110033();
						$log['status']['1.2.0017'] = $this->version120017();
						$log['status']['1.3.0006'] = $this->version130000();
						$log['status']['1.3.0011'] = '';
						$log['status']['1.4.0009'] = $this->version140000();
						$log['status']['1.5.0006'] = $this->version150006();
						$log['status']['1.6.0014'] = $this->version160014();
						$log['status']['1.7.0014'] = $this->version170014();
						$log['status']['1.8.0006'] = $this->version180006();
						$log['status']['1.9.0000'] = $this->version190000();
						$log['status']['1.9.0007'] = $this->version190007();
						$log['status']['1.9.2']    = $this->version192();
						$log['status']['1.9.3']['message']    = "Version was successfully";	
						$log['status']['1.9.3']['is_created'] = true;
						$log['status']['1.9.4']    = $this->version194();
						$log['is_success'] = true;
						break;
					default:	
						$log['errors']     = 'Version selected is not available!'; 
						$log['is_success'] = false;							
						break;
				}
				
				$status = $log['status'][$version];
				$errors = $log['errors'];
				
				if( !empty($status) ){
					$class = 'alert alert-info';
					$message = "<ul>";
					foreach( $status as $key => $value ){					
						$keyValidate = trim($key);
						if( $keyValidate != "errors" && $keyValidate != "is_success" ){
							$valMsg   = $value['message'];
							$message .= "<li>{$key} <b>({$valMsg})</b><br /></li>";
						}
					}
					$message .= "</ul>";
				}else{		
					$class   = 'alert alert-error';
					foreach( $errors as $key => $value ){						
						$message = "{$key} <b>({$value})</b><br />";
					}
				}

				$message = "<div class='{$class}'>{$message}</div>";
				$log['message'] = $message;
				}
				
			}
		
		return $log;
	}

	public function disableFactoryReset() {
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/factory_reset.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2   = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//Settings');
			$obj[0]->enable = 'false';						
			$xmlStr->asXml($xmlUrl);
		}
	}
	
	public function truncateAllTables()
	{
		$tables = self::sqlGetAllTables();
		$not_to_truncate = array(
			"g_attendance_rate",
			"g_philhealth",
			"g_philhealth_table_rate",
			"g_settings_application_status",
			"g_settings_company_benefits",
			"g_settings_default_leave",
			"g_settings_dependent_relationship",
			"g_settings_employee_field",
			"g_settings_grace_period",
			"g_settings_holiday",
			"g_settings_settings_license",
			"g_settings_language",
			"g_settings_location",
			"g_settings_membership",
			"g_settings_membership_type",
			"g_settings_pay_period",
			"g_settings_policy",
			"g_settings_request",
			"g_settings_request_approvers",
			"g_settings_salutation",
			"g_settings_subdivision_type",
			"g_sss",
			"g_sss_table_rate",
			"g_system_module",
			"g_tax_table"
		);

		$truncated_tables = array();
		foreach($tables as $key => $value) {
			foreach($value as $table) {
				if(!in_array($table, $not_to_truncate)){
					$sql = 'TRUNCATE TABLE ' . $table;
					Model::runSql($sql);

				}
			}
		}

	}
	
	public function truncateRecruitmentData()
	{		
		$sql = 'TRUNCATE TABLE ' . APPLICANT;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . APPLICANT_PROFILE;		
		Model::runSql($sql);
		
		$sql = 'TRUNCATE TABLE ' . APPLICANT_LOGS;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . G_JOB_APPLICATION_EVENT;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_ATTACHMENT;		
		Model::runSql($sql);
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_REQUIREMENTS;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_EXAMINATION;		
		Model::runSql($sql);
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_EDUCATION;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_TRAINING;		
		Model::runSql($sql);
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_SKILLS;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_LICENSE;		
		Model::runSql($sql);
		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_LANGUAGE;		
		Model::runSql($sql);		
		
		$sql = 'TRUNCATE TABLE ' . APPLICANT_PROFILE;		
		Model::runSql($sql);
	}
	
	public function truncateRecommendedTables()
	{
		$sql = 'TRUNCATE TABLE ' . COMPANY_BRANCH;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_ACCESS_RIGHTS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_USER_GROUP;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . SUBDIVISION_TYPE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . LICENSE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_JOB;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_JOB_SPECIFICATION;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_JOB_SALARY_RATE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LOAN;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LOAN_DETAILS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_EARNINGS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_JOB_VACANCY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . EMPLOYEE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_DETAILS_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . APPLICANT;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_ERROR_LEAVE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_HOLIDAY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_HOLIDAY_BRANCH;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_CONTACT_DETAILS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_EMERGENCY_CONTACT;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_DEPENDENT;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_DIRECT_DEPOSIT;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_ATTENDANCE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_BRANCH_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_BASIC_SALARY_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_JOB_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_SUBDIVISION_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_PERFORMANCE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_TRAINING;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_WORK_EXPERIENCE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_EDUCATION;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_SKILLS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_SUPERVISOR;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LICENSE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LANGUAGE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_MEMBERSHIP;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_MEMO;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LEAVE_AVAILABLE;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_LEAVE_REQUEST;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_DYNAMIC_FIELD;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_ATTACHMENT;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_REQUIREMENTS;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_LEAVE;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_CONTRIBUTION;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_EXTEND_CONTRACT;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_TAGS;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_JOB_APPLICATION_EVENT;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_ATTACHMENT;		
		Model::runSql($sql);	
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_REQUIREMENTS;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_EXAMINATION;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_EDUCATION;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_TRAINING;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_SKILLS;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_LICENSE;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_LANGUAGE;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_APPLICANT_WORK_EXPERIENCE;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_EXAM;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_EXAM_CHOICES;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_EXAM_QUESTION;		
		Model::runSql($sql);		
		$sql = 'TRUNCATE TABLE ' . G_PERFORMANCE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_PERFORMANCE_INDICATOR;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SETTINGS_EMPLOYEE_FIELD;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_PAYSLIP_ERROR;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_ATTENDANCE_ERROR;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_PAYSLIP;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_DAILY_TIME_RECORD;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_OVERTIME_ERROR;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_OVERTIME_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_REST_DAY_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_CHANGE_SCHEDULE_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_UNDERTIME_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SETTINGS_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SETTINGS_REQUEST_APPROVERS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_REQUEST;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_REQUEST_APPROVERS;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_OVERTIME;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_ATTENDANCE_LOG;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_RESTDAY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SCHEDULE_GROUP;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SCHEDULE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_GROUP_SCHEDULE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_SCHEDULE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_PAYABLE;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_EMPLOYEE_PAYABLE_HISTORY;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . EMAIL_BUFFER;		
		Model::runSql($sql);
		$sql = 'TRUNCATE TABLE ' . G_SETTINGS_DEFAULT_LEAVE;		
		Model::runSql($sql);
	}
	
	public static function convertXmlToArray($xml,$type)
	{
		//CONVERT XML TO ARRAY
		if($type == 'settings'){
			$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/' . $xml;		
		}elseif($type == 'tables'){
			$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/tables/' . $xml;
		}else{
			$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/company_structure/' . $xml;
		}		
		if(Tools::isFileExist($file)==true) {			
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2 = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);				
			$return = $arrXml;
		}else {
			$return = 'No file exist';
		}
		
		return $return;
		
	}
	
	public static function checkTableIfExists()
	{
		$arr = self::convertXmlToArray("table_structure.xml","tables");		
		
		foreach($arr as $key => $value){
			foreach($value as $keysub => $subvalue){
				$sql = 'SELECT 1 from `' . $subvalue['name'] . '`';		
				$is_exists =  Model::runSql($sql);
				if($is_exists == false){
					self::createTable($subvalue['name']);
				}
			}
		}
	}
	
	function createTable($table_name)
	{
		//Drop Table if exists
			self::sqlDropTable($table_name);
		//
		switch ($table_name) {
			case G_ACCESS_RIGHTS:				
			$sql = "CREATE TABLE IF NOT EXISTS `g_access_rights` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `user_group_id` int(11) NOT NULL,
					  `rights` varchar(240) COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialize',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
			break;
			
			case APPLICANT:	
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_id` int(11) DEFAULT NULL COMMENT 'if hired',
					  `company_structure_id` int(11) NOT NULL,
					  `job_vacancy_id` int(11) NOT NULL,
					  `job_id` int(11) NOT NULL,
					  `application_status_id` int(11) NOT NULL COMMENT '0=application submitted,  1=interview,  2=offered a job, 3=declined offer, 4=reject, 5=hired',
					  `lastname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `firstname` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `middlename` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `extension_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `gender` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `birthdate` date NOT NULL,
					  `birth_place` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `address` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
					  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `country` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `home_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `email_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `qualification` text COLLATE utf8_unicode_ci NOT NULL,
					  `sss_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `tin_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `applied_date_time` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `hired_date` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `rejected_date` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'if applicant did not passed, or failed or declined or delinquent',
					  `resume_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `resume_path` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `application_status_id` (`application_status_id`),
					  KEY `job_id` (`job_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";			
			break;
			
			case G_APPLICANT_ATTACHMENT:	
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_attachment` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `size` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '346kb',
					  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'doc, docx, pdf',
					  `date_attached` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `added_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hr admin name',
					  `screen` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'personal details,\r\n employment details, qualification',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
						
			break;
			
			case G_APPLICANT_EDUCATION:	
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_education` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `institute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `course` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `start_date` date NOT NULL,
					  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
					  `gpa_score` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `attainment` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
			break;
			
			case G_APPLICANT_EXAMINATION:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_examination` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `applicant_id` int(11) NOT NULL,
					  `exam_id` int(11) NOT NULL,
					  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `passing_percentage` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
					  `exam_code` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `schedule_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `status` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'pending, failed, passed, rescheduled',
					  `result` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `questions` text COLLATE utf8_unicode_ci NOT NULL,
					  `time_duration` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'day:hour:minute',
					  `scheduled_by` int(11) NOT NULL COMMENT 'employee_id',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
			break;
			
			case G_APPLICANT_LANGUAGE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_language` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `fluency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'writing, speaking,reading',
					  `competency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'poor,basic,good, mother tongue',
					  `comments` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
			break;
			
			case G_APPLICANT_LICENSE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_license` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `license_type` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Engineer License etc',
					  `license_number` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `issued_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `expiry_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_APPLICANT_REQUIREMENTS:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_requirements` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `requirements` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_complete` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT '1=complete, 0=incomplete',
					  `date_updated` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `applicant_id` (`applicant_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_APPLICANT_SKILLS:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_skills` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `skill` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `years_experience` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `comments` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_APPLICANT_TRAINING:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_training` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `from_date` date NOT NULL,
					  `to_date` date NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `provider` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `location` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `cost` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `renewal_date` date NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			
			break;
			
			case G_APPLICANT_WORK_EXPERIENCE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_applicant_work_experience` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `applicant_id` int(11) NOT NULL,
					  `company` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `job_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `from_date` date NOT NULL,
					  `to_date` date NOT NULL,
					  `comment` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_attendance_rate":
			$sql = "CREATE TABLE IF NOT EXISTS `g_attendance_rate` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `nightshift_rate` float NOT NULL COMMENT '%',
					  `regular_overtime` float NOT NULL COMMENT '% (example: 125%)',
					  `nightshift_overtime` float NOT NULL COMMENT '%',
					  `restday` float NOT NULL COMMENT '%',
					  `restday_overtime` float NOT NULL COMMENT '%',
					  `holiday_special` float NOT NULL COMMENT '%',
					  `holiday_special_overtime` float NOT NULL COMMENT '%',
					  `holiday_legal` float NOT NULL COMMENT '%',
					  `holiday_legal_overtime` float NOT NULL COMMENT '%',
					  `holiday_special_restday` float NOT NULL COMMENT '%',
					  `holiday_special_restday_overtime` float NOT NULL COMMENT '%',
					  `holiday_legal_restday` float NOT NULL COMMENT '%',
					  `holiday_legal_restday_overtime` float NOT NULL COMMENT '%',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case COMPANY_BRANCH:
			$sql = "CREATE TABLE IF NOT EXISTS `g_company_branch` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
					  `province` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
					  `city` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
					  `address` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `fax` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `location_id` int(40) NOT NULL,
					  `is_archive` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;
					";
			break;
			
			case COMPANY_INFO:
			$sql = "CREATE TABLE IF NOT EXISTS `g_company_info` (
					  `id` int(6) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(6) NOT NULL,
					  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `fax` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `address1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `state` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `zip_code` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `company_logo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case COMPANY_STRUCTURE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_company_structure` (
					  `id` smallint(6) NOT NULL AUTO_INCREMENT,
					  `company_branch_id` int(6) NOT NULL DEFAULT '0',
					  `title` tinytext COLLATE utf8_unicode_ci NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Branch / Department / Group / Team',
					  `parent_id` int(6) NOT NULL DEFAULT '0',
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;
					";
			break;
			
			case G_CUTOFF_PERIOD:
			$sql = "CREATE TABLE IF NOT EXISTS `g_cutoff_period` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `period_start` date NOT NULL,
					  `period_end` date NOT NULL,
					  `payout_date` date NOT NULL,
					  `salary_cycle_id` int(11) NOT NULL,
					  `is_lock` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case G_DAILY_TIME_RECORD:
			$sql = "CREATE TABLE IF NOT EXISTS `g_daily_time_record` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `date_entry` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `time_entry` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EEO_JOB_CATEGORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_eeo_job_category` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `category_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;
					";
			break;
			
			case EMAIL_BUFFER:
			$sql = "CREATE TABLE IF NOT EXISTS `g_email_buffer` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `sent_from` varchar(80) CHARACTER SET latin1 NOT NULL COMMENT 'sender name, web email (Administrator, admin@gleent.com)',
					  `email_address` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `sent_name` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `subject` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `message` text CHARACTER SET latin1 NOT NULL,
					  `attachment` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `is_sent` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'Yes / No',
					  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT 'Yes / No',
					  `error_message` text CHARACTER SET latin1 NOT NULL,
					  `date_added` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case EMPLOYEE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee` (
					  `id` bigint(30) NOT NULL AUTO_INCREMENT,
					  `hash` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `company_structure_id` int(11) NOT NULL,
					  `employment_status_id` int(11) NOT NULL,
					  `eeo_job_category_id` int(11) NOT NULL,
					  `photo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `salutation` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `middlename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `extension_name` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `birthdate` date NOT NULL,
					  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `marital_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `nationality` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `number_dependent` int(11) NOT NULL,
					  `sss_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `tin_number` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `pagibig_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `philhealth_number` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `hired_date` date NOT NULL,
					  `terminated_date` date NOT NULL,
					  `e_is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
					  PRIMARY KEY (`id`),
					  KEY `firstname-lastname` (`firstname`,`lastname`,`id`),
					  KEY `id-first-last-code` (`id`,`firstname`,`lastname`,`employee_code`),
					  KEY `firstname` (`firstname`),
					  KEY `lastname` (`lastname`),
					  KEY `employee_code` (`employee_code`),
					  KEY `id` (`id`,`hash`,`firstname`,`lastname`,`employee_code`),
					  KEY `company_structure_id` (`company_structure_id`),
					  KEY `nationality` (`nationality`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case G_EMPLOYEE_ATTACHMENT:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_attachment` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `filename` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `description` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `size` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '346kb',
					  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'doc, docx, pdf',
					  `date_attached` date NOT NULL,
					  `added_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'hr admin name',
					  `screen` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'personal details, employment details, qualification',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_ATTENDANCE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_attendance` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date_attendance` date NOT NULL,
					  `is_present` tinyint(1) NOT NULL,
					  `is_paid` tinyint(1) NOT NULL,
					  `is_restday` tinyint(1) NOT NULL,
					  `is_holiday` tinyint(1) NOT NULL,
					  `is_leave` smallint(1) NOT NULL,
					  `leave_id` int(11) NOT NULL,
					  `is_suspended` tinyint(1) NOT NULL,
					  `holiday_id` int(11) NOT NULL,
					  `holiday_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `holiday_type` tinyint(4) NOT NULL COMMENT '1 = legal, 2 = special',
					  `scheduled_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `scheduled_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_date_in` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_date_out` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `overtime_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `overtime_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `total_hours_worked` float NOT NULL COMMENT 'based from actual time in and out',
					  `night_shift_hours` float NOT NULL,
					  `night_shift_overtime_hours` float NOT NULL,
					  `night_shift_overtime_excess_hours` float NOT NULL,
					  `night_shift_hours_special` float NOT NULL,
					  `night_shift_hours_legal` float NOT NULL,
					  `holiday_hours_special` float NOT NULL,
					  `holiday_hours_legal` float NOT NULL,
					  `overtime_hours` float NOT NULL,
					  `overtime_excess_hours` float NOT NULL,
					  `restday_overtime_hours` float NOT NULL,
					  `restday_overtime_excess_hours` float NOT NULL,
					  `restday_overtime_nightshift_hours` float NOT NULL,
					  `restday_overtime_nightshift_excess_hours` float NOT NULL,
					  `late_hours` float NOT NULL,
					  `undertime_hours` float NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `employee_id-date_attendance` (`employee_id`,`date_attendance`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_ATTENDANCE_CORRECTION_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_attendance_correction_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `date_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `correct_date_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `correct_time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `correct_time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `comment` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_BASIC_SALARY_HISTORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_basic_salary_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `job_salary_rate_id` int(11) NOT NULL,
					  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'monthly_rate, daily_rate, hourly_rate',
					  `basic_salary` float NOT NULL,
					  `pay_period_id` int(11) NOT NULL COMMENT 'frequency rate: example Bi-Monthly or Monthly',
					  `start_date` date NOT NULL,
					  `end_date` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  KEY `employee_id` (`employee_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
					";
			break;
			
			case G_EMPLOYEE_BRANCH_HISTORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_branch_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `company_branch_id` int(11) NOT NULL,
					  `branch_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `start_date` date NOT NULL,
					  `end_date` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Null it means Current',
					  PRIMARY KEY (`id`),
					  KEY `employee_id` (`employee_id`),
					  KEY `company_branch_id` (`company_branch_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case G_EMPLOYEE_CHANGE_SCHEDULE_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_change_schedule_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` datetime NOT NULL,
					  `date_start` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `change_schedule_comments` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` int(11) NOT NULL,
					  `is_archive` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_CONTACT_DETAILS:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_contact_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `address` text COLLATE utf8_unicode_ci NOT NULL,
					  `city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `province` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `zip_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `home_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `mobile` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `work_telephone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `work_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `other_email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case G_EMPLOYEE_CONTRIBUTION:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_contribution` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `sss_ee` float NOT NULL,
					  `pagibig_ee` float NOT NULL,
					  `philhealth_ee` float NOT NULL,
					  `sss_er` float NOT NULL,
					  `pagibig_er` float NOT NULL,
					  `philhealth_er` float NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
					";
			break;
			
			
			case G_EMPLOYEE_DEPENDENT:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_dependent` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `relationship` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `birthdate` date NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case G_EMPLOYEE_DETAILS_HISTORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_details_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` bigint(30) NOT NULL,
					  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `modified_by` bigint(30) NOT NULL,
					  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
					  `history_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `date_modified` datetime NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
					  PRIMARY KEY (`id`),
					  KEY `firstname-lastname` (`id`),
					  KEY `id-first-last-code` (`id`,`employee_code`),
					  KEY `employee_code` (`employee_code`),
					  KEY `id` (`id`,`employee_code`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case G_EMPLOYEE_DIRECT_DEPOSIT:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_direct_deposit` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `bank_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `account` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `account_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'checking / savings',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case G_EMPLOYEE_DYNAMIC_FIELD:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_dynamic_field` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,					  
					  `employee_id` int(11) NOT NULL,
					  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `value` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `screen` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_EARNINGS:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_earnings` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` varchar(240) COLLATE utf8_unicode_ci NOT NULL,
					  `title` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
					  `amount` double NOT NULL,
					  `payroll_period_id` int(11) NOT NULL,
					  `apply_to_all_employee` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `is_taxable` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `company_structure_id` (`company_structure_id`),
					  KEY `employee_id` (`employee_id`),
					  KEY `payroll_period_id` (`payroll_period_id`),
					  KEY `id` (`id`,`company_structure_id`,`employee_id`,`payroll_period_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_EDUCATION:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_education` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `institute` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `course` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `year` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `start_date` date NOT NULL,
					  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
					  `gpa_score` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `attainment` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_EMERGENCY_CONTACT:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_emergency_contact` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `person` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
					  `relationship` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `home_telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `work_telephone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `address` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
					";
			break;
			
			case G_EMPLOYEE_EXTEND_CONTRACT:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_extend_contract` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `start_date` date NOT NULL,
					  `end_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
					  `attachment` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `is_done` int(11) NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `employee_id` (`employee_id`),
					  KEY `end_date` (`end_date`),
					  KEY `start_date` (`start_date`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
					";
			break;
			
			case G_EMPLOYEE_GROUP_SCHEDULE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_group_schedule` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_group_id` int(11) NOT NULL COMMENT 'g_employee.id OR g_company_structure.id ',
					  `schedule_group_id` int(11) NOT NULL,
					  `schedule_id` int(11) NOT NULL,
					  `date_start` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_group` int(1) NOT NULL COMMENT '1 = Employee; 2 = Group',
					  PRIMARY KEY (`id`),
					  KEY `schedule_id` (`schedule_id`),
					  KEY `employee_group_id` (`employee_group_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This is template schedule' AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_JOB_HISTORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_job_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `job_id` int(11) NOT NULL,
					  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
					  `employment_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Part Time, Full Time etc',
					  `start_date` date NOT NULL,
					  `end_date` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  KEY `job_id` (`job_id`),
					  KEY `employee_id` (`employee_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case G_EMPLOYEE_LANGUAGE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_language` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `fluency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'writing, speaking,reading',
					  `competency` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'poor,basic,good, mother tongue',
					  `comments` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_LEAVE_AVAILABLE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_leave_available` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `leave_id` int(11) NOT NULL,
					  `no_of_days_alloted` int(11) NOT NULL,
					  `no_of_days_available` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;
					";
			break;
			
			case G_EMPLOYEE_LEAVE_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_leave_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `leave_id` int(11) NOT NULL,
					  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `apply_half_day_date_start` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `apply_half_day_date_end` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `leave_comments` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Pending / Approved / Disapproved',
					  `is_paid` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `is_archive` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_LICENSE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_license` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `license_type` varchar(256) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Engineer License etc',
					  `license_number` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `issued_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `expiry_date` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `notes` varchar(164) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_LOAN:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_loan` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `type_of_loan_id` int(11) NOT NULL,
					  `interest_rate` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `loan_amount` double NOT NULL,
					  `balance` int(11) NOT NULL,
					  `type_of_deduction_id` int(11) NOT NULL,
					  `no_of_installment` int(11) NOT NULL,
					  `start_date` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `end_date` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `status` varchar(20) CHARACTER SET latin1 NOT NULL,
					  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_LOAN_DETAILS:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_loan_details` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `loan_id` int(11) NOT NULL,
					  `date_of_payment` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `amount` double NOT NULL,
					  `amount_paid` double NOT NULL,
					  `is_paid` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `remarks` text CHARACTER SET latin1 NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_LOAN_PAYMENT_BREAKDOWN:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_loan_payment_breakdown` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `loan_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `loan_payment_id` int(11) NOT NULL,
					  `reference_number` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
					  `amount_paid` double NOT NULL,
					  `date_paid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;
					";
			break;
			
			case G_EMPLOYEE_MAKE_UP_SCHEDULE_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_make_up_schedule_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_from` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_to` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `start_time` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `end_time` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `comment` text COLLATE utf8_unicode_ci NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `is_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_MEMBERSHIP:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_membership` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `membership_type_id` int(11) NOT NULL,
					  `membership_id` int(11) NOT NULL,
					  `subscription_ownership` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'company, individual',
					  `subscription_amount` double NOT NULL,
					  `commence_date` date NOT NULL,
					  `renewal_date` date NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_MEMO:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_memo` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `memo` text COLLATE utf8_unicode_ci NOT NULL,
					  `attachment` varchar(164) COLLATE utf8_unicode_ci NOT NULL,
					  `date_created` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `created_by` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_official_business_request` (
					  `id` bigint(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `comments` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_OVERTIME:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_overtime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `reason` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_OVERTIME_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_overtime_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` datetime NOT NULL,
					  `date_start` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `overtime_comments` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
					  `is_archive` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Yes / No',
					  `created_by` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_PAYABLE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_payable` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `balance_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `total_amount` double(15,2) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_PAYABLE_HISTORY:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_payable_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_payable_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `amount_paid` double(15,2) NOT NULL,
					  `date_paid` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_PAYSLIP:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_payslip` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `period_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `period_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `payout_date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `basic_pay` double(15,2) NOT NULL,
					  `gross_pay` double(15,2) NOT NULL,
					  `net_pay` double(15,2) NOT NULL,
					  `taxable` double(15,2) NOT NULL,
					  `withheld_tax` double(15,2) NOT NULL,
					  `month_13th` double(15,2) NOT NULL,
					  `sss` double(15,2) NOT NULL,
					  `pagibig` double(15,2) NOT NULL,
					  `philhealth` double(15,2) NOT NULL,
					  `earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'Initial earnings like overtime, late, basic pay',
					  `other_earnings` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'other earnings to be added manually',
					  `deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
					  `other_deductions` longtext COLLATE utf8_unicode_ci NOT NULL,
					  `labels` mediumtext COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `period_start-end` (`period_start`,`period_end`,`employee_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_PERFORMANCE:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_performance` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `performance_id` int(11) NOT NULL,
					  `performance_title` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `reviewer_id` int(11) NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `position` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
					  `created_date` date NOT NULL,
					  `period_from` date NOT NULL,
					  `period_to` date NOT NULL,
					  `due_date` date NOT NULL,
					  `summary` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
					  `status` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'being reviewed, pending',
					  `kpi` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case G_EMPLOYEE_REQUEST:
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `settings_request_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `request_id` int(11) NOT NULL,
					  `start_date` date NOT NULL,
					  `end_date` date NOT NULL,
					  `start_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					  `end_time` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					  `reason` text COLLATE utf8_unicode_ci NOT NULL,
					  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '0 = Pending / 1 = Approve / -1 = Disapprove',
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_request_approvers":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_request_approvers` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `request_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Generic / Overtime / Leave / Rest Day',
					  `request_type_id` int(11) NOT NULL,
					  `position_employee_id` int(11) NOT NULL,
					  `type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Employee Id / Position Id / Department Id',
					  `level` int(11) NOT NULL COMMENT '0 = Override',
					  `override_level` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `message` text COLLATE utf8_unicode_ci NOT NULL,
					  `status` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Pending / Approved / Disapproved',
					  `remarks` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Approver''s Remarks',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_requirements":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_requirements` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `requirements` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_complete` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `date_updated` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_restday":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_restday` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `reason` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_rest_day_request":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_rest_day_request` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `schedule_id` int(11) NOT NULL,
					  `date_applied` datetime NOT NULL,
					  `date_start` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  `rest_day_comments` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_approved` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_schedule_specific":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_schedule_specific` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `date_start` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `date_end` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_skills":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_skills` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `skill` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `years_experience` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
					  `comments` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_subdivision_history":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_subdivision_history` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `company_structure_id` int(11) NOT NULL COMMENT 'branch,division, department, team',
					  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Finance Department, Production Team',
					  `type` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Department, Group, Team etc',
					  `start_date` date NOT NULL,
					  `end_date` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'if null means current',
					  PRIMARY KEY (`id`),
					  KEY `employee_id` (`employee_id`),
					  KEY `company_structure_id` (`company_structure_id`),
					  KEY `start_date` (`start_date`),
					  KEY `end_date` (`end_date`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_supervisor":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_supervisor` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `supervisor_id` int(11) NOT NULL COMMENT 'employee_id',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_tags":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_tags` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `tags` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_training":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_training` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `from_date` date NOT NULL,
					  `to_date` date NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `provider` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `location` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `cost` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `renewal_date` date NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_undertime_request":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_undertime_request` (
					  `id` bigint(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `employee_id` int(11) NOT NULL,
					  `date_applied` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `date_of_undertime` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `time_out` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `reason` text COLLATE utf8_unicode_ci NOT NULL,
					  `created_by` int(11) NOT NULL,
					  `is_approved` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_employee_work_experience":
			$sql = "CREATE TABLE IF NOT EXISTS `g_employee_work_experience` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `job_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  `from_date` date NOT NULL,
					  `to_date` date NOT NULL,
					  `comment` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_error_attendance":
			$sql = "CREATE TABLE IF NOT EXISTS `g_error_attendance` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `employee_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `date_attendance` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
					  `message` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_fixed` tinyint(1) NOT NULL,
					  `error_type_id` tinyint(10) NOT NULL COMMENT 'ERROR_INVALID_EMPLOYEE = 1; ERROR_INVALID_TIME = 2; ERROR_INVALID_OT = 3; ERROR_INVALID_DATE = 4; ERROR_NO_OUT = 5; ERROR_NO_IN = 6;',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_error_leave":
			$sql = "CREATE TABLE IF NOT EXISTS `g_error_leave` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `employee_code` int(11) NOT NULL,
					  `employee_name` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `date_applied` varchar(20) CHARACTER SET latin1 NOT NULL,
					  `date_start` varchar(15) CHARACTER SET latin1 NOT NULL,
					  `date_end` varchar(15) CHARACTER SET latin1 NOT NULL,
					  `message` text CHARACTER SET latin1 NOT NULL,
					  `is_fixed` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
					  `error_type_id` int(11) NOT NULL COMMENT '1 = EMPLOYEE_DOES_NOT_EXISTS, 2 = INVALID_START_END_DATE',
					  PRIMARY KEY (`id`),
					  KEY `default_log` (`employee_code`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_error_overtime":
			$sql = "CREATE TABLE IF NOT EXISTS `g_error_overtime` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `employee_code` int(11) NOT NULL,
					  `employee_name` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `date_attendance` varchar(20) CHARACTER SET latin1 NOT NULL,
					  `time_in` varchar(15) CHARACTER SET latin1 NOT NULL,
					  `time_out` varchar(15) CHARACTER SET latin1 NOT NULL,
					  `message` text CHARACTER SET latin1 NOT NULL,
					  `is_fixed` varchar(10) CHARACTER SET latin1 NOT NULL DEFAULT 'No' COMMENT 'Yes / No',
					  `error_type_id` int(11) NOT NULL COMMENT '1 - INVALID_SCHEDULE_TIME_INOUT, 2 - INVALID_ACTUAL_TIME_INOUT, 3 = LATE, 4 - ATO_LESS_THAN_STO, 5 - OT_START_LESS_THAN_STO, 6 - OT_END_GREATER_THAN_ATO, 7 - OT_START_LESS_THAN_ATS, 8 - INVALID_EMPLOYEE_ID, ABSENT = 9, OT_LESS_THAN_30 = 10',
					  PRIMARY KEY (`id`),
					  KEY `default_log` (`employee_code`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_error_payslip":
			$sql = "CREATE TABLE IF NOT EXISTS `g_error_payslip` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `message` text COLLATE utf8_unicode_ci NOT NULL,
					  `is_fixed` tinyint(1) NOT NULL,
					  `error_type_id` int(11) NOT NULL COMMENT '1 = ERROR_NO_SALARY, 2 = ERROR_NO_ATTENDANCE',
					  `date_logged` date NOT NULL,
					  `time_logged` time NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_exam":
			$sql = "CREATE TABLE IF NOT EXISTS `g_exam` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `passing_percentage` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `time_duration` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'days:hours:minutes',
					  `created_by` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `date_created` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_exam_choices":
			$sql = "CREATE TABLE IF NOT EXISTS `g_exam_choices` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `exam_question_id` int(11) NOT NULL,
					  `choices` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `order_by` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_exam_question":
			$sql = "CREATE TABLE IF NOT EXISTS `g_exam_question` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `exam_id` int(11) NOT NULL,
					  `question` text COLLATE utf8_unicode_ci NOT NULL,
					  `answer` text COLLATE utf8_unicode_ci NOT NULL,
					  `order_by` int(11) NOT NULL,
					  `type` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_fp_attendance_log":
			$sql = "CREATE TABLE IF NOT EXISTS `g_fp_attendance_log` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) NOT NULL,
					  `employee_code` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `employee_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `date` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `time` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` varchar(128) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `code,date,type` (`employee_code`,`date`,`type`),
					  KEY `date` (`date`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_fp_attendance_summary":
			$sql = "CREATE TABLE IF NOT EXISTS `g_fp_attendance_summary` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `employee_id` int(11) NOT NULL,
					  `employee_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
					  `actual_date_in` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'for biometrics',
					  `actual_time_in` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_date_out` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_time_out` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
					  `actual_total_hours_worked` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'compute by biometrics',
					  `done` int(11) NOT NULL COMMENT 'for biometrics',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_fp_fingerprint":
			$sql = "CREATE TABLE IF NOT EXISTS `g_fp_fingerprint` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) NOT NULL,
					  `employee_code` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT 'employee_code example 100203-023',
					  `name` varchar(64) CHARACTER SET latin1 NOT NULL,
					  `template` longblob NOT NULL,
					  `finger` varchar(64) CHARACTER SET latin1 NOT NULL,
					  `integer_representation` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_holiday":
			$sql = "CREATE TABLE IF NOT EXISTS `g_holiday` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `public_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `holiday_type` tinyint(1) NOT NULL COMMENT '1 = legal, 2 = special',
					  `holiday_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `holiday_month` tinyint(12) NOT NULL,
					  `holiday_day` tinyint(31) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_holiday_branch":
			$sql = "CREATE TABLE IF NOT EXISTS `g_holiday_branch` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `holiday_id` int(11) NOT NULL,
					  `company_branch_id` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_job":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `job_specification_id` int(11) NOT NULL,
					  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `is_active` tinyint(4) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;
					";
			break;
			
			case "g_job_application_event":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job_application_event` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `applicant_id` int(11) NOT NULL,
					  `date_time_created` datetime NOT NULL,
					  `created_by` int(11) NOT NULL COMMENT 'employee_id',
					  `hiring_manager_id` int(11) DEFAULT NULL,
					  `date_time_event` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'on march 6, 3:00pm',
					  `event_type` int(11) NOT NULL COMMENT 'Application Submitted: 0, Interview: 1, Job Offered: 2, Offer Declined: 3, Rejected: 4, Hired: 5',
					  `application_status_id` int(11) NOT NULL,
					  `notes` text COLLATE utf8_unicode_ci NOT NULL,
					  `remarks` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`),
					  KEY `applicant_id` (`applicant_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_job_employment_status":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job_employment_status` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `job_id` int(11) NOT NULL,
					  `employment_status_id` int(11) NOT NULL,
					  `employment_status` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_job_salary_rate":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job_salary_rate` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `job_level` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'senior programmer, beginner programmer etc',
					  `minimum_salary` double DEFAULT NULL,
					  `maximum_salary` double DEFAULT NULL,
					  `step_salary` double DEFAULT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;
					";
			break;
			
			case "g_job_specification":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job_specification` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `duties` text COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_job_vacancy":
			$sql = "CREATE TABLE IF NOT EXISTS `g_job_vacancy` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `job_id` int(11) NOT NULL,
					  `hiring_manager_id` int(11) NOT NULL COMMENT 'employee_id',
					  `job_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `hiring_manager_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
					  `publication_date` date NOT NULL,
					  `advertisement_end` date NOT NULL,
					  `is_active` smallint(6) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_leave":
			$sql = "CREATE TABLE IF NOT EXISTS `g_leave` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
					  `default_credit` int(11) NOT NULL,
					  `is_paid` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
					  `gl_is_archive` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
					";
			break;
			
			case "g_loan_deduction_type":
			$sql = "CREATE TABLE IF NOT EXISTS `g_loan_deduction_type` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `deduction_type` varchar(180) CHARACTER SET latin1 NOT NULL,
					  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;
					";
			break;
			
			case "g_loan_type":
			$sql = "CREATE TABLE IF NOT EXISTS `g_loan_type` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `loan_type` varchar(180) CHARACTER SET latin1 NOT NULL,
					  `is_archive` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `date_created` datetime NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_pagibig_table":
			$sql = "CREATE TABLE IF NOT EXISTS `g_pagibig_table` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `salary_from` float NOT NULL,
					  `salary_to` float NOT NULL,
					  `multiplier_employee` float NOT NULL,
					  `multiplier_employer` float NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_payslip_deductions":
			$sql = "CREATE TABLE IF NOT EXISTS `g_payslip_deductions` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `deduction_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_payslip_earnings":
			$sql = "CREATE TABLE IF NOT EXISTS `g_payslip_earnings` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `earning_type` int(10) NOT NULL COMMENT '0=normal, 1=adjustment, 2=allowance, 3=bonus, 4=advance',
					  `earning_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "g_performance":
			$sql = "CREATE TABLE IF NOT EXISTS `g_performance` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `company_structure_id` int(11) NOT NULL,
					  `title` varchar(124) COLLATE utf8_unicode_ci NOT NULL,
					  `job_id` int(11) NOT NULL,
					  `description` text COLLATE utf8_unicode_ci NOT NULL,
					  `date_created` date NOT NULL,
					  `created_by` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
					  `is_archive` int(11) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
					";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
			
			case "tablename":
			$sql = "";
			break;
		}
		Model::runSql($sql);
	}

	function sqlGetAllTables() {
		$sql = "SHOW TABLES FROM ". DB_DATABASE;
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	function sqlDropTable($table_name)
	{
		$sql = "DROP TABLE IF EXISTS `" . $table_name . "`;";
		Model::runSql($sql);
	}
	
	public function loadDefaultCompanyInfo($c)
	{		
		$arr = self::convertXmlToArray("company_info.xml","company_structure");		
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . COMPANY_INFO;		
			Model::runSql($sql);
		
			$gci = new G_Company_Info();			
			$gci->setAddress($arr['address']);
			$gci->setPhone($arr['phone']);	
			$gci->setFax($arr['fax']);		
			$gci->setAddress1($arr['address1']);
			$gci->setCity($arr['city']);
			$gci->setAddress2($arr['address2']);
			$gci->setState($arr['state']);
			$gci->setZipCode($arr['zip_code']);
			$gci->setRemarks($arr['remarks']);		
			$gci->setCompanyLogo($arr['company_logo] ']);
			$gci->save($c);
		}
	}
	
	public function loadDefaultCompanyStructure()
	{	
		$arr = self::convertXmlToArray("company_structure.xml","company_structure");		
		if($arr){			
	
			$sql = 'TRUNCATE TABLE ' . COMPANY_STRUCTURE;		
			Model::runSql($sql);
			
			$gcs = new G_Company_Structure();			
			$gcs->setCompanyBranchId($arr['company_branch_id']);
			$gcs->setTitle($arr['title']);
			$gcs->setDescription($arr['description']);
			$gcs->setType($arr['type']);
			$gcs->setParentId($arr['parent_id']);	
			$gcs->setIsArchive(G_Company_Structure::NO);			
			return $csid = $gcs->save();
		}
	}
	
	public function loadDefaultCompanyBranch($c)
	{		
		$arr = self::convertXmlToArray("company_branch.xml","company_structure");		
		if($arr){		
		
			$sql = 'TRUNCATE TABLE ' . COMPANY_BRANCH;		
			Model::runSql($sql);
			
			$gcb = new G_Company_Branch();			
			$gcb->setName($arr['name']);
			$gcb->setProvince($arr['province']);	
			$gcb->setCity($arr['city']);				
			$gcb->setAddress($arr['address']);
			$gcb->setZipCode($arr['zip_code']);
			$gcb->setPhone($arr['phone']);
			$gcb->setFax($arr['fax']);
			$gcb->setLocationId($arr['location_id']);
			$gcb->setIsArchive(G_Company_Branch::NO);
			return $gcb->save($c);
		}
	}
	
	public function loadDefaultCompanyDepartment($c,$b)
	{		
		$arr = self::convertXmlToArray("department.xml","company_structure");		
		if($arr){				
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gcs = new G_Company_Structure;					
					$gcs->setCompanyBranchId($b->getId());
					$gcs->setTitle($subvalue['title']);
					$gcs->setDescription("");
					$gcs->setType(G_Company_Structure::DEPARTMENT);
					$gcs->setParentId($c->getId());	
					$gcs->setIsArchive(G_Company_Structure::NO);	
					$gcs->save();							
				}
			}	
		}
	}
	
	public function loadDefaultSettingsDeductionType($c)
	{		
		$arr = self::convertXmlToArray("loan_deduction_type.xml","settings");		
		if($arr){	
			
			$sql = 'TRUNCATE TABLE ' . G_LOAN_DEDUCTION_TYPE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gldt = new G_Loan_Deduction_Type();			
					$gldt->setCompanyStructureId($c->getId());
					$gldt->setDeductionType($subvalue['deduction_type']);
					$gldt->setIsArchive(G_Loan_Deduction_Type::NO);					
					$gldt->setDateCreated(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));	
					$gldt->save();							
				}
			}					
		}
	}
	
	public function loadDefaultSettingsLoanType($c)
	{		
		$arr = self::convertXmlToArray("loan_type.xml","settings");		
		if($arr){	
			
			$sql = 'TRUNCATE TABLE ' . G_LOAN_TYPE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$glt = new G_Loan_Type();					
					$glt->setCompanyStructureId($c->getId());
					$glt->setLoanType($subvalue['loan_type']);
					$glt->setIsArchive(G_Loan_Type::NO);					
					$glt->setDateCreated(Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila'));	
					$glt->save();							
				}
			}					
		}
	}
		
	public function loadDefaultSettingsApplicationStatus($c)
	{		
		$arr = self::convertXmlToArray("application_status.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_APPLICATION_STATUS;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Settings_Application_Status();					
					$g->setCompanyStructureId($c->getId());
					$g->setStatus($subvalue['status']);			
					$g->save();
				}
			}		
		}
	}
	
	public function loadDefaultSettingsSalutation($c)
	{		
		$arr = self::convertXmlToArray("salutation.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_SALUTATION;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gss = new G_Settings_Salutation();
					$gss->setId($row['id']);
					$gss->setCompanyStructureId($c->getId());
					$gss->setSalutation($subvalue['salutation']);
					$gss->setDescription("");				
					$gss->save();
				}
			}		
		}
	}
	
	public function loadDefaultSettingsEmploymentStatus($c)
	{		
		$arr = self::convertXmlToArray("employment_status.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . EMPLOYMENT_STATUS;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Settings_Employment_Status($row['id']);
					$g->setCode($subvalue['code']);
					$g->setStatus($subvalue['status']);	
					$g->save($c);
				}
			}		
		}
	}

	public function loadDefaultSettingsEmployeeStatus($c)
	{		
		$arr = self::convertXmlToArray("employee_status.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_EMPLOYEE_STATUS;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gses = new G_Settings_Employee_Status();
					$gses->setCompanyStructureId($c->getId());
					$gses->setName($subvalue['name']);
					$gses->setIsArchive('No');		
					$gses->setDateCreated(date('Y-m-d'));
					$gses->save();
				}
			}		
		}
	}

	public function loadDefaultSettingsMemo()
	{		
		$arr = self::convertXmlToArray("memo.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_MEMO;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$sm = new G_Settings_Memo();
					$sm->setTitle($subvalue['title']);
					$sm->setContent($subvalue['content']);
					$sm->setCreatedBy($subvalue['created_by']);
					$sm->setIsArchive('No');
					$sm->setDateCreated(date('Y-m-d'));	
					$sm->save();															
				}
			}		
		}
	}

	public function loadDefaultSettingsDeductionBreakdown()
	{		
		$arr = self::convertXmlToArray("deduction_breakdown.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_DEDUCTION_BREAKDOWN;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$e = new G_Settings_Deduction_Breakdown;
					$e->setName($subvalue['name']);
					$e->setBreakdown($subvalue['breakdown']);
					$e->setIsActive('Yes');
					$e->save();														
				}
			}		
		}
	}

	public function loadDefaultSettingsRequirements($c)
	{		
		$arr = self::convertXmlToArray("requirements.xml","settings");					
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_REQUIREMENTS;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gsr = new G_Settings_Requirement();
					$gsr->setCompanyStructureId($c->getId());
					$gsr->setName($subvalue['title']);
					$gsr->setIsArchive('No');		
					$gsr->setDateCreated(date('Y-m-d'));
					$gsr->save();														
				}
			}		
		}
	}
	
	public function loadDefaultSettingsLicenses($c)
	{		
		$arr = self::convertXmlToArray("license.xml","settings");								
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . LICENSE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gsl = new G_Settings_License();					
					$gsl->setLicenseType($subvalue['license_type']);
					$gsl->setDescription($subvalue['description']);	
					$gsl->save($c);						
				}
			}		
		}
	}
	
	public function loadDefaultSettingsLeaveType($c)
	{		
		$arr = self::convertXmlToArray("leave_type.xml","settings");			
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_LEAVE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$l = new G_Leave;					
					$l->setCompanyStructureId($c->getId());
					$l->setName($subvalue['name']);
					$l->setIsPaid(G_Leave::YES);
					$l->setIsArchive(G_Leave::NO);
					$l->save();
				}
			}		
		}
	}
	
	public function loadDefaultSettingsPayPeriod($c)
	{		
		$arr = self::convertXmlToArray("pay_period.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_PAY_PERIOD;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){

					$gspp = new G_Settings_Pay_Period();					
					$gspp->setPayPeriodCode($value['pay_period_code']);
					$gspp->setPayPeriodName($value['pay_period_name']);
					$gspp->setCutOff($value['cut_off']);
					$gspp->setPayOutDay($value['payout_day']);
					$gspp->setIsDefault($value['default']);
					$gspp->save($c);
				
			}		
		}
	}
	
	public function loadDefaultSettingsJob($c)
	{		
		$arr = self::convertXmlToArray("jobs.xml","settings");			
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_JOB;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Job();					
					$g->setCompanyStructureId($c->getId());
					$g->setJobSpecificationId(G_Job::DEFAULT_JOB_SPECIFICATION_ID);	
					$g->setTitle($subvalue['title']);		
					$g->setIsActive(G_Job::ACTIVE);
					$g->save();
				}
			}	

			$job_id = 2; // Human resource manager
			$job = G_Job_Finder::findById($job_id);
			$e = new G_Employee_Job_History;
			$e->setEmployeeId(1);
			$e->setJobId($job_id);
			$e->setName($job->title);
			$e->setEmploymentStatus('Full Time');
			$e->setStartDate('2013-01-01');
			$e->setEndDate('');
			$e->save();	
		}
	}
	
	public function loadDefaultSettingsSkills($c)
	{		
		$arr = self::convertXmlToArray("skills.xml","settings");			
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . SKILLS;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gss = new G_Settings_Skills();					
					$gss->setSkill($subvalue['skill']);		
					$gss->save($c);
				}
			}		
		}
	}
	
	public function loadDefaultSettingsSubdivisionType($c)
	{		
		$arr = self::convertXmlToArray("subdivision_type.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . SUBDIVISION_TYPE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gsst = new G_Settings_Subdivision_Type();					
					$gsst->setType($subvalue['type']);		
					$gsst->save($c);
				}
			}		
		}
	}
	
	public function loadDefaultSettingsLocations($c)
	{
		$arr = self::convertXmlToArray("location.xml","settings");						
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . LOCATION;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gsl = new G_Settings_Location();					
					$gsl->setCode($subvalue['code']);
					$gsl->setLocation($subvalue['location']);	
					$gsl->save($c);	
				}
			}		
		}	
	}
	
	public function loadDefaultSettingsLanguages($c)
	{		
		$arr = self::convertXmlToArray("language.xml","settings");						
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_LANGUAGE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$gsl = new G_Settings_Language();					
					$gsl->setCompanyStructureId($c->getId());		
					$gsl->setLanguage($subvalue['language']);	
					$gsl->save();	
				}
			}		
		}
	}
	
	public function loadDefaultSettingsEeoJobCategory($c)
	{		
		$arr = self::convertXmlToArray("eeo_job_category.xml","settings");						
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_EEO_JOB_CATEGORY;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Eeo_Job_Category();					
					$g->setCompanyStructureId($c->getId());
					$g->setCategoryName($subvalue['category_name']);
					$g->setDescription($subvalue['description']);	
					$g->save();
				}
			}		
		}
	}
	
	public function loadDefaultSettingsJobSalaryRate($c)
	{		
		$arr = self::convertXmlToArray("eeo_job_salary_rate.xml","settings");						
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_JOB_SALARY_RATE;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Job_Salary_Rate();					
					$g->setCompanyStructureId($c->getId());
					$g->setJobLevel($subvalue['job_level']);	
					$g->setMinimumSalary($subvalue['minimum_salary']);	
					$g->setMaximumSalary($subvalue['maximum_salary']);	
					$g->setStepSalary($subvalue['step_salary']);
					$g->save();	
				}
			}		
		}
	}
	
	public function loadDefaultSettingsDependentRelationship($c)
	{		
		$arr = self::convertXmlToArray("dependent_relationship.xml","settings");						
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . DEPENDENT_RELATIONSHIP;		
			Model::runSql($sql);
			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){
					$g = new G_Settings_Dependent_Relationship();
					$g->setCompanyStructureId($c->getId());
					$g->setRelationship($subvalue['relationship']);	
					$g->save();
				}
			}		
		}
	}
	
	public function loadDefaultSettingsScheduleGroup($c)
	{
		$arr = self::convertXmlToArray("schedule_group.xml","settings");		
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SCHEDULE_GROUP;		
			Model::runSql($sql);
						
			$gsg = new G_Schedule_Group();			
			$gsg->setName($arr['schedule_name']);
			$gsg->setPublicId(uniqid());
			$gsg->setGracePeriod($arr['grace_period']);
			$gsg->setEffectivityDate('2013-01-01');			
			$gsg->setAsDefault(G_Schedule_Group::IS_DEFAULT);	
			$sgid = $gsg->save();
			
			$g = G_Schedule_Group_Finder::findById($sgid);
			if($g){
				$g->setDefaultGroup();
			}
			return $sgid;
		}
	}
	
	public function loadDefaultSettingsSchedule($c,$sg)
	{
		$arr = self::convertXmlToArray("schedule.xml","settings");			
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SCHEDULE;		
			Model::runSql($sql);
						
			$s = new G_Schedule;			
			$s->setPublicId(uniqid());
			$s->setScheduleGroupId($sg->getId());
			$s->setName($arr['schedule_name']);
			$s->setGracePeriod($arr['grace_period']);
			$s->setWorkingDays($arr['working_days']);
			$s->setTimeIn($arr['time_in']);
			$s->setTimeOut($arr['time_out']);			
			$sid = $s->save();	
			
			$s = G_Schedule_Finder::findById($sid);
			if($s){
				$s->setDefaultSchedule();
				$s->saveToScheduleGroup($sg);
			}
		}
	}
	
	public function loadDefaultPagibigTable($c)
	{
		$arr = self::convertXmlToArray("pagibig_table.xml","settings");
		if($arr){			
			$sql = 'TRUNCATE TABLE ' . G_PAGIBIG;		
			Model::runSql($sql);
				
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){				
					$gpt = new G_Pagibig_Table();					
					$gpt->setCompanyStructureId($c->getId());
					$gpt->setSalaryFrom($subvalue['salary_from']);
					$gpt->setSalaryTo($subvalue['salary_to']);				
					$gpt->setMultiplierEmployee($subvalue['multiplier_employee']);				
					$gpt->setMultiplierEmployer($subvalue['multiplier_employer']);	
					$gpt->save();							
				}
			}		
		}
	}

	public function loadDefaultPhilhealthTable()
	{
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 			
		$sql = "
			CREATE TABLE IF NOT EXISTS `p_philhealth` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `salary_base` float(10,2) NOT NULL,
			  `salary_bracket` smallint(6) NOT NULL,
			  `from_salary` decimal(15,2) NOT NULL,
			  `to_salary` decimal(15,2) NOT NULL,
			  `monthly_contribution` float(10,2) NOT NULL,
			  `employee_share` float(10,2) NOT NULL,
			  `company_share` float(10,2) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		$result = $mysqli->query($sql);	

		$sql = "
			INSERT INTO `p_philhealth` (`id`, `salary_base`, `salary_bracket`, `from_salary`, `to_salary`, `monthly_contribution`, `employee_share`, `company_share`) VALUES
			(1, 8000.00, 1, 0.00, 8999.99, 200.00, 100.00, 100.00),
			(2, 9000.00, 2, 9000.00, 9999.99, 225.00, 112.50, 112.50),
			(3, 10000.00, 3, 10000.00, 10999.99, 250.00, 125.00, 125.00),
			(4, 11000.00, 4, 11000.00, 11999.99, 275.00, 137.50, 137.50),
			(5, 12000.00, 5, 12000.00, 12999.99, 300.00, 150.00, 150.00),
			(6, 13000.00, 6, 13000.00, 13999.99, 325.00, 162.50, 162.50),
			(7, 14000.00, 7, 14000.00, 14999.99, 350.00, 175.00, 175.00),
			(8, 15000.00, 8, 15000.00, 15999.99, 375.00, 187.50, 187.50),
			(9, 16000.00, 9, 16000.00, 16999.99, 400.00, 200.00, 200.00),
			(10, 17000.00, 10, 17000.00, 17999.99, 425.00, 212.50, 212.50),
			(11, 18000.00, 11, 18000.00, 18999.99, 450.00, 225.00, 225.00),
			(12, 19000.00, 12, 19000.00, 19999.99, 475.00, 237.50, 237.50),
			(13, 20000.00, 13, 20000.00, 20999.99, 500.00, 250.00, 250.00),
			(14, 21000.00, 14, 21000.00, 21999.99, 525.00, 262.50, 262.50),
			(15, 22000.00, 15, 22000.00, 22999.99, 550.00, 275.00, 275.00),
			(16, 23000.00, 16, 23000.00, 23999.99, 575.00, 287.50, 287.50),
			(17, 24000.00, 17, 24000.00, 24999.99, 600.00, 300.00, 300.00),
			(18, 25000.00, 18, 25000.00, 25999.99, 625.00, 312.50, 312.50),
			(19, 26000.00, 19, 26000.00, 26999.99, 650.00, 325.00, 325.00),
			(20, 27000.00, 20, 27000.00, 27999.99, 675.00, 337.50, 337.50),
			(21, 28000.00, 21, 28000.00, 28999.99, 700.00, 350.00, 350.00),
			(22, 29000.00, 22, 29000.00, 29999.99, 725.00, 362.50, 362.50),
			(23, 30000.00, 23, 30000.00, 30999.99, 750.00, 375.00, 375.00),
			(24, 31000.00, 24, 31000.00, 31999.99, 775.00, 387.50, 387.50),
			(25, 32000.00, 25, 32000.00, 32999.99, 800.00, 400.00, 400.00),
			(26, 33000.00, 26, 33000.00, 33999.99, 825.00, 412.50, 412.50),
			(27, 34000.00, 27, 34000.00, 34999.99, 850.00, 425.00, 425.00),
			(28, 35000.00, 28, 35000.00, 9999999.99, 875.00, 437.50, 437.50);
		";		
		$result = $mysqli->query($sql);		
	}

	public function loadDefaultNetTaxableTable()
	{
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 				
		$sql = "INSERT IGNORE INTO `g_net_taxable_table` (`id`, `over`, `not_over`, `amount`, `rate_percentage`, `excess_over`) VALUES
				(1, 0, 10000, 0, 0.05, 0),
				(2, 10000, 30000, 500, 0.10, 10000),
				(3, 30000, 70000, 2500, 0.15, 30000),				
				(4, 70000, 140000, 8500, 0.2, 70000),
				(5, 140000, 250000, 22500, 0.25, 140000),
				(6, 250000, 500000, 50000, 0.3, 250000),
				(7, 500000, 10000000, 125000, 0.32, 500000);				
		";				
		$result = $mysqli->query($sql);		
	}

	public function loadDefaultBreaktimeSchedule()
	{
		$date_start   = date("Y-m-d");
		$date_created = date("Y-m-d H:i:s"); 
		
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 				
		$sql = "
			INSERT INTO `g_break_time_schedule_header` (`id`, `schedule_in`, `schedule_out`, `break_time_schedules`, `applied_to`, `date_start`, `date_created`) VALUES(1, '08:00:00', '17:00:00', '12:00 PM - 01:00 PM', 'All employees', '{$date_start}', '{$date_created}');				
		";	
		$result = $mysqli->query($sql);		

		$sql = "
		INSERT INTO `g_break_time_schedule_details` (`header_id`, `obj_id`, `obj_type`, `break_in`, `break_out`, `to_deduct`) VALUES(1, 0, 'a', '12:00:00', '13:00:00', 1);
		";				
		$result = $mysqli->query($sql);		
	}

	public function loadDefaultSssTable()
	{
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 				
		$sql = "
		CREATE TABLE IF NOT EXISTS `p_sss` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `monthly_salary_credit` float(10,2) NOT NULL,
		  `from_salary` float(10,2) NOT NULL,
		  `to_salary` float(10,2) NOT NULL,
		  `employee_share` float(10,2) NOT NULL,
		  `company_share` float(10,2) NOT NULL,
		  `company_ec` float(10,2) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
		";
		
		$result = $mysqli->query($sql);		
		
		$sql = "
			INSERT INTO `p_sss` (`id`, `monthly_salary_credit`, `from_salary`, `to_salary`, `employee_share`, `company_share`, `company_ec`) VALUES
				(1, 1000.00, 0.00, 1249.99, 36.30, 83.70, 10.00),
				(2, 1500.00, 1250.00, 1749.99, 54.50, 120.50, 10.00),
				(3, 2000.00, 1750.00, 2249.99, 72.70, 157.30, 10.00),
				(4, 2500.00, 2250.00, 2749.99, 90.80, 194.20, 10.00),
				(5, 3000.00, 2750.00, 3249.99, 109.00, 231.00, 10.00),
				(6, 3500.00, 3250.00, 3749.99, 127.20, 267.80, 10.00),
				(7, 4000.00, 3750.00, 4249.99, 145.30, 304.70, 10.00),
				(8, 4500.00, 4250.00, 4749.99, 163.50, 341.50, 10.00),
				(9, 5000.00, 4750.00, 5249.99, 181.70, 378.30, 10.00),
				(10, 5500.00, 5250.00, 5749.99, 199.80, 415.20, 10.00),
				(11, 6000.00, 5750.00, 6249.99, 218.00, 452.00, 10.00),
				(12, 6500.00, 6250.00, 6749.99, 236.20, 488.80, 10.00),
				(13, 7000.00, 6750.00, 7249.99, 254.30, 525.70, 10.00),
				(14, 7500.00, 7250.00, 7749.99, 272.50, 562.50, 10.00),
				(15, 8000.00, 7750.00, 8249.99, 290.70, 599.30, 10.00),
				(16, 8500.00, 8250.00, 8749.99, 308.80, 636.20, 10.00),
				(17, 9000.00, 8750.00, 9249.99, 327.00, 673.00, 10.00),
				(18, 9500.00, 9250.00, 9749.99, 345.20, 709.80, 10.00),
				(19, 10000.00, 9750.00, 10250.00, 363.30, 746.70, 10.00),
				(20, 10500.00, 10250.00, 10750.00, 381.50, 783.50, 10.00),
				(21, 11000.00, 10750.00, 11250.00, 399.70, 820.30, 10.00),
				(22, 11500.00, 11250.00, 11750.00, 417.80, 857.20, 10.00),
				(23, 12000.00, 11750.00, 12250.00, 436.00, 894.00, 10.00),
				(24, 12500.00, 12250.00, 12750.00, 454.20, 930.80, 10.00),
				(25, 13000.00, 12750.00, 13250.00, 472.30, 967.70, 10.00),
				(26, 13500.00, 13250.00, 13750.00, 490.50, 1004.50, 10.00),
				(27, 14000.00, 13750.00, 14250.00, 508.70, 1041.30, 10.00),
				(28, 14500.00, 14250.00, 14750.00, 526.80, 1078.20, 10.00),
				(29, 15000.00, 14750.00, 15250.00, 545.00, 1135.00, 30.00),
				(30, 15500.00, 15250.00, 15750.00, 563.20, 1171.80, 30.00),
				(31, 16000.00, 15750.00, 999999.00, 581.30, 1208.70, 30.00);
		";	

		$result = $mysqli->query($sql);		

	}

	public function loadDefaultCutoffPeriods(){
		$number_of_cutoff_to_generate = 1; //previous cutoff

		for( $counter = 0; $counter < $number_of_cutoff_to_generate; $counter++ ){
			$date = date("Y-m-d",strtotime("-{$counter} months"));
			$c = new G_Cutoff_Period();
			$return = $c->generateCutOffPeriodsByDate($date);
		}	 
	}
	 
	public function loadDefaultSettingsGracePeriod($c,$sg)
	{
		$arr = self::convertXmlToArray("grace_period.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_GRACE_PERIOD;		
			Model::runSql($sql);
						
			$gsg = new G_Settings_Grace_Period;			
			$gsg->setCompanyStructureId($c->getId());
			$gsg->setTitle($arr['title']);
			$gsg->setDescription($arr['description']);
			$gsg->setIsArchive(G_Settings_Grace_Period::NO);
			$gsg->setIsDefault(G_Settings_Grace_Period::YES);
			$gsg->setNumberMinuteDefault($arr['number_minute_default']);
			$gsg->save();
		}
	}

	public function loadDefaultPayrollSettings()
	{
		$arr = self::convertXmlToArray("payroll_settings.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . PAYROLL_VARIABLES;		
			Model::runSql($sql);
			
			foreach( $arr['Fields'] as $key => $value ){
				$fields[] = $key;
				$values[] = $value;	
			}

			$sql_fields = implode(",", $fields);
			$sql_values = implode(",", $values);

			$sql = "INSERT INTO " . PAYROLL_VARIABLES . "({$sql_fields})VALUES({$sql_values})";			
			Model::runSql($sql);
		}
	}

	public function loadDefaultSettingEmployeeBenefits($c)
	{
		$arr = self::convertXmlToArray("benefits.xml","settings");				
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . G_SETTINGS_COMPANY_BENEFITS;		
			Model::runSql($sql);

			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){				
					$b = new G_Settings_Employee_Benefit();			
					$b->setCode($subvalue['benefit_code']);
			        $b->setName($subvalue['benefit_name']);        
			        $b->setDescription($subvalue['benefit_description']);        
			        $b->setAmount($subvalue['benefit_amount']);			        
			        $b->setIsTaxable(G_Settings_Employee_Benefit::NO);	       
			        $b->setDateCreated(date("Y-m-d H:i:s"));	        
					$b->saveBenefit();		
				}
			}	
		}
	}

	public function loadDefaultSettingUserRoles()
	{
		$arr_role = self::convertXmlToArray("role.xml","settings");	
		$arr_role_action = self::convertXmlToArray("role_action.xml","settings");		

		//SAVE DEFAULT ROLE and ROLE ACTION
		foreach($arr_role_action as $key => $value){
			foreach($value as $keysub => $subvalue){
				$modules[$subvalue['parent_module']][$subvalue['module']] = $subvalue['action'];
			}
		}	

		$r = new G_Role();			
		$r->setName($arr_role['role_name']);
		$r->setDescription($arr_role['role_description']);
		$r->setIsArchive(G_Role::NO);
		$r->setDateCreated(date("Y-m-d"));
		$id = $r->save();						
		if( $id > 0 ){				
			$r->setId($id);
			$r->addModuleActions($modules);
		}

		return $id;

	}


	public function loadDefaultEmployeeWithUserAccount()
	{
		$c = G_Company_Structure_Finder::findById(1);	
		self::loadDefaultSettingsEmployee($c);
		self::loadDefaultSettingsUserWithRole($c);
	}

	public function loadDefaultSettingsUserWithRole($c)
	{
		$arr_user = self::convertXmlToArray("user.xml","settings");				
		if($arr_user){
			
			$id  = $this->loadDefaultSettingUserRoles();
			$sql = 'TRUNCATE TABLE ' . G_USER;		
			Model::runSql($sql);
			
			$u = new G_Employee_User();
			$u->setCompanyStructureId($c->getId());
	        $u->setEmployeeId(1);        
	        $u->setUsername($arr_user['username']);                
	        $u->setPassword($arr_user['password']);                
	        $u->setRoleId($id);                
	        $u->setDateCreated(date("Y-m-d"));                
			$u->addUser();
		}
	}
	
	public function loadDefaultSettingsUser($c)
	{
		$arr_user = self::convertXmlToArray("user.xml","settings");				
		if($arr_user){

			$sql = 'TRUNCATE TABLE ' . G_USER;		
			Model::runSql($sql);
			
			$u = new G_Employee_User();
			$u->setCompanyStructureId($c->getId());
	        $u->setEmployeeId(1);        
	        $u->setUsername($arr_user['username']);                
	        $u->setPassword($arr_user['password']);                	                       
	        $u->setDateCreated(date("Y-m-d"));                
			$u->addUser();
		}
	}

	public function loadDefaultSettingsEmployee($c)
	{
		$arr = self::convertXmlToArray("employee.xml","settings");			
		if($arr){
			
			$sql = 'TRUNCATE TABLE ' . EMPLOYEE;		
			Model::runSql($sql);
						
			$e = new G_Employee;
			$e->setEmployeeCode($arr['employee_code']);
			$e->setFirstname($arr['firstname']);
			$e->setLastname($arr['lastname']);
			$e->setHiredDate($arr['hired_date']);
			$e->setIsArchive(G_Employee::NO);
			$employee_id = $e->save();
			
			$e = Employee_Factory::get($employee_id);
			
			$hash = Utilities::createHash($employee_id);
			$e->addHash($hash);
			
			$c = G_Company_Structure_Finder::findById($c->getId());
			$c->addEmployee($e);
			
			$c_subd = G_Company_Structure_Finder::findById($arr['department_company_structure_id']);
			if($c_subd) {
				$c_subd->addEmployeeToSubdivision($e,$arr['hired_date']);
			}
				
			$b = G_Company_Branch_Finder::findById(1);
			if($b) {
				$b->addEmployee($e, $arr['hired_date']);
			}
			
		}
	}

	public function loadDefaultSettingsHoliday()
	{
		$arr = self::convertXmlToArray("holiday.xml","settings");		
		if($arr){			
			foreach($arr as $key => $value){
				foreach($value as $keysub => $subvalue){				
					$h = new G_Holiday();
					$h->setTitle($subvalue['title']);
					$h->setMonth($subvalue['month']);
					$h->setDay($subvalue['day']);
					$h->setType($subvalue['holiday_type']);
		            $h->setYear(date('Y'));
					$h->save();

					//$sql_value = "()";
				}
			}		
		}
	}
	
	public function loadDefaultSettingsPayrollPeriod($c)
	{
		/*$date = Tools::getGmtDate('Y-m-d');
		$cycle = G_Salary_Cycle_Finder::findDefault();
		$current = Tools::getCutOffPeriod($date, $cycle->getCutOffs());
		$payout_date = Tools::getPayoutDate($date, $cycle->getCutOffs(), $cycle->getPayoutDays());
		G_Cutoff_Period_Manager::savePeriod(date('Y'),$current['start'], $current['end'], G_Salary_Cycle::TYPE_SEMI_MONTHLY, $payout_date);	*/
	}

	public function loadDefaultValuesByVersion($version)
	{
		$start_version = G_Sprint_Version::STARTING_VERSION;		
		$c = G_Company_Structure_Finder::findById(1);		
		if( empty($c) ){
			$csid = self::loadDefaultCompanyStructure();	
			$c    = G_Company_Structure_Finder::findById($csid);			
		}

		switch ($version) {
			case $start_version:
				self::loadDefaultValuesStartVersion();
				break;
			case '1.1.0033':
				self::loadDefaultValuesStartVersion();
				self::loadDefaultSettingsUserWithRole($c);
				break;
			case '1.2.0017':								
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				break;
			case '1.3.0006':
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				break;
			case '1.3.0011':
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				break;
			case '1.4.0009':
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				break;
			case '1.5.0006':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();				
				break;
			case '1.6.0014':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				break;
			case '1.7.0014':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				break;
			case '1.8.0006':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();
				break;
			case '1.8.0009':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();
				break;
			case '1.9.0000':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();				
				break;
			case '1.9.0007':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();	
				self::employeeBenefits();
				self::sprintVariablesCustomValue();	
				self::sprintVariablesDefaultCustomValue();
				self::defaultSettingsLeaveGeneral();
				break;
			case '1.9.2':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();	
				self::employeeBenefits();
				self::sprintVariablesCustomValue();	
				self::sprintVariablesDefaultCustomValue();
				self::defaultSettingsLeaveGeneral();
				self::loadDefaultPayslipTemplates();
				self::updateOTNDRate();
				break;
			case '1.9.3':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();	
				self::employeeBenefits();
				self::sprintVariablesCustomValue();	
				self::sprintVariablesDefaultCustomValue();
				self::defaultSettingsLeaveGeneral();
				self::loadDefaultPayslipTemplates();
				self::updateOTNDRate();
				break;
			case '1.9.4':				
				self::dropAttendanceTriggers();
				self::loadDefaultValuesStartVersion();
				self::createEmployeeContributionsTable();
				self::loadDefaultSettingsUserWithRole($c);
				self::loadDefaultSettingEmployeeBenefits($c);
				self::loadDefaultPayrollSettings();
				self::createEmployeeTableWithTaxExempted();
				self::updateFpTableStructure();
				self::createNetTaxableTable();
				self::updateFpLogsStructure();		
				self::updateEmployeePayslipTableStructure();
				self::createIPAllowedTable();		
				self::createAttendanceTriggers();	
				self::createOTAllowanceTable();
				self::createBreaktimeScheduleTable();
				self::createBreaktimeScheduleTable();
				self::createSprintVariablesDefaultWorkingDays();
				self::createSprintVariablesCETASEA();	
				self::employeeBenefits();
				self::sprintVariablesCustomValue();	
				self::sprintVariablesDefaultCustomValue();
				self::defaultSettingsLeaveGeneral();
				self::loadDefaultPayslipTemplates();
				self::updateOTNDRate();				
				break;
			default:
				# code...
				break;
		}
	}

	public function loadDefaultValuesStartVersion()
	{
		//Company Structure	   		
			$csid = self::loadDefaultCompanyStructure();			
			$c 	  = G_Company_Structure_Finder::findById($csid);			
	   //
	    
	   //Company Info
	   		self::loadDefaultCompanyInfo($c);
	   //
	   
	   //Company Branch
	   		$bid = self::loadDefaultCompanyBranch($c);
			$b   = G_Company_Branch_Finder::findById($bid);
	   //

	   //Department	   
	   		self::loadDefaultCompanyDepartment($c,$b);
	   //
	   
	   //Loan Deduction Type
	   		self::loadDefaultSettingsDeductionType($c);
	   //
	   
	   //Loan Type
	   		self::loadDefaultSettingsLoanType($c);
	   //
	   
	   //Application Status
	   		self::loadDefaultSettingsApplicationStatus($c);
	   //
	   
	   //Salutation
	   		self::loadDefaultSettingsSalutation($c);
	   //
	   
	   //Employment Status
	   		self::loadDefaultSettingsEmploymentStatus($c);
	   //

	   //Employee Status
	   		self::loadDefaultSettingsEmployeeStatus($c);
	   //

	   //Requirements
	   		self::loadDefaultSettingsRequirements($c);
	   //

	   //Memo
	   		self::loadDefaultSettingsMemo();
	   //

	   //Deduction Breakdown
	   		self::loadDefaultSettingsDeductionBreakdown();
	   //
	   
	   //Leave Type
	   		self::loadDefaultSettingsLeaveType($c);
	   //
	   
	   //Licenses
	   		self::loadDefaultSettingsLicenses($c);
	   //
	   
	   //Pay Period
	   		self::loadDefaultSettingsPayPeriod($c);
	   //

	   	//Cutoff Periods
	   		//self::loadDefaultCutoffPeriods();
	   	//
		
		//Jobs
			self::loadDefaultSettingsJob($c);
		//
		
		//Skills
			self::loadDefaultSettingsSkills($c);
		//
		
		//Subdivision Type
			self::loadDefaultSettingsSubdivisionType($c);
		//
		
		//Language
			self::loadDefaultSettingsLanguages($c);
		//
		
		//EEO Job Category
			self::loadDefaultSettingsEeoJobCategory($c);
		//
		
		//Job Salary Rate
			self::loadDefaultSettingsJobSalaryRate($c);
		//
		
		//Dependent Relationship
			self::loadDefaultSettingsDependentRelationship($c);
		//
		
		//Location
			self::loadDefaultSettingsLocations($c);
		//
		
		//Schedule Group
			$schedule_group_id = self::loadDefaultSettingsScheduleGroup($c);
			$sg 			   = G_Schedule_Group_Finder::findById($schedule_group_id);
		//
		
	   //Schedule
	   		self::loadDefaultSettingsSchedule($c,$sg);
	   //
	   
	    //Grace Period
	   		self::loadDefaultSettingsGracePeriod($c);
	   // 

   		//Employee
	   		self::loadDefaultSettingsEmployee($c);
	   //

	   	//User
	   		self::loadDefaultSettingsUser($c);
	   //

	   //Holiday
	   		self::loadDefaultSettingsHoliday();
	   //
	   
	   //Payroll Period
	   		self::loadDefaultSettingsPayrollPeriod($c);
	   //
	   
	    //Deductions Table
	   	//Philhealth
	   		self::loadDefaultPhilhealthTable();
	   	//

		//SSS
	   		self::loadDefaultSssTable();
	   	//

	   	//Pagibig
	   		self::loadDefaultPagibigTable($c);
	   	//

	   return true;
	}
	
	public function loadDefaultValues()
	{
		//Company Structure	   		
			$csid = self::loadDefaultCompanyStructure();			
			$c 	  = G_Company_Structure_Finder::findById($csid);			
	   //
	    
	   //Company Info
	   		self::loadDefaultCompanyInfo($c);
	   //
	   
	   //Company Branch
	   		$bid = self::loadDefaultCompanyBranch($c);
			$b   = G_Company_Branch_Finder::findById($bid);
	   //

	   //Department	   
	   		self::loadDefaultCompanyDepartment($c,$b);
	   //
	   
	   //Loan Deduction Type
	   		self::loadDefaultSettingsDeductionType($c);
	   //
	   
	   //Loan Type
	   		self::loadDefaultSettingsLoanType($c);
	   //
	   
	   //Application Status
	   		self::loadDefaultSettingsApplicationStatus($c);
	   //
	   
	   //Salutation
	   		self::loadDefaultSettingsSalutation($c);
	   //
	   
	   //Employment Status
	   		self::loadDefaultSettingsEmploymentStatus($c);
	   //

	   //Employee Status
	   		self::loadDefaultSettingsEmployeeStatus($c);
	   //

	   //Requirements
	   		self::loadDefaultSettingsRequirements($c);
	   //

	   //Memo
	   		self::loadDefaultSettingsMemo();
	   //

	   //Deduction Breakdown
	   		self::loadDefaultSettingsDeductionBreakdown();
	   //
	   
	   //Leave Type
	   		self::loadDefaultSettingsLeaveType($c);
	   //
	   
	   //Licenses
	   		self::loadDefaultSettingsLicenses($c);
	   //
	   
	   //Pay Period
	   		self::loadDefaultSettingsPayPeriod($c);
	   //
		
		//Jobs
			self::loadDefaultSettingsJob($c);
		//
		
		//Skills
			self::loadDefaultSettingsSkills($c);
		//
		
		//Subdivision Type
			self::loadDefaultSettingsSubdivisionType($c);
		//
		
		//Language
			self::loadDefaultSettingsLanguages($c);
		//
		
		//EEO Job Category
			self::loadDefaultSettingsEeoJobCategory($c);
		//
		
		//Job Salary Rate
			self::loadDefaultSettingsJobSalaryRate($c);
		//

		//Employee Benefits
			self::loadDefaultSettingEmployeeBenefits($c);
		//
		
		//Dependent Relationship
			self::loadDefaultSettingsDependentRelationship($c);
		//
		
		//Location
			self::loadDefaultSettingsLocations($c);
		//
		
		//Schedule Group
			$schedule_group_id = self::loadDefaultSettingsScheduleGroup($c);
			$sg 			   = G_Schedule_Group_Finder::findById($schedule_group_id);
		//
		
	   //Schedule
	   		self::loadDefaultSettingsSchedule($c,$sg);
	   //
	   
	    //Grace Period
	   		self::loadDefaultSettingsGracePeriod($c);
	   // 

   		//Employee
	   		self::loadDefaultSettingsEmployee($c);
	   //

	   	//User
	   		self::loadDefaultSettingsUser($c);
	   //

	   //Holiday
	   		self::loadDefaultSettingsHoliday();
	   //
	   
	   //Payroll Period
	   		self::loadDefaultSettingsPayrollPeriod($c);
	   //
	   
	    //Deductions Table
	   	//Philhealth
	   		self::loadDefaultPhilhealthTable();
	   	//

		//SSS
	   		self::loadDefaultSssTable();
	   	//

	   	//Pagibig
	   		self::loadDefaultPagibigTable($c);
	   	//
	   
	    //Payroll default variables
	   		self::loadDefaultPayrollSettings();
	   	//
	   
	   //Employee
	   		/*$sql = 'TRUNCATE TABLE ' . EMPLOYEE;		
			Model::runSql($sql);*/
	   //
	   
	   return true;
	}
}
?>