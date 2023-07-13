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
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		$this->var['company_structure_id'] =  $this->session->get('company_structure_id');
		$this->var['username'] =  $this->session->get('username');
		$this->var['employee_id'] =  $this->session->get('employee_id');
		
	
		$this->var['page_title'] = 'Login';
		$this->view->setTemplate('login.php');
		$this->view->render('login/index.php',$this->var);
		
	}
	
	function _login()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];
		$sql = "SELECT * FROM g_user WHERE username=".Model::safeSql($username)." ";
		$result = Model::runSql($sql,true);
		if(!$result){
			echo 0;
		} else {
			$md5pass = md5($password);
			$shapass = hash("SHA512", $password, false);
			if($result[0]['password']== $shapass){
			// User's pass has been updated and is correct with pass in database.
			// Run whatever they needed to login for
				$e = G_Employee_Helper::findByEmployeeId($result[0]['employee_id']);
				$this->session->set('company_structure_id', $result[0]['company_structure_id']);
				$this->session->set('username',  $result[0]['username']);
				$this->session->set('employee_id',Utilities::encrypt($result[0]['employee_id']));
				echo 1;
			} else if($result[0]['password'] == $md5pass){
			// User is correct, but his password has not been updated
			// Update his password
			// No fancy spanshy result checking, because they can still login even if the update fails.
				$sql = "UPDATE g_user SET password='$shapass' WHERE username='$username'";
				Model::runSql($sql);
				// Run your login stuff.
				$e = G_Employee_Helper::findByEmployeeId($result[0]['employee_id']);
				//$this->session = new WG_Session(array('namespace' => 'hr'));
				$this->session->set('company_structure_id', $result[0]['company_structure_id']);
				$this->session->set('username',  $result[0]['username']);
				$this->session->set('employee_id',Utilities::encrypt($result[0]['employee_id']));
				echo 1;
			} else {
			// User's pass is incorrect.
			 	echo 0;
			}	
		}	
	}
	
	function _isLogin()
	{
		$return = false;

		if ($this->session->get('company_structure_id') && $this->session->get('username') && $this->session->get('employee_id') )
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