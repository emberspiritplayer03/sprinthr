<?php
class Index_Controller extends Controller
{
	function __construct()
	{
		
		parent::__construct();					
		Loader::appStyle('style.css');
	}

	function index()
	{
		$this->view->setTemplate('template_index.php');
		$this->view->render('index/widgets.php',$this->var);
	}
	
	function test() {
		$this->view->setTemplate('template.php');
		$this->view->render('index/index.php',$this->var);
	}
	
	function jquery() {
	
		$this->view->setTemplate('template.php');
		$this->view->render('index/jquery.php');
	}
	
	function date() {
		 $start = '2010-08-09 21:30:24';
		 $end = '2010-08-10 01:00:23';
		
		
		$x = Date::get_time_diff($start,$end);
		print_r($x);
		
	}
	
	function jqueryui() {
		Loader::appStyle('style.css');
		$this->view->setTemplate('template.php');
		$this->view->render('index/ui/index.php');
	}
	
	function _json_get_datatable_info() {
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$letter = $_GET['letter'];	
		$sql = "SELECT * FROM g_user";
	
		$result = mysql_query($sql . " LIMIT $limit");
		$result_count = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$data[] = $row;
		}
	
		$records_returned = count($data);
		$total_records = mysql_num_rows($result_count);
		mysql_free_result($result);
		mysql_free_result($result_count);
		header("Content-Type: application/json");
		echo "{\"recordsReturned\":{$records_returned}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data)  . "}";
	}
	
	function _json_get_datatable_info2() {
		$limit = $_GET['startIndex'] . ', ' . $_GET['results'];
		$letter = $_GET['letter'];	
		$sql = "SELECT * FROM g_user";
	
		$result = mysql_query($sql . " LIMIT $limit");
		$result_count = mysql_query($sql);
		while($row = mysql_fetch_assoc($result)) {
			$data[] = $row;
		}
	
		$records_returned = count($data);
		$total_records = mysql_num_rows($result_count);
		mysql_free_result($result);
		mysql_free_result($result_count);
		header("Content-Type: application/json");
		echo "{\"recordsReturned\":{$records_returned}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data)  . "}";
	}
	
	function _get_autocomplete()
	{
		$q = Model::safeSql(strtolower($_GET["q"]), false);
		if ($q != '') {	
			$sql = "
				SELECT *
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$string .= $record['name'] . '|' . $record['id'] . "\n";
			}
	
			if ($string != '') {
				echo $string;
			} else {
				echo "0";
			}
		}
	}	
	
	function _autocomplete()
	{
		$q = Model::safeSql(strtolower($_GET["term"]), false);
		if ($q != '') {
		$sql = "
				SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array('id'=>$record['id'],'label'=>$record['name']);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		
		header('Content-type: application/json');
		echo json_encode($response);	
		
	}
	
	//this is for employee only
	function _get_names_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		if ($q != '') {
			$sql = "
				SELECT u.id, CONCAT(u.firstname,' ', u.lastname) as name
				FROM g_user u
				WHERE (u.lastname LIKE '%{$q}%' OR u.firstname LIKE '%{$q}%')
				";
			
			$records = Model::runSql($sql, true);
			foreach ($records as $record) {
				$response[] = array($record['id'], $record['name'], null);
			}
		}
		if(count($response)==0)
		{
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
	
	function json_username_check() {
		//print_r($_POST);
		
		$new_username = $_POST['username'];
		
		$isExist = Model::runSql("SELECT * FROM g_user WHERE  username='".$new_username."' ",true);
		if(count($isExist)>0) {
			
			echo "false";
		}else {
			echo "true";	
		}
	}
	
	function _load_user_datatable2() {
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? $_GET['sort'] . ' ' . $_GET['dir']  :  'id asc' ;

		$data =  Employee::findAll($sql,$_GET['fieldname'],$_GET['search'],$limit);
		
		$data2 =  Employee::findAll($sql,$_GET['fieldname'],$_GET['search']);
		$total = count($data);
		$total_records = count($data2);
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function template_form() {
		
		
		$this->view->setTemplate('template.php');
		$this->view->render('index/form_template.php');
		
	}
	
	function editor() {
		
		Loader::appLibrary('ckeditor/ckeditor');
		Loader::appLibrary('ckeditor/ckfinder/ckfinder');

		
		$this->view->setTemplate('template.php');
		$this->view->render('index/editor.php',$this->var);
	}
	
	function scaffold()
	{
		$scaffold = new Scaffold("g_user",2, array('firstname'), FALSE, '850',$foreign_table,$foreign_field,$field_title,$formIdName);
 		//$where = ' WHERE application_status_id<3';
		 $this->var['scaffold'] =  $scaffold->load_scaffolding($msg,$where);
		$this->view->setTemplate('template.php');
		$this->view->render('index/scaffold.php',$this->var);
	}
	
	function csv() 
	{
		$csv = new Csv('g_user','test');
		$csv->execute();	
		
		$calendar = new Calendar($date);
	}
	
	function calendar() {
		Loader::appScript('jim_mayes_calendar.js');
		$this->view->setTemplate('template_test.php');
		$this->view->render('calendar/index.php',$this->var);
	}
	
	function _load_calendar() {
	$date = (!empty($_POST['current_month'])) ? $_POST['current_month'] : date("Y-m-d");
		$calendar = new Calendar($date);
	
			$calendar->highlighted_dates = array(
						'2009-10-03',
						'2009-10-17',
						'2009-10-25'
						);
						
						$calendar->link_days = 2; // highlighted days are allowable to click
			
			$calendar->formatted_link_to = 'javascript:test(/%Y/%m/%d);';
			$calendar->height ='400px';
			$calendar->width ='700px';
			
			$calendar->mark_passed = TRUE; //default true
			$calendar->passedDateBgColor = 'red';
			$calendar->passed_date_class = 'passed';
			
			$calendar->mark_selected = TRUE; //default true
			$calendar->selectedDateBgColor = 'blue';
			$calendar->selected_date_class = 'selected'; //default selected
			
			
			$calendar->highlightedDatesBgColor ='green';
			$calendar->default_highlighted_class = 'highlighted';
			
			$calendar->mark_today = TRUE; //DEFAULT TRUE
			$calendar->todayDateBgColor =	'yellow';
			$calendar->today_date_class = 'today'; //CLASS THAT YOU CAN IMPORT YOUR STYLE //default today
			
			$calendar->week_start = '7'; //sunday
			echo $calendar->output_calendar();
	}
}
?>