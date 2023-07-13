<?php
class Profile_Controller extends Controller
{
	function __construct() {
		parent::__construct();
		$this->isLogin();
		$this->sprintHdrMenu(G_Sprint_Modules::EMPLOYEE, 'employee_profile');
		$this->validatePermission(G_Sprint_Modules::EMPLOYEE,'employee_profile','');		
		Loader::appStyle('style.css');
	}
	
	function index() {		
		$id = Utilities::decrypt($this->global_user_eid);
		$e = G_Employee_Finder::findById($id);		
		if( $e ){
			$contact_details = $e->getContactDetails();			
			$image = $e->getValidEmployeeImage();					
			$this->var['e_photo']    = $image;
			$this->var['e'] 		 = $e;
			$this->var['c']          = $e->getContactDetails();
			$this->var['page_title'] = 'Profile';
			$this->view->setTemplate('template_employee_portal.php');
			$this->view->render('profile/index.php',$this->var);
		}else{
			echo "<div class=\"alert alert-error\">Employee Profile not found!</div><br />";
		}
		
		
	}
}
?>