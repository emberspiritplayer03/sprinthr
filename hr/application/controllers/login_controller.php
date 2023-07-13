<?php
class Login_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainUtilities();
		Loader::appStyle('style.css');

		
	}

	function index()
	{	
	
		echo "<pre>";
		//print_r($_SESSION);
		echo "</pre>";
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['company_structure_id'] =  $_SESSION['hr']['company_structure_id'];
		$this->var['username'] =  $_SESSION['hr']['username'];
		$this->var['employee_id'] =  $_SESSION['hr']['employee_id'];
		
	
		$this->var['page_title'] = 'Login';
		$this->view->setTemplate('login.php');
		$this->view->render('login/index.php',$this->var);
		
	}
	
	function _login()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		echo G_User_Helper::findByUsernamePassword($username,$password);
		
	}
	
	function _isLogin()
	{
		$return = false;

		if ($_SESSION['sprint_hr']['company_structure_id'] && $_SESSION['sprint_hr']['username'] && $_SESSION['sprint_hr']['employee_id'] && $_SESSION['sprint_hr']['hash'])
		{
			$return = true;
		}
		return $return;
	}
	
	function logout()
	{
		$this->session->removeAll();
		redirect('login');	
	}
	
}
?>