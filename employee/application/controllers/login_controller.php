<?php
class Login_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appMainUtilities();
		Loader::appStyle('style.css');
		$this->module = 'LOGIN';
		
	}

	function index()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();

		if( $this->user_session_files ){

			$total_module = 0;

			if( is_array($this->global_user_employee_actions) ){								
				$total_module++;
				$redirect_url = url('dashboard');
			}
			
			if( $total_module == 1 ){
				header("Location:{$redirect_url}");
			}else{
				$this->logout();
				$view_file = "index.php";
			}
		}else{
			$view_file = "index.php";
		}

		$this->view->setTemplate('login.php');
		$this->view->render('login/' . $view_file, $this->var);	
	}

	function _login()
	{
		$username = $_POST['username'];
		$password = $_POST['password'];

		$u = new G_Employee_User();
		$u->setUserName($username);
		$u->setPassword($password);
		$json = $u->login();

		$err_image = BASE_FOLDER . 'themes/' . THEME . '/themes-images/login/error.png';
		if( !$json['is_success'] ){
			$json['message'] = '
					<div class="alert alert-block alert-error fade in">
						<button class="close" data-dismiss="alert" type="button" onclick="javascript:hideAlertBox();">×</button>
						<h4 class="alert-heading">
								<img src="' . $err_image . '" alt="error" class="error_icon" />Invalid Username or Password							
						</h4>
					</div>';
		}

		
		echo json_encode($json);
	}
	
	function logout()
	{
		$u = new G_Employee_User();
		$u->logout();		
		header('Location:'.url('login'));
	}

	function redirect_login() {

	}

}
?>