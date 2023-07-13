<?php
class Initial_Settings_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		Loader::appStyle('style.css');
		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);
	}

	function index()
	{
		
	}

	function ini_pay_period() {		
		$pp          = G_Settings_Pay_Period_Finder::findDefault();
		if( $pp ){					
			$payoutday     = explode(",", $pp->getPayOutDay());
			$cutoff        = explode(",", $pp->getCutOff());
			$first_cutoff  = explode("-",$cutoff[0]);
			$second_cutoff = explode("-",$cutoff[1]);

			$this->var['payoutday']		    = $payoutday;
			$this->var['first_cutoff']      = $first_cutoff;
			$this->var['second_cutoff']     = $second_cutoff;
			$this->var['pp'] 				= $pp;
			$this->var['action_pay_period'] = url('initial_settings/update_pay_period');
			$this->view->noTemplate();
			$this->view->render('ini_settings/_ajax_ini_pay_period.php',$this->var);  
		}
	}

	function update_pay_period() {
		$return['is_success'] = false;
		$return['message']    = 'Invalid form entry';
		if( !empty($_POST['first_cutoff_a']) && !empty($_POST['first_cutoff_b']) && !empty($_POST['first_cutoff_payday']) && !empty($_POST['second_cutoff_a']) && !empty($_POST['second_cutoff_b']) && !empty($_POST['second_cutoff_payday']) ){
			$data[1]['a']      = $_POST['first_cutoff_a'];
			$data[1]['b']      = $_POST['first_cutoff_b'];
			$data[1]['payday'] = $_POST['first_cutoff_payday'];
			$data[2]['a']      = $_POST['second_cutoff_a'];
			$data[2]['b']      = $_POST['second_cutoff_b'];
			$data[2]['payday'] = $_POST['second_cutoff_payday'];

			$gspp       = G_Settings_Pay_Period_Finder::findById($_POST['pay_period_id']);
			if($gspp){
				$year = date("Y");
				$c = new G_Cutoff_Period();
				//$return = $c->deleteAllByYear($year)->setNumberOfMonths(12)->generateIniCutOffPeriods($data);				
				$return = $c->deleteAllCutOffPeriods()->setNumberOfMonths(12)->generateIniCutOffPeriods($data);				
			}else{
				$return['is_success'] = false;
				$return['message']    = 'Invalid form entry'; 
			}			
		}

		echo json_encode($return);	
	}
}
?>