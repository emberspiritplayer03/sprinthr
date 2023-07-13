<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Audit_Trail_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->audit_login();
		Loader::appScript('audit_trail.js');
		Loader::appStyle('style.css');
		$this->var['page_title'] = 'Audit Trail';
	}

	function index()
	{
		Yui::loadDatatable();
		Jquery::loadTipsy();
		Jquery::loadInlineValidation2();
		Jquery::loadTextBoxList();
		Jquery::loadJqueryFormSubmit();
					
		$this->view->setTemplate('template_audit_trail.php');
		$this->view->render('audit_trail/index.php',$this->var);				
	}
	
	function _json_encode_view_all_audit_trail_list()
	{
		$search = ($_GET['fieldname']  !='') ? "WHERE ". $_GET['fieldname']." like '". $_GET['search'] ."%'": '' ;
		
		$limit = 'LIMIT '.$_GET['startIndex'] . ', ' . $_GET['results'];
		$order_by = ($_GET['sort'] != '') ? $_GET['sort'] . ' ' . $_GET['dir']  :  'id asc' ;
		
		$audit_data = G_Audit_Trail_Finder::findAll();
		foreach ($audit_data as $key=> $object) { 
			$data[] = Tools::objectToArray($object);
		}
		
		$count_total =  G_Audit_Trail_Helper::countTotalRecords();
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _json_encode_view_all_audit_trail_list_search()
	{
		$search['field']  = $_GET['field'];
		$search['search'] = $_GET['search'];
		
		$audit_data = G_Audit_Trail_Finder::findBySearch($search);
		foreach ($audit_data as $key=> $object) { 
			$data[] = Tools::objectToArray($object);
		}
		
		$count_total =  G_Audit_Trail_Helper::countTotalRecords();
		$total = count($data);
		$total_records =$count_total;
		
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
		
	}
	
}
?>