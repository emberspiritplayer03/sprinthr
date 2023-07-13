<?php
// Script Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Benchmark_Revina_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appUtilities();
		//Loader::appScript('settings.js');
		//Loader::appScript('settings_base.js');
		//Loader::appScript('startup.js');
		Loader::appStyle('style.css');
		//Loader::appScript('jquerytimepicker/jquery.timepicker.min.js');
		//Loader::appStyle('jquerytimepicker/jquery.timepicker.css');
		//$this->c_date  = Tools::getCurrentDateTime('Y-m-d h:i:s','Asia/Manila');
		//$this->var['settings'] = 'current';
		//$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];
	}

	function index()
	{	
		$c_structure = G_Company_Structure_Finder::findById(6);
		echo '<pre>';
		print_r($c_structure);	
	}
	
	function add_policy()
	{
		$t = new G_Settings_Policy();
		$t->setPolicy("File OT when later cccc");
		$t->setDescription("Settings for filing of OT when late cccc ccc");
		$t->setIsActive("Yes");
		$t->save();			
	}
	
	function update_policy()
	{
		$u = G_Settings_Policy_Finder::findById(1);
		$u->setPolicy("test edit");
		$u->setDescription("Test Edit");
		$u->setIsActive("No");
		$u->save();			
	}
	
	function find_policy()
	{
		$p = G_Settings_Policy_Finder::findById(1);
		
		echo '<pre>';
		print_r($p);
		echo '</pre>';
	}
	
	function delete_policy()
	{
		$p = G_Settings_Policy_Finder::findById(1);
		$p->delete();
	}
	
	function view_ot_policy()
	{
		$policy = new G_Settings_Policy();
		$policy->OvertimePolicyWhenLate();
		
		if($policy->OvertimePolicyWhenLate() == G_Settings_Policy::IS_ACTIVATED){
			echo 'This is activated';			
		}else{
			echo 'Not Activated';	
		}
	}
	
	function view_exam_details() {
		echo 'test exam details<hr />';
		$a_id = 1;
		$job_id = "rPn_n_eXTu4htnooJyAx-2NRCcVhfRIF0rvjqQz3q-I";
		$exams = G_Exam_Finder::findAllExamByJobIdAndApplyToAllJobs(Utilities::decrypt($job_id));		
		echo '<pre>';
		print_r($exams);
		echo '</pre>';		
		
		//$exam_details = $e->sendExaminationToApplicant('rPn_n_eXTu4htnooJyAx-2NRCcVhfRIF0rvjqQz3q-I',$a_id);
		//echo '<pre>';
		//print_r($exam_details);
		//echo '</pre>';					
	}
	
	function test()
	{
		echo $GLOBALS['hr']['audit_trail']['employee']['employee_add_new'];
	}
	
	function create_password()
	{
		$_POST['new_password'] = 'abc';
		$new_password = Utilities::encrypt($_POST['new_password']);
		echo $new_password;
	}
	
	function test_audit_trail()
	{	
		$at = new G_Audit_Trail();
		$user_info = $at->getUserIPAndCountry();
		$at->setUser('bryann with ip');
		$at->setAction('test logon');
		$at->setEventStatus(G_Audit_Trail::FAIL);
		$at->setDetails('This is the details');
		$at->setAuditDate('audit date');
		$at->setIpAddress($user_info['ip']);	
		$at->save();
	}
	
	function test_audit_trail_delete()
	{
		$d = G_Audit_Trail_Finder::findById(1);
		echo $d->delete();
	}
	
	function test_audit_trail_update()
	{
		$s = G_Audit_Trail_Finder::findById(2);
		$s->setUser('bryannx');
		$s->setAction('test logonx');
		$s->setEventStatus('successx');
		$s->setDetails('This is the detailsx');
		$s->setAuditDate('audit datex');
		$s->setIpAddress('123.387.64x');	
		$s->save();
	}
	
	function test_trigger_audit()
	{
		echo 'TEST TRIGGER AUDIT';
		echo '<hr />';
		
		$audit = new Sprint_Audit();			
		$audit->setUser('Darnley Canog');
		$audit->setAction('Main Login');
		$audit->setDetails(MAIN_LOGIN);
		$audit->triggerAudit(1); // 0 = fail, 1=success
	}
	
	function test_add_employee_deductions()
	{
		$d = new G_Employee_Deductions();
		$d->setEmployeeId(1);
		$d->setCompanyStructureId(50);
		$d->setTitle('xxx');		
		$d->setRemarks('xxx');				
		$d->setAmount(1000);				
		$d->setPayrollPeriodId(25);				
		$d->setApplyToAllEmployee('xxx');				
		$d->setStatus('xxx');				
		$d->setTaxable('xxx');				
		$d->setIsArchive('xxx');					
		$d->setDateCreated('xxx');								
		$d->save();	
	}
	
	function test_view_employee_deductions()
	{
		$d = G_Employee_Deductions_Finder::findById(1);	
		echo '<pre>';
		print_r($d);
		echo '</pre>';
	}
	
	function test_update_employee_deductions()
	{
		$d = G_Employee_Deductions_Finder::findById(1);
		$d->setEmployeeId(2);
		$d->setCompanyStructureId(55);
		$d->setTitle('xxxupdate');		
		$d->setRemarks('xxxupdate');				
		$d->setAmount(9999);				
		$d->setPayrollPeriodId(50);				
		$d->setApplyToAllEmployee('updatexxx');				
		$d->setStatus('xxupdx');				
		$d->setTaxable('xup');				
		$d->setIsArchive('xup');					
		$d->setDateCreated('xupdatexx');								
		$d->save();		
	}
	
	function test_delete_employee_status()
	{
		$d = G_Employee_Deductions_Finder::findById(1);
		$d->delete();
	}

	function testPayslipData()
	{
		$range_custom = array('from' => '2017-01-01', 'to' => '2017-12-31');
		$eid = 22;
		$p_other_labels = G_Payslip_Helper::sqlGetEmployeesPayslipLabels($eid, $range_custom, 2017);

		foreach($p_other_labels as $pkey => $labels) {
			$uns_labels = unserialize($labels['labels']);

			foreach($uns_labels as $uns_data) {
				if($uns_data->getVariable() == 'tax_refund') {
					echo 'bry: ' . $uns_data->getValue();
					echo '<br />';
				}
				echo '<hr />';
			}

			echo '<pre>';
			print_r($uns_labels);
			echo '</pre>';


		}

	}
	
}
?>