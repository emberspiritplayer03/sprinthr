<?php
class Generic_Admin_Controller extends Controller {
	function __construct() {
		parent::__construct();		
	}
	
	function admin() {
		Loader::appStyle('style_admin.css');
		Loader::appUtilities();
		Loader::includeScript('generic_admin.js');
				
		$sub_nav = '<ul>';
		$sub_nav .= '<li><a onclick="showPageOne();" href="javascript:;">Show page 1</a></li>';
		$sub_nav .= '<li><a onclick="showPageTwo();" href="javascript:;">Show page 2</a></li>';
		$sub_nav .= '</ul>';		
		$this->var['sub_nav_dashboard'] = $sub_nav;				
		
		$this->view->setTemplate('template_generic_admin.php');	
		$this->view->render('generic_admin/main.php', $this->var);
	}
	
	function index() {
		$this->view->render('generic_admin/main.php', $this->var);
	}
	
	function show_page_one() {
		$this->view->noTemplate();
		$this->view->render('generic_admin/page_one.php', $this->var);
	}
	
	function show_page_two() {
		$this->view->noTemplate();
		$this->view->render('generic_admin/page_two.php', $this->var);
	}	
}
?>