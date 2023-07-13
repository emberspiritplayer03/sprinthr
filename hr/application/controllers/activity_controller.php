<?php
class Activity_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();

		Loader::appStyle('style.css');	
		Loader::appMainUtilities();

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'attendance');
		
		$this->eid                  = $this->global_user_eid;
		$this->company_structure_id = $this->global_user_ecompany_structure_id;
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	

        $this->var['departments'] = G_Group_Finder::findAllDepartments();
        $this->var['activity_categories'] = G_Activity_Category_Finder::findAllCategories();
        $this->var['project_sites'] = G_Project_Site::all();
		$this->var['activity_skills'] = G_Activity_Skills_Finder::findAllSkills();
		
		$this->validatePermission(G_Sprint_Modules::HR,'attendance','');
	}
	
//testing
	
	function index()
	{			
		redirect("activity/employee_activities");
	}

    /*
	function project_sites(){

		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		Loader::appMainScript('activity_admin.js');
		Loader::appMainScript('activity_admin_base.js');	

		$this->var['project_sites']   = 'class="selected"';				
		$this->var['module'] 	 = 'activity'; 	
		
		$btn_add_activity_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_activity_form();',
    		'id' 					=> 'add_project_site_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong style="line-height: 15px;">+</strong><b>Add Project Sites</b>'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');
		$this->var['btn_add_project_site'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_activity_config);

		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['page_title']  = 'Project Sites';
		$this->view->setTemplate('template_leftsidebar.php');

		$this->view->render('activity/project_sites/index.php',$this->var);
	}*/


	//employee activities
	function employee_activities()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		Loader::appMainScript('employee_activity_admin.js');
		Loader::appMainScript('employee_activity_admin_base.js');	

		$this->var['employee_activities']   = 'class="selected"';				
		$this->var['module'] 	 = 'activity'; 	
		
		$this->var['eids'] = $_GET['eids'];
		$this->var['group_id'] = (int)$_GET['group_id'];
		
		$btn_add_employee_activity_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_employee_activity_form();',
    		'id' 					=> 'add_activity_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong style="line-height: 15px;">+</strong><b>Add Activity</b>'
    		); 

    	$btn_import_employee_activity_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:importEmployeeActivities();',
    		'id' 					=> 'import_activity',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-arrow-left"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Import Activity'
    		); 


    	$btn_generate_employee_activity_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> '#',
    		'onclick' 				=> 'javascript:generateEmployeeActivities();',
    		'id' 					=> 'generate_activity',
    		'class' 				=> 'add_button pull-right',
    		'icon' 					=> '<i class="icon-plus"></i>',
    		'additional_attribute'	=> '',
    		'caption' 				=> 'Generate Activity'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');
		$this->var['btn_add_employee_activity'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employee_activity_config);
		$this->var['btn_import_employee_activity'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_employee_activity_config);

		$this->var['btn_generate_employee_activity'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_generate_employee_activity_config);

        $this->var['download_url'] = url("activity/download_employee_activities?group_id={$group_id}&download=yes&eids={$eids}");

		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['page_title']  = 'Activities';
        $this->var['sub_title'] = 'Schedule Activities';
		$this->view->setTemplate('template_leftsidebar.php');

		$this->view->render('activity/employee_activities/index.php',$this->var);
	}
	
	function _load_employee_activities_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		$this->view->render('activity/employee_activities/list.php',$this->var);
	}
	
	function _load_server_employee_activities_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		$eids = $_GET['eids'];
		$group_id = $_GET['group_id'];
		$sqlcond = '';

        if( !empty($eids) ){
        	$ids = explode(",", $eids);
        	foreach( $ids as $id ){
        		$a_new_ids[] = Utilities::decrypt($id);
        	}
        	$s_new_ids = implode(",", $a_new_ids);
        	$sqlcond = "e.id IN($s_new_ids)";        	
		}
		
        if( !empty($group_id) && strtolower($group_id) != 'all' ){
			if ($sqlcond != '') {
				$sqlcond = $sqlcond . ' AND ';
			}

        	$sqlcond = $sqlcond . "e.department_company_structure_id = $group_id";        	
		}

		Utilities::ajaxRequest();		
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_ACTIVITIES);
		$dt->setCustomField(array('emp_name' => 'e.firstname,e.lastname', 'project_site' => 'project_site','designation' =>'c.activity_category_name','activity' =>'s.activity_skills_name'));
		$dt->setJoinTable("LEFT JOIN " . EMPLOYEE . " e");			
		$dt->setJoinFields(G_EMPLOYEE_ACTIVITIES . ".employee_id = e.id LEFT JOIN " .G_ACTIVITY_CATEGORY ." c ON " . G_EMPLOYEE_ACTIVITIES . ".activity_category_id = c.id LEFT JOIN " .G_ACTIVITY_SKILLS ." s ON " . G_EMPLOYEE_ACTIVITIES . ".activity_skills_id = s.id");

		if ($sqlcond != '') {
			$dt->setCondition(' ' . $sqlcond);
		}

		$dt->setColumns('date,time_in,time_out');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editEmployeeActivity(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteEmployeeActivityLog(\'e_id\')\"></a></li></ul></div>'));
		}else{
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_employee_activity() 
	{
		sleep(1);
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Schedule Activities';		
		$this->view->render('activity/employee_activities/form/add_form.php',$this->var);		
	}
	
	function ajax_get_employees_autocomplete() 
	{
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Employee_Finder::searchByFirstnameAndLastname($q);
			
			foreach ($employees as $e) {
				$response[] = array(Utilities::encrypt($e->getId()), $e->getFullname(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function ajax_import_employee_activities() 
	{	
		$this->var['action']	 = url('activity/import_employee_activities');			
		$this->view->render('activity/employee_activities/form/ajax_import.php',$this->var);
	}


    //getting generate form
	function ajax_generate_employee_activities() 
	{	
		$this->var['action']	 = url('activity/generate_employee_activities');
		$this->var['frequency'] = G_Frequency_Finder::findAll();
		$this->var['start_year']        = 2015;			
		$this->view->render('activity/employee_activities/form/ajax_generate_activity_form.php',$this->var);
	}

	
	function html_import_employee_activities() {
		$this->view->setTemplate('template_blank.php');
		$this->view->render('activity/employee_activities/html/html_import.php', $this->var);	
	}
	
	function _load_add_category_form()
	{
		$this->var['category_form_action'] = url('activity/_insert_new_category');
		$this->view->noTemplate();
		$this->view->render('activity/employee_activities/form/add_category.php',$this->var);
	}
	
	function _load_category_dropdown()
	{
		sleep(1);
		
        $this->var['categories'] = G_Activity_Category_Finder::findAllCategories();
		
		$this->view->noTemplate();
		$this->view->render('activity/employee_activities/includes/category_dropdown.php',$this->var);	
	}


     //actual generation of activity

	function generate_employee_activities(){

		$frequency_id = $_POST['frequency_id'];
		$cutoff_period = explode('/',$_POST['cutoff_period']);
		$year_tag = $_POST['year'];

		$from = $cutoff_period[0]; //start date
		$to = $cutoff_period[1]; //end date
		
		$generate = G_Generate_activity_logs::generateActivityLogs($from, $to);

	    if($generate == true){

	    	$return['is_imported'] = true;
	    	$return['message'] = 'Employee Activity successfully generated'; 

	    }else{

	    	$return['is_imported'] = false;
	    	$return['message'] = 'Error generating employee activity'; 
	    }

	    echo json_encode($return);

	}



	//delete employee activity log

	public function _delete_employee_activity(){

		$eid = utilities::decrypt($_POST['eid']);

		$employee_activity = G_Employee_Activities_Finder::findById($eid);
		if($employee_activity){
                
                $delete = G_Employee_Activities::delete($eid);

                if($delete > 0){

                	 $return['is_success'] = 1;
                	 $return['message'] = 'Employee activity log successfully deleted';

                }else{

                	 $return['is_success'] = 0;
                	 $return['message'] = 'Error deleting employee activity log';
                }

		}
		else{

			$return['is_success'] = 0;
                	 $return['message'] = 'Employee activity log not found';
		}

		 echo json_encode($return);

	}


		
	function _insert_new_category()
	{
		sleep(1);
		
        $return['is_saved'] = true;
		$return['message'] = 'Designation has been successfully added';

		$activity = G_Activity_Category_Finder::findByName($_POST['activity_category_name']);

		if ($activity) {
			$return['message']  = 'Error: Name already exists.';
			$return['is_saved'] = false;
		}
		else {
			$ea = new G_Activity_Category();  
			$ea->setActivityCategoryName($_POST['activity_category_name']);
			$ea->setActivityCategoryDescription($_POST['activity_category_description']);
			$ea->setDateCreated(date('Y-m-d'));
			$ea->save(); 
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _load_add_activity_form()
	{
		$this->var['activity_form_action'] = url('activity/_insert_new_activity');
		$this->view->noTemplate();
		$this->view->render('activity/employee_activities/form/add_activity.php',$this->var);
	}
	
	function _load_activity_dropdown()
	{
		sleep(1);
		
        $this->var['activities'] = G_Activity_Skills_Finder::findAllSkills();
		
		$this->view->noTemplate();
		$this->view->render('activity/employee_activities/includes/activity_dropdown.php',$this->var);	
	}
		
	function _insert_new_activity()
	{
		sleep(1);
		
        $return['is_saved'] = true;
		$return['message'] = 'Activity has been successfully added';

		$activity = G_Activity_Skills_Finder::findByName($_POST['activity_skills_name']);

		if ($activity) {
			$return['message']  = 'Error: Name already exists.';
			$return['is_saved'] = false;
		}
		else {
			$ea = new G_Activity_Skills();  
			$ea->setActivitySkillsName($_POST['activity_skills_name']);
			$ea->setActivitySkillsDescription($_POST['activity_skills_description']);
			$ea->setDateCreated(date('Y-m-d'));
			$ea->save(); 
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}

	function _check_employee_dtr_logs()
	{
		$activity_date	= (!empty($_POST['activity_date']) ? $_POST['activity_date'] : null);
		$time_in	= (!empty($_POST['time_in']) ? $_POST['time_in'] : null);
		$time_out 	= (!empty($_POST['time_out']) ? $_POST['time_out'] : null);
		$activity_date_out = $activity_date;

        $time_in  = date("H:i:s", strtotime($time_in));
		$time_out = date("H:i:s", strtotime($time_out));
		
		if ($activity_date && Tools::isNightShift($time_out)) {
			$activity_date_out = Tools::getTomorrowDate("{$activity_date} {$time_out}");
		}
		
		$activity_date_time_in = $activity_date . ' ' . $time_in;
		$activity_date_time_out = $activity_date_out . ' ' . $time_out;

        $return['is_valid'] = true;
		$return['message'] = '';
		
		$employee_id  = Utilities::decrypt($_POST['employee_id']);    	
		$e = G_Employee_Finder::findById($employee_id);
		
		if ($e) {
			$return = G_Employee_Activities_Helper::compareActivityToDTR($employee_id, $activity_date_time_in, $activity_date_time_out);
		}
		else{
			$return['is_valid'] = false;
			$return['message']  = 'Error: No Employee found. Activity will not be saved.';
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}

	function _save_employee_activity()
	{
		$project_site_id = (!empty($_POST['project_site_id']) ? $_POST['project_site_id'] : null);
		$category_id = (!empty($_POST['category_id']) ? $_POST['category_id'] : null);
		$activity_id = (!empty($_POST['activity_id']) ? $_POST['activity_id'] : null);
		$activity_date	= (!empty($_POST['activity_date']) ? $_POST['activity_date'] : null);
		$time_in	= (!empty($_POST['time_in']) ? $_POST['time_in'] : null);
		$time_out 	= (!empty($_POST['time_out']) ? $_POST['time_out'] : null);
		$reason 	= (!empty($_POST['reason']) ? $_POST['reason'] : null);
		$activity_date_out = $activity_date;

        $time_in  = date("H:i:s", strtotime($time_in));
		$time_out = date("H:i:s", strtotime($time_out));
		
		if ($activity_date && Tools::isNightShift($time_out)) {
			$activity_date_out = Tools::getTomorrowDate("{$activity_date} {$time_out}");
		}
		
		$activity_date_time_in = $activity_date . ' ' . $time_in;
		$activity_date_time_out = $activity_date_out . ' ' . $time_out;

        $return['is_saved'] = true;
		$return['message'] = 'Employee activity has been successfully added';

		if(Utilities::isFormTokenValid($_POST['token'])) {
			$employee_id  = Utilities::decrypt($_POST['employee_id']);    	
			$e = G_Employee_Finder::findById($employee_id);
			$employee_code = $e->employee_code;
			// var_dump([$employee_code, $activity_date]);
			if ($e) {  

				$project_site = G_Project_Site::find($project_site_id);

				$ea = new G_Employee_Activities();  

				$has_dtr = G_Employee_Activities_Helper::compareActivityToDTR($e->getId(), $activity_date_time_in, $activity_date_time_out);

				if ($has_dtr['is_invalid']) {
						
						$return['message']  = 'Error: Cannot save record. No dtr logs match. ';
						$return['is_saved'] = false;
				}
				else {


					$check_duplicate = G_Employee_Activities_Finder::checkDuplicate($e->getId(), $category_id, $activity_id,$activity_date, $time_in, $activity_date_out, $time_out, $project_site_id);
			
					if($check_duplicate){

						$return['message']  = 'Error: Cannot save record. Activity log already exists. ';
						$return['is_saved'] = false;
					}
					else{

						$ea->setEmployeeId($employee_id);
						$ea->setActivityCategoryId($category_id);
						$ea->setActivitySkillsId($activity_id);
						$ea->setDate($activity_date);
						$ea->setTimeIn($time_in);
						$ea->setDateOut($activity_date_out);
						$ea->setTimeOut($time_out);
						$ea->setReason($reason);
						// $ea->setProjectSiteName($device_name);
						// $ea->setProjectSiteName($e->getCostCenter());
						$ea->setProjectSiteName((isset($project_site) ? $project_site->getName() : '' ));
						$ea->setProjectSiteId(($project_site_id));
						$ea->setDateCreated(date('Y-m-d'));

						$json = $ea->saveActivity(); 

						if( !$json['is_success'] ){
							$return['message']  = 'Error: Cannot save record.';
							$return['is_saved'] = false;
						} 

					}


				}   




			}
			else{
				$return['is_success'] = false;
				$return['message']  = 'Error: No Employee found. Activity will not be saved.';
			}
		}
		else {
			$return['message']  = 'Error: Invalid Token. Activity will not be saved.';
			$return['is_saved'] = false;
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function import_employee_activities()
	{
		ob_start();
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file = $_FILES['employee_activities_file']['tmp_name'];
		
		$employee_activities = new G_Employee_Activities_Import($file);
		$is_imported = $employee_activities->import();	
			
		$message = '';
		
		if ($is_imported) {
			$return['is_imported'] = true;
			$message = 'Employee Activities has been successfully imported.';	

			if (count($employee_activities->errors) > 0) {
				$message =  $employee_activities->imported_records. ' of '.$employee_activities->total_records .' records has been successfully imported.';
				$message .= '<br> '. $employee_activities->error_employee_code.' error(s) found:<br> ';	

				foreach($employee_activities->errors as $key => $value) {
					$message .= $value . '<br>';
				}
			}
		}
		else {
			$return['is_imported'] = false;
			$message    = 'There was a problem importing Employee Activities. Please contact the administrator.';
		}

		$return['message'] = $message;

		ob_clean();
		ob_end_flush();
		echo json_encode($return);		
	}
	
	function ajax_edit_employee_activity() 
	{
		$employee_activity = G_Employee_Activities_Finder::findById(Utilities::decrypt($_POST['eid']));
		
		if($employee_activity){
			$e = G_Employee_Finder::findById($employee_activity->getEmployeeId());

			if( $e ){
				$this->var['employee_name'] = $e->getLastname() . ', ' . $e->getFirstname();
				$this->var['eid']       = Utilities::encrypt($e->getId());
				$this->var['employee_activity']	= $employee_activity;
				$this->var['token']		= Utilities::createFormToken();
				$this->var['page_title']= 'Edit Employee Activity';		

				$this->view->render('activity/employee_activities/form/ajax_edit_form.php',$this->var);	
			}
			else{
				echo "<div class=\"alert alert-error\">Employee Record not found</div><br />";
			}

		}
		else{
			echo "<div class=\"alert alert-error\">Employee Activity Record not found</div><br />";
		}
	}

	function _update_employee_activity()
	{
		Utilities::verifyFormToken($_POST['token']);	

		if($_POST['employee_activity_id'] && $_POST['employee_id']){
			$employee_activity = G_Employee_Activities_Finder::findById(Utilities::decrypt($_POST['employee_activity_id']));

			if( $employee_activity )
			{
 				$e = G_Employee_Finder::findById($employee_activity->getEmployeeId());	

				$category_id = (!empty($_POST['category_id']) ? $_POST['category_id'] : null);
				$activity_id = (!empty($_POST['activity_id']) ? $_POST['activity_id'] : null);
				$activity_date	= (!empty($_POST['activity_date']) ? $_POST['activity_date'] : null);
				$project_site_id	= (!empty($_POST['project_site_id']) ? $_POST['project_site_id'] : null);
				$time_in	= (!empty($_POST['time_in']) ? $_POST['time_in'] : null);
				$time_out 	= (!empty($_POST['time_out']) ? $_POST['time_out'] : null);
				$reason 	= (!empty($_POST['reason']) ? $_POST['reason'] : null);

				$time_in  = Tools::convert12To24Hour($time_in);
				$time_out = Tools::convert12To24Hour($time_out);

				$employee_activity->setActivityCategoryId($category_id);
				$employee_activity->setActivitySkillsId($activity_id);
				$employee_activity->setDate($activity_date);
				$employee_activity->setTimeIn($time_in);
				$employee_activity->setTimeOut($time_out);
				$employee_activity->setReason($reason);
				$employee_activity->setProjectSiteId($project_site_id);


				$project_site = G_Project_Site::find($project_site_id);


				if($project_site)
				{
					$employee_activity->setProjectSiteName($project_site->getName());
				}

				$employee_activity->save();

				$json['is_success'] = 1;			
				$json['message']    = 'Record was successfully saved.';		
			
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found';
			}
		}
		else {
			$json['is_success'] = 0;
			$json['message']    = 'Record not found';
		}
				
		$token = Utilities::createFormToken();
		$json['token'] = $token;		
		$json['id'] = $_POST['employee_activity_id'];		
		echo json_encode($json);
	}

	//activities
	function activities()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		Loader::appMainScript('activity_admin.js');
		Loader::appMainScript('activity_admin_base.js');	

		$this->var['activities']   = 'class="selected"';				
		$this->var['module'] 	 = 'activity'; 	
		
		$btn_add_activity_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_activity_form();',
    		'id' 					=> 'add_activity_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong style="line-height: 15px;">+</strong><b>Add Activity</b>'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');
		$this->var['btn_add_activity'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_activity_config);

		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['page_title']  = 'Activities';
		$this->view->setTemplate('template_leftsidebar.php');

		$this->view->render('activity/activities/index.php',$this->var);
	}
	
	function _load_activities_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		$this->view->render('activity/activities/list.php',$this->var);
	}
	
	function _load_server_activities_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		Utilities::ajaxRequest();		
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_ACTIVITY_SKILLS);
		$dt->setColumns('activity_skills_name,activity_skills_description');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editActivity(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteActivity(\'e_id\')\"></a></li></ul></div>'));
		}else{
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_activity() 
	{
		sleep(1);
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Activity';		
		$this->view->render('activity/activities/form/add_form.php',$this->var);		
	}

	function _save_activity()
	{
		$activity_skills_name = (!empty($_POST['activity_skills_name']) ? $_POST['activity_skills_name'] : null);
		$activity_skills_description = (!empty($_POST['activity_skills_description']) ? $_POST['activity_skills_description'] : null);

        $return['is_saved'] = true;
		$return['message'] = 'Activity has been successfully added';

		if(Utilities::isFormTokenValid($_POST['token'])) {
			$activity = G_Activity_Skills_Finder::findByName($activity_skills_name);

			if ($activity) {
				$return['message']  = 'Error: Name already exists.';
				$return['is_saved'] = false;
			}
			else {
				$ea = new G_Activity_Skills();  
				$ea->setActivitySkillsName($activity_skills_name);
				$ea->setActivitySkillsDescription($activity_skills_description);
				$ea->setDateCreated(date('Y-m-d'));
	
				$json = $ea->saveActivity(); 

				if( !$json['is_success'] ){
					$return['message']  = 'Error: Cannot save record.';
					$return['is_saved'] = false;
				}
			}
		}
		else {
			$return['message']  = 'Error: Invalid Token. Activity will not be saved.';
			$return['is_saved'] = false;
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _delete_activity()
	{
		if($_POST['eid']){
			$activity = G_Activity_Skills_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($activity){
				$employee_activities = G_Employee_Activities_Finder::findByActivityId(Utilities::decrypt($_POST['eid']));
				
				if ($employee_activities) {
					$json['is_success'] = 0;
					$json['message']    = 'Error: This activity has employee';
				}
				else {
					$activity->delete();
					$json['is_success'] = 1;			
					$json['message']    = 'Record was successfully deleted.';
				}
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function ajax_edit_activity() 
	{
		$activity = G_Activity_Skills_Finder::findById(Utilities::decrypt($_POST['eid']));
		
		if($activity){
			$this->var['activity']	= $activity;
			$this->var['token']		= Utilities::createFormToken();
			$this->var['page_title']= 'Edit Activity';		
			$this->view->render('activity/activities/form/ajax_edit_form.php',$this->var);		
		}
		else{
			echo "<div class=\"alert alert-error\">Activity Record not found</div><br />";
		}
	}

	function _update_activity()
	{
		Utilities::verifyFormToken($_POST['token']);	

		if($_POST['activity_id']){
			$activity = G_Activity_Skills_Finder::findById(Utilities::decrypt($_POST['activity_id']));

			if( $activity ){	
				$activity_skills_name = (!empty($_POST['activity_skills_name']) ? $_POST['activity_skills_name'] : null);
				$activity_skills_description = (!empty($_POST['activity_skills_description']) ? $_POST['activity_skills_description'] : null);

				$where = array();
				$where[] = array(
					'column' => 'id',
					'operator' => '!=',
					'value' => Utilities::decrypt($_POST['activity_id'])
				);

				$check_activity_exists = G_Activity_Skills_Finder::findByName($activity_skills_name, $where);

				if ($check_activity_exists) {
					$json['message']  = 'Error: Name already exists.';
					$json['is_saved'] = false;
				}
				else {
					$activity->setActivitySkillsName($activity_skills_name);
					$activity->setActivitySkillsDescription($activity_skills_description);        		
					$activity->save();            		
	
					$json['is_success'] = 1;			
					$json['message']    = 'Record was successfully saved.';		
				}
			
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found';
			}
		}
		else {
			$json['is_success'] = 0;
			$json['message']    = 'Record not found';
		}
				
		$token = Utilities::createFormToken();
		$json['token'] = $token;		
		$json['id'] = $_POST['activity_id'];		
		echo json_encode($json);
	}


	//activity reports
	function reports()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

	    Loader::appMainScript('employee_activity_admin.js');
		Loader::appMainScript('employee_activity_admin_base.js');	

		$this->var['download_activity']   = 'class="selected"';				
		$this->var['module'] 	 = 'activity'; 	

		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();		
        
        $employee_access = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

        if($employee_access == Sprint_Modules::PERMISSION_05) {
			$is_with_confi_nonconfi_option = true;
		}else{
			$is_with_confi_nonconfi_option = false;
		}
		
		$this->var['project_sites'] = G_Project_Site::all();
		$this->var['start_year']        = 2015;
		$this->var['is_with_confi_nonconfi_option'] = $is_with_confi_nonconfi_option;   
        $this->var['action'] 		    = 'activity/download_activity_reports';
		$this->var['page_title']  = 'Activities Reports';
		$this->var['title'] 		    = "Download Activity";
		$this->view->setTemplate('template_leftsidebar.php');
		$this->view->render('activity/reports/index.php',$this->var);
	}



	//download report activity

	function download_activity_reports() {
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		$data = $_POST;		
		$frequency_id        = $data['frequency_id'];
		$a_periods = explode("/", $data['cutoff_period']);
		$from      = trim($a_periods[0]);
		$to        = trim($a_periods[1]);

		$project_site_id = $data['project_site_id'];
		if($project_site_id == 'all'){
			$activity = G_Employee_Activity_Attendance_Finder::findActivityByDateFromTo($frequency_id,$from,$to);
		}
		else{
			$activity = G_Employee_Activity_Attendance_Finder::findActivityByDateFromToAndProjectSiteId($frequency_id,$from,$to,$project_site_id);
		}

		foreach($activity as $dkey => $d) {
		        $report_data[$d['employee_id']][$d['project_site_id']][] = $d;
		}		


		//Utilities::displayArray($report_data);exit();

		$this->var['header1']   = G_Company_Structure_Helper::sqlCompanyName();
		$this->var['header2']   = "Activity Report: {$from} - {$to}";
		$this->var['data'] = $report_data;
		$this->var['from'] = $from;
		$this->var['to'] = $to;
		if($data['report_type'] == 'summarized'){
			$this->view->render('activity/reports/form/activity_report.php', $this->var);
		}
		else{
			$this->view->render('activity/reports/form/activity_report_detailed.php', $this->var);
		}
 		
	}



	//designations
	function designations()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		
		$enable_next_previous_link = false;

		Loader::appMainScript('jquerytimepicker/jquery.timepicker.min.js');
		Loader::appMainStyle('jquerytimepicker/jquery.timepicker.css');

		Loader::appMainScript('designation_admin.js');
		Loader::appMainScript('designation_admin_base.js');	

		$this->var['designations']   = 'class="selected"';				
		$this->var['module'] 	 = 'activity'; 	
		
		$btn_add_designation_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'attendance',
    		'child_index'			=> 'activity',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:show_add_designation_form();',
    		'id' 					=> 'add_designation_button',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute'	=> '',
    		'caption' 				=> '<strong style="line-height: 15px;">+</strong><b>Add Designation</b>'
    		); 
		
		$this->var['permission_action'] 	= $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');
		$this->var['btn_add_designation'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_designation_config);

		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();
		$this->var['page_title']  = 'Designations';
		$this->view->setTemplate('template_leftsidebar.php');

		$this->view->render('activity/designations/index.php',$this->var);
	}
	
	function _load_designations_list_dt() 
	{
		$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		$this->view->render('activity/designations/list.php',$this->var);
	}
	
	function _load_server_designations_list_dt() 
	{
		$permission_action = $this->validatePermission(G_Sprint_Modules::HR,'attendance','activity');

		Utilities::ajaxRequest();		
		
		$dt = new Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_ACTIVITY_CATEGORY);
		$dt->setColumns('activity_category_name,activity_category_description');
		$dt->setOrder('ASC');
		$dt->setStartIndex(0);
		$dt->setSort(0);
		if($permission_action == Sprint_Modules::PERMISSION_02) {
			$dt->setNumCustomColumn(1);
				$dt->setCustomColumn(	
				array(
				'1' => '<div class=\"i_container\"><ul class=\"dt_icons\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editDesignation(\'e_id\');\"></a></li><li><a title=\"Delete\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:deleteDesignation(\'e_id\')\"></a></li></ul></div>'));
		}else{
			$dt->setNumCustomColumn(0);
		}
		echo $dt->constructDataTable();
	}
	
	function ajax_add_new_designation() 
	{
		sleep(1);
		$this->var['token']		 = Utilities::createFormToken();		
		$this->var['page_title'] = 'Activity';		
		$this->view->render('activity/designations/form/add_form.php',$this->var);		
	}

	function _save_designation()
	{
		$activity_category_name = (!empty($_POST['activity_category_name']) ? $_POST['activity_category_name'] : null);
		$activity_category_description = (!empty($_POST['activity_category_description']) ? $_POST['activity_category_description'] : null);

        $return['is_saved'] = true;
		$return['message'] = 'Designation has been successfully added';

		if(Utilities::isFormTokenValid($_POST['token'])) {
			$activity = G_Activity_Category_Finder::findByName($activity_category_name);

			if ($activity) {
				$return['message']  = 'Error: Name already exists.';
				$return['is_saved'] = false;
			}
			else {
				$ea = new G_Activity_Category();  
				$ea->setActivityCategoryName($activity_category_name);
				$ea->setActivityCategoryDescription($activity_category_description);
				$ea->setDateCreated(date('Y-m-d'));
	
				$json = $ea->saveDesignation(); 

				if( !$json['is_success'] ){
					$return['message']  = 'Error: Cannot save record.';
					$return['is_saved'] = false;
				}
			}
		}
		else {
			$return['message']  = 'Error: Invalid Token. Designation will not be saved.';
			$return['is_saved'] = false;
		}

		$token = Utilities::createFormToken();
		$return['token'] = $token;
		echo json_encode($return);
	}
	
	function _delete_designation()
	{
		if($_POST['eid']){
			$designation = G_Activity_Category_Finder::findById(Utilities::decrypt($_POST['eid']));
			if($designation){
				$employee_activities = G_Employee_Activities_Finder::findByDesignationId(Utilities::decrypt($_POST['eid']));
				
				if ($employee_activities) {
					$json['is_success'] = 0;
					$json['message']    = 'Error: This designation has employee';
				}
				else {
					$designation->delete();
					$json['is_success'] = 1;			
					$json['message']    = 'Record was successfully deleted.';
				}
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found...';
			}
		}else{
			$json['is_success'] = 0;
			$json['message']    = 'Error in sql';
		}
		
		$json['eid'] = $_POST['eid'];
		echo json_encode($json);
	}
	
	function ajax_edit_designation() 
	{
		$designation = G_Activity_Category_Finder::findById(Utilities::decrypt($_POST['eid']));
		
		if($designation){
			$this->var['designation']	= $designation;
			$this->var['token']		= Utilities::createFormToken();
			$this->var['page_title']= 'Edit Designation';		
			$this->view->render('activity/designations/form/ajax_edit_form.php',$this->var);		
		}
		else{
			echo "<div class=\"alert alert-error\">Designation Record not found</div><br />";
		}
	}

	function _update_designation()
	{
		Utilities::verifyFormToken($_POST['token']);	

		if($_POST['designation_id']){
			$designation = G_Activity_Category_Finder::findById(Utilities::decrypt($_POST['designation_id']));

			if( $designation ){	
				$activity_category_name = (!empty($_POST['activity_category_name']) ? $_POST['activity_category_name'] : null);
				$activity_category_description = (!empty($_POST['activity_category_description']) ? $_POST['activity_category_description'] : null);

				$where = array();
				$where[] = array(
					'column' => 'id',
					'operator' => '!=',
					'value' => Utilities::decrypt($_POST['designation_id'])
				);

				$check_designation_exists = G_Activity_Category_Finder::findByName($activity_category_name, $where);

				if ($check_designation_exists) {
					$json['message']  = 'Error: Name already exists.';
					$json['is_saved'] = false;
				}
				else {
					$designation->setActivityCategoryName($activity_category_name);
					$designation->setActivityCategoryDescription($activity_category_description);        		
					$designation->save();            		
	
					$json['is_success'] = 1;			
					$json['message']    = 'Record was successfully saved.';		
				}
			
			}else{
				$json['is_success'] = 0;
				$json['message']    = 'Record not found';
			}
		}
		else {
			$json['is_success'] = 0;
			$json['message']    = 'Record not found';
		}
				
		$token = Utilities::createFormToken();
		$json['token'] = $token;		
		$json['id'] = $_POST['designation_id'];		
		echo json_encode($json);
	}



	

}
?>