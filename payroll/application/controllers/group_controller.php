<?php
class Group_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();		
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');
	}

	function index() {}

	function ajax_get_groups_autocomplete() {
		$q = Model::safeSql(strtolower($_GET["search"]), false);
		
		if ($q != '') {
			$employees = G_Group_Finder::searchByGroupName($q);
			
			foreach ($employees as $e) {
				$response[] = array($e->getId(), $e->getName(), null);
			}
		}
		
		if(count($response) == 0) {
			$response = '';
		}
		header('Content-type: application/json');
		echo json_encode($response);		
	}
}
?>