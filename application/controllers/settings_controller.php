<?php
class Settings_Controller extends Controller {
	function __construct() 
	{
		parent::__construct();
		Loader::appUtilities();		
		Loader::appStyle('style.css');
		$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		$this->var['settings'] = 'current';
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}
		
	function index()
	{
		$this->view->render('generic_admin/main.php', $this->var);
	}

	function database()
	{	
		$this->db_admin_login();		
		Loader::appScript('db_settings.js');
		Loader::appScript('version_app.js');
		Loader::appScript('addon_app.js');
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadTipsy();
		
		$t = new Sprint_Tables();
		$this->var['tables'] 	= $t->sqlGetAllTables();

		$addons = new G_Sprint_Add_Ons();
		$addons_data = $addons->getAddOnsList();

		$v = new G_Sprint_Version();
		$versions = $v->getVersionList();
		$data     = $v->getAppVersion();

		if( empty($data) ){				
			$date_updated = date("Y-m-d H:i:s");
			$version = G_Sprint_Version::STARTING_VERSION;			
			$v->updateVersionTextFile($version, $date_updated);
			$data = $v->getAppVersion();
		}		

		$version_part = explode("/", $data);		
		$app_version  = $version_part[0];

		$this->var['addons_data']			= $addons_data;
		$this->var['app_version']           = $app_version;
		$this->var['versions']              = $versions;
		$this->var['show_db_admin_logout'] 	= true;
		$this->var['page_title'] 			= 'Database';
		$this->var['company_structure_sb']	= 'selected';
		$this->var['module_title']			= 'SprintHR Settings';
		$this->view->setTemplate('template_startup.php'); //template_settings
		$this->view->render('settings/database/index.php',$this->var);
		
	}

	function version_updates()
	{
		$v = new G_Sprint_Version();
		$versions = $v->getVersionInfoList();

		Utilities::displayArray($versions);
	}
	
	function policy()
	{
		$this->db_admin_login();
		Loader::appScript('db_settings.js');
		Jquery::loadInlineValidation2();
		Jquery::loadModalExetend();	
		Jquery::loadJqueryFormSubmit();
		Jquery::loadTipsy();		
		
		$this->var['policy'] 					= G_Settings_Policy_Finder::findAll(); 
		$this->var['show_db_admin_logout'] 	= true;
		$this->var['page_title'] 				= 'Policy';
		$this->var['module_title']				= 'SprintHR Settings';
		$this->view->setTemplate('template_startup.php'); //template_settings
		$this->view->render('settings/policy/index.php',$this->var);

	}

	function factory_reset() {
		$this->db_admin_login();	
		$t = new Sprint_Tables();
		$is_success = $t->factoryResetByAppVersion();
		//$this->enable_startup();

		$this->var['is_success']				= $is_success;
		$this->var['page_title'] 				= 'Factory Reset';
		$this->var['module_title']				= 'SprintHR Settings';
		$this->view->setTemplate('template_startup.php'); //template_settings
		$this->view->render('settings/database/success_factory_reset.php',$this->var);
	}

	function update_database()
	{
		$version = $_POST['version'];
		$json['is_success'] = false;
		$json['status']     = '';
		if( !empty($version) ){
			//Upate database
			$db = new Sprint_Tables();			
			$json = $db->updateTablesByAppVersion($version);		

			if( $json['is_success'] ){
				//Update version file
				$date_updated = date("Y-m-d H:i:s");
				$v = new G_Sprint_Version();				
				$v->updateVersionTextFile($version, $date_updated);
			}
		}
		echo json_encode($json);
	}

	function activate_addon()
	{
		$addon = Utilities::decrypt($_POST['addon']);		
		$ad    = new G_Sprint_Add_Ons();
		$json  = $ad->activateAddOn($addon);

		echo json_encode($json);
	}

	function deactivate_addon()
	{
		$addon = Utilities::decrypt($_POST['addon']);		
		$ad    = new G_Sprint_Add_Ons();
		$json  = $ad->deactivateAddOn($addon);

		echo json_encode($json);
	}

