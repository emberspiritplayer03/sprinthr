<?php
class Evaluation_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainScript('employee.js');
		

		$this->sprintHdrMenu(G_Sprint_Modules::HR, 'employees');
		
		$employee = G_Employee_Finder::findById(Utilities::decrypt($this->global_user_eid));
		if($employee) {
			$position = G_Employee_Job_History_Finder::findCurrentJob($employee);
			if($position){
				$this->h_job_position_id 	= Utilities::encrypt($position->getJobId());
			}else{
				$this->h_job_position_id = 0;
			}
		}
		
		
		$this->h_employee_id   			= $this->global_user_eid; //employee
		$this->h_company_structure_id 	= $this->global_user_ecompany_structure_id;
		$this->c_date  					= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		
		$this->default_method   		= 'index';
		
		$this->var['h_employee_id']  	     = $this->h_employee_id;
		$this->var['h_job_position_id'] 	 = $this->h_job_position_id;	
		$this->var['h_company_structure_id'] = $this->h_company_structure_id;

		Loader::appStyle('style.css');
		$this->var['employee'] 		= 'selected';
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);			
		$this->username 	= $this->global_user_username;
		$this->module 		= 'HR EMPLOYEE';		

		$this->validatePermission(G_Sprint_Modules::HR,'employee_evaluation','');
	}

	function index()
	{
		
		
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		
		$this->var['page_title'] = 'Employees';
		$this->var['token'] = Utilities::createFormToken();
		
		Jquery::loadMainJTags();
		//Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();

		$company_structure_id = $this->company_structure_id;

		$cs = G_Company_Structure_Finder::findById($company_structure_id);

		$btn_import_employee_eval_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_evaluation',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:importEmployeeEvaluation();',
    		'id' 					=> '',
    		'class' 				=> 'gray_button',
    		'icon' 					=> '<i class="icon-user"></i>',
    		'additional_attribute' 	=> '',
    		'caption' 				=> 'Import Employee Evaluation'
    		); 

		$btn_add_employee_eval_config = array(
    		'module'				=> 'hr',
    		'parent_index'			=> 'employees',
    		'child_index'			=> 'employee_evaluation',
    		'href' 					=> 'javascript:void(0);',
    		'onclick' 				=> 'javascript:load_add_employee_eval();',
    		'id' 					=> 'add_employee_button_wrapper',
    		'class' 				=> 'add_button',
    		'icon' 					=> '',
    		'additional_attribute' 	=> '',
    		'caption' 				=> '<strong>+</strong><b>Add Evaluations</b>'
    		); 

		//$sv = new G_Sprint_Variables();
		//$working_days_options = $sv->optionsWorkingDays();

		$count_employee_notifications = G_Notifications_Helper::countTotalEmployeeNotifications();

		$this->var['is_enable_popup_notification']  = true;
		$this->var['count_employee_notifications']  = $count_employee_notifications;  		
    	
    	//$this->var['working_days_options']      = $working_days_options;
    	$this->var['permission_action'] 		= $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_evaluation');
    	$this->var['btn_add_employee'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_add_employee_eval_config);

    	$this->var['btn_import_employee_eval'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_hr_actions, $btn_import_employee_eval_config);
		
		$this->var['page_title'] = 'Employees Evaluations';
		$this->view->setTemplate('template_employee.php');
		$this->view->render('employee/evaluation/index.php',$this->var);
	}



	function ajax_add_evaluation() 
	{	
		$this->var['action']	 = url('evaluation/add_employee_evaluation');			
		$this->view->render('employee/evaluation/form/ajax_evaluation_form.php',$this->var);		
	}

	function ajax_get_employees_autocomplete() {
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


	function _load_employee_evaluation_list_dt() 
	{		
		//$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_evaluation');
			$this->view->render('employee/evaluation/_employee_evaluation_dt.php',$this->var);
	}


	function _load_employee_evaluation_search_list_dt() 
	{		
		//$this->var['permission_action'] = $this->validatePermission(G_Sprint_Modules::HR,'employees','employee_evaluation');
            $this->var['search'] = $_GET['searched'];
			$this->view->render('employee/evaluation/_employee_evaluation_search_dt.php',$this->var);
	}




	function add_employee_evaluation(){

			
				if($_POST){


					$employee_id = $_POST['employee_id'];
					$score = $_POST['score'];
					$evaldate =  date('Y-m-d',strtotime($_POST['evaldate']));
					$nextevaldate = date('Y-m-d',strtotime($_POST['nextevaldate']));
					$dateCreated = date('Y-m-d', strtotime('now'));
					$file  = $_FILES['attachments']['name'];

					$path = BASE_PATH."files/evaluation/".$file;

					
					//var_dump($path);
					//exit;

					$v = new G_Employee_Evaluation;


					$v->setEmployeeId($employee_id);
					$v->setScore($score);
					$v->setEvaluationDate($evaldate);
					$v->setNextEvaluationDate($nextevaldate);
					$v->setDateCreated($dateCreated);
					$v->setAttachment($file);
					$v->setIsArchive(G_Employee_Evaluation::NO);
					$is_saved = $v->save();


					if($is_saved){


						 move_uploaded_file($_FILES['attachments']['tmp_name'], $path);

						
							   $return['is_saved'] = true;
			   		  		   $return['message'] = 'Employee Evaluation has been saved';
						


						
			 			

					}
					else{

						 $return['is_saved'] = false;
			   		     $return['message'] = 'Error in saving new Employee Evaluation';

					}


					 echo json_encode($return);

			}

	}



    function _load_employee_eval_dt()
	{
		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_EVALUATION);
		$dt->setSQL("
				SELECT
				ev.id,
			ev.score as score,
			ev.evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id
			");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_EVALUATION . " c");	
		

		//$dt->setCondition("eob.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND eob.employee_id = e.id");
		$dt->setColumns('branch_name,department,section_name,employee_id,employee_name, position, evaluation_date, score');	
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"></div>',
						2 => '<div class=\"i_container\"><ul class=\"dt_icons\" style=\"display:inline-flex\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editEvaluation(\'e_id\');\"></a></li><li><a title=\"View\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:viewEvaluation(\'e_id\',1);\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveEvaluation(\'e_id\')\"></a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}




function _load_employee_eval_search_dt()
	{

		if($_GET['search']) {
				$search  .= " AND (e.e_is_archive = '" . G_Employee::NO . "')";
				$search .= " AND (e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' )";
					
			}



		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_EVALUATION);
		$dt->setSQL("
				SELECT
				ev.id,
			ev.score as score,
			ev.evaluation_date as evaluation_date,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id = e.id ".$search."
			");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_EVALUATION . " c");	
		

		//$dt->setCondition($search);
		$dt->setColumns('branch_name,department,section_name,employee_id,employee_name, position, evaluation_date, score');	
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"></div>',
						2 => '<div class=\"i_container\"><ul class=\"dt_icons\" style=\"display:inline-flex\"><li><a title=\"Edit\" id=\"edit\" class=\"ui-icon ui-icon-pencil g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:editEvaluation(\'e_id\');\"></a></li><li><a title=\"View\" id=\"edit\" class=\"ui-icon ui-icon-person g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:viewEvaluation(\'e_id\',1);\"></a></li><li><a title=\"Send to Archive\" id=\"delete\" class=\"ui-icon ui-icon-trash g_icon\" href=\"javascript:void(0);\" onclick=\"javascript:archiveEvaluation(\'e_id\')\"></a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();
	}





	function history(){

		$vid = Utilities::decrypt($_GET['evid']);

		$this->var['cur_eval'] = $cur_eval = G_Employee_Evaluation_Finder::findById($vid);

		$this->var['employee_id'] = $employee_id = $cur_eval->getEmployeeId2();

		$employee = G_Employee_Finder::findById($employee_id);

		$this->var['employee_name'] = $employee->getLastName().', '.$employee->getFirstName().' '.$employee->getMiddlename();

		$this->var['all_employee_eval'] = G_Employee_Evaluation_Finder::findByEmployeeId($employee_id);

		//var_dump($this->var['all_employee_eval']);
		//exit();

		//$this->var['page_title'] = 'Employees Evaluations';
		$this->view->setTemplate('template_blank.php.php');
		$this->view->render('employee/evaluation/history.php',$this->var);
	  	        

	}



	function archive_evaluation(){

		if(!empty($_POST)) {
			$gel = G_Employee_Evaluation_Finder::findById(Utilities::decrypt($_POST['e_id']));
			if($gel) {				
				$json['is_success'] = 1;
				$gel->setIsArchive(G_Employee_Evaluation::YES);
				$gel->save();							
			}
		}else{$json['is_success'] = 0;}


		echo json_encode($json);

	}



		function ajax_edit_employee_evaluation() 
		{

			

			$this->var['eval']			 = $eval = G_Employee_Evaluation_Finder::findById(Utilities::decrypt($_POST['evid']));
			$this->var['token']		     = Utilities::createFormToken();		
			$this->var['page_title']     = 'Edit Employee Evaluation';		
			//$this->var['has_started']	 = (strtotime(date("Y-m-d")) >= strtotime($eval->getStartDate()) ? 1 : 0);
			$emp = G_Employee_Finder::findById($eval->getEmployeeId());

			$this->var['emp']	 = 	$emp->getFullname();

			$this->view->render('employee/evaluation/form/edit_evaluation.php',$this->var);
		}


		function edit_employee_evaluation(){

					//$employee_id = Utilities::decrypt($_POST['employee_id']);

					if($_POST){




					$v = G_Employee_Evaluation_Finder::findById($_POST['eval_id']);

					$employee_id = $_POST['employee_id'];
					$score = $_POST['score'];
					$evaldate =  date('Y-m-d',strtotime($_POST['evaldate']));
					$nextevaldate = date('Y-m-d',strtotime($_POST['nextevaldate']));
					$dateCreated = $v->getDateCreated();

					

						if($_FILES['attachments']['name']  != "") {
						    // file field is not empty..
							$file  = $_FILES['attachments']['name'];

							$path = BASE_PATH."files/evaluation/".$file;


						} else {
						    // no file uploaded..

						    $file = $v->gettAttachment();
					
						}

					
					
					
					//var_dump($path);
					//exit;

					//$v = new G_Employee_Evaluation;


					$v->setEmployeeId($employee_id);
					$v->setScore($score);
					$v->setEvaluationDate($evaldate);
					$v->setNextEvaluationDate($nextevaldate);
					$v->setDateCreated($dateCreated);
					$v->setAttachment($file);
					$v->setIsArchive(G_Employee_Evaluation::NO);
					$is_saved = $v->save();


					if($is_saved){

							if($_FILES['attachments']['name'] != "") {
						      
						      unlink( BASE_PATH."files/evaluation/".$_POST['cur_attachment']);

						      move_uploaded_file($_FILES['attachments']['tmp_name'], $path);

						     }

						
							   $return['is_saved'] = true;
			   		  		   $return['message'] = 'Employee Evaluation has been successfully updated';
						


						
			 			

					}
					else{

						 $return['is_saved'] = false;
			   		     $return['message'] = 'Error in saving new Employee Evaluation';

					}


					 echo json_encode($return);



					
					}
				

					
		}



	function import_employee_evaluation_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
		
		$file 	 = $_FILES['attachment']['tmp_name'];

		if (!is_file($file)) {
            echo "Please select a file";
            exit;
        } else {

			$path = $_FILES['attachment']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if($ext != 'xls' && $ext != 'xlsx')
			{
				echo "Invalid file. Allowed file types are (.xls) and (.xlsx).";
				exit;
			}
		}


		$account = new G_Evaluation_Import($file);
		//$account->setCompanyStructureId($this->company_structure_id);
		
		$is_imported = $account->import();		
		
		if ($is_imported) {
			$return['is_imported'] = true;
			$return['message']     = 'Evaluation has been successfully imported.';	
		} else {
			$return['is_imported'] = false;
			$return['message']     = 'There was a problem importing account. Please contact the administrator.';
		}
		echo json_encode($return);		
	}



	function _load_employee_evaluation_history_list_dt(){

		    $this->var['eid'] = $_GET['eid'];
		    $this->var['date'] = $_GET['date'];
			$this->view->render('employee/evaluation/_employee_evaluation_history_dt.php',$this->var);

	}


	function _load_employee_eval_history_dt(){

		$eid = $_GET['eid'];

		if($_GET['date'] == 0){

			$sqladd = '';

		}
		else{

			$sqladd = "AND ev.evaluation_date='".date('Y-m-d',strtotime($_GET['date']))."'";
		}



		Utilities::ajaxRequest();
		$dt = new Main_Datatable();
		$c  = $_GET['iDisplayStart'];
		$dt->setPagination(1);
		$dt->setStart(1);
		$dt->setStartIndex(0);
		$dt->setDbTable(G_EMPLOYEE_EVALUATION);
		$dt->setSQL("
				SELECT
				ev.id,
			ev.score as score,
			ev.evaluation_date as evaluation_date,
			ev.attachments as attachment,
			company_branch.name as  branch_name,
			`d`.`name` AS `department`,
			(SELECT title FROM `g_company_structure` WHERE id = e.section_id LIMIT 1)AS section_name,
			`e`.`employee_code`,
			CONCAT(e.lastname,', ',e.firstname,' ',substring(e.middlename,1,1),'. ', e.extension_name) AS `employee_name`,
			`j`.`name` AS `position`, e.employee_code as employee_id
			
	
			FROM `g_employee_evaluation` AS `ev`
			inner Join `g_employee` AS `e`  ON `e`.`id` = `ev`.`employee_id`
			Left Join `g_employee_subdivision_history` AS `d` ON `ev`.`employee_id` = `d`.`employee_id` AND `d`.`end_date` = ''
			Left Join `g_employee_branch_history` AS `b` ON `ev`.`employee_id` = `b`.`employee_id` AND `b`.`end_date` = ''
			Left Join g_company_branch as company_branch ON b.company_branch_id=company_branch.id
			Left Join `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `ev`.`employee_id` AND `j`.`end_date` = '' )
			
			Inner Join `g_company_structure` AS `company` ON `e`.`company_structure_id` = `company`.`id`
			 
			where ev.is_archive = ". Model::safeSql(G_Employee_Evaluation::NO)." AND ev.employee_id =".$eid." ".$sqladd."
			");		
		$dt->setCountSQL("SELECT COUNT(c.id) as c FROM " . G_EMPLOYEE_EVALUATION . " c");	
		

		//$dt->setCondition($search);
		$dt->setColumns('evaluation_date,score,attachment');	
		$dt->setOrder('ASC');
		$dt->setSort(0);							
		$dt->setCustomColumn(
				array(		
						1 => '<div class=\"i_container\"></div>',
						2 => '<div class=\"i_container\"><ul class=\"dt_icons\" style=\"display:inline-flex\"><li><a title=\"download attachment\" id=\"download\" class=\"ui-icon ui-icon-arrowstop-1-s g_icon\" href=\"'.BASE_FOLDER.'files/evaluation/attachment\" download=\"attachment\"></a></li></ul></div>'
		));
		//echo "<pre>"; print_r($dt);
		echo $dt->constructDataTableRightTools();



	}



	function ajax_create_employee_evaluation_notification() 
		{

			

			$this->var['eval']			 = $eval = G_Employee_Evaluation_Finder::findById(Utilities::decrypt($_POST['evid']));
			$this->var['token']		     = Utilities::createFormToken();		
			$this->var['page_title']     = 'Edit Employee Evaluation';		
			//$this->var['has_started']	 = (strtotime(date("Y-m-d")) >= strtotime($eval->getStartDate()) ? 1 : 0);
			$emp = G_Employee_Finder::findById($eval->getEmployeeId());

			$this->var['emp']	 = 	$emp->getFullname();

			$this->view->render('employee/evaluation/form/create_evaluation_notification.php',$this->var);
		}





		function add_employee_evaluation_notif(){

			
				if($_POST){


					$employee_id = $_POST['employee_id'];
					$score = $_POST['score'];
					$evaldate =  date('Y-m-d',strtotime($_POST['evaldate']));
					$nextevaldate = date('Y-m-d',strtotime($_POST['nextevaldate']));
					$dateCreated = date('Y-m-d', strtotime('now'));
					$file  = $_FILES['attachments']['name'];

					$path = BASE_PATH."files/evaluation/".$file;

					
					//var_dump($path);
					//exit;

					$v = new G_Employee_Evaluation;


					$v->setEmployeeId($employee_id);
					$v->setScore($score);
					$v->setEvaluationDate($evaldate);
					$v->setNextEvaluationDate($nextevaldate);
					$v->setDateCreated($dateCreated);
					$v->setAttachment($file);
					$v->setIsArchive(G_Employee_Evaluation::NO);
					$is_saved = $v->save();


					if($is_saved){


							$is_updated = G_Employee_Evaluation_Manager::updatedFromNotification($_POST['eval_id']);

						     move_uploaded_file($_FILES['attachments']['tmp_name'], $path);

						
							   $return['is_saved'] = true;
			   		  		   $return['message'] = 'Employee Evaluation has been saved';
						


						
			 			

					}
					else{

						 $return['is_saved'] = false;
			   		     $return['message'] = 'Error in saving new Employee Evaluation';

					}


					 echo json_encode($return);

			}

	}



}

?>