	function update_tdd_database()
	{
		$version = $_POST['version'];
		$json['is_success'] = false;
		$json['status']     = '';
		if( !empty($version) ){
			//Upate database
			$db = new Sprint_Tables();			
			$json = $db->updateTablesByAppVersion($version);					
		}

		echo json_encode($json);
	}
	
	function update_policy()
	{			
		if($_POST) {
			$to_update = G_Settings_Policy_Finder::findAll();
			foreach($to_update as $tu):
				$u = G_Settings_Policy_Finder::findById($tu->getId());	
				if($_POST['policy'][$tu->getId()] == G_Settings_Policy::IS_ACTIVATED) {
					$u->setIsActive("Yes");						
				} else {
					$u->setIsActive("No");
				}
				$u->save();
			endforeach;
			$return['is_success'] = 1;
			$return['message']    = '<div class="alert alert-info">Updating policy(s) completed.</div>';						
		} else {
			$return['is_success'] = 2;
			$return['message']    = '<div class="alert alert-block alert-error fade in">Error in SQL.Contact your system administrator.</div>';			
		}
		echo json_encode($return);	
	}	
	
	function truncate_table()
	{
		if($_POST){	
			if($_POST['sprint_tables']){
				$t = new Sprint_Tables($_POST['sprint_tables']);
				$t->truncateSelectedTable();
			}elseif($_POST['truncate_recommended']){
				$t = new Sprint_Tables();
				$t->truncateRecommendedTables();
				$this->enable_startup();
			}elseif($_POST['truncate_all']){
				$t = new Sprint_Tables();
				$t->truncateAllTables();
				$this->enable_startup();
			}elseif($_POST['truncate_recruitment']){
				$t = new Sprint_Tables();
				$t->truncateRecruitmentData();
			}
			
			$return['is_success'] = 1;
			$return['message']    = '<div class="alert alert-info">Truncating table(s) completed.</div>';			
		}else{
			$return['is_success'] = 2;
			$return['message']    = '<div class="alert alert-block alert-error fade in">Error in SQL.Contact your system administrator.</div>';			
		}
		
		echo json_encode($return);
	}
	
	function _load_create_recommended_tables() {
		$t = Sprint_Tables::checkTableIfExists();
	}

	function _load_version_info() {
		$version_number = $_GET['version'];

		$v    = new G_Sprint_Version();
		$info = $v->getVersionInfo($version_number);		
		$this->var['release_date']   = $info['release_date'];
		$this->var['info_new_mod']   = $info['release_info']['new_modules'];		
		$this->var['info_fixes_mod'] = $info['release_info']['fixes_bugs'];	
		$this->var['version']        = $version_number;
		$this->view->render('settings/versions/version_info.php',$this->var);
	}

	function _load_addon_info() {
		$addon = $_GET['addon'];	

		$ad   = new G_Sprint_Add_Ons();
		$info = $ad->getAddOnInfo($addon);	
		$data =  $ad->isAddOnEnabled($addon);
		
		$this->var['addon_enabled']   = G_Sprint_Add_Ons::ENABLED;
		$this->var['is_enabled']      = $data['is_addon_enabled'];
  		$this->var['addon_key']       = Utilities::encrypt($addon);	
		$this->var['released_date']   = $info['released_date'];
		$this->var['features']        = $info['features'];				
		$this->view->render('settings/addons/addon_info.php',$this->var);
	}
	
	function enable_startup()
	{
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/settings/startup.xml';
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2   = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);							
			$obj    = $xmlStr->xpath('//Settings');
			$obj[0]->startup = 'enabled';						
			$xmlStr->asXml($xmlUrl);
		}
	}
	
	function load_default_values()
	{
		$t = new Sprint_Tables();
		$r = $t->loadDefaultValues();
					
		$return['is_success'] = 1;
		$return['message']    = '<div class="alert alert-info">Loading default values completed.</div>';			
		
		echo json_encode($return);
	}
	
	function show_page_one()
	{
		$this->view->noTemplate();
		$this->view->render('generic_admin/page_one.php', $this->var);
	}
	
	function show_page_two()
	{
		$this->view->noTemplate();
		$this->view->render('generic_admin/page_two.php', $this->var);
	}	
}
?>