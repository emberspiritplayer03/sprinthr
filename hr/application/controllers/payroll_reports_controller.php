<?php
class Payroll_Reports_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		//ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);		
		
		Loader::appMainScript('reports.js');
		Loader::appStyle('style.css');

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'reports');	
		
		Jquery::loadMainInlineValidation2();
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'reports','');

		$this->company_structure_id = Utilities::decrypt($this->global_user_ecompany_structure_id);

	}

	// payroll management menu
	function payroll_management()
	{
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();

		$btn_payslip_config = array(
    		'module'				=> G_Sprint_Modules::PAYROLL,
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'payslip',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#payslip',
    		'onclick' 				=> 'javascript:hashClick("#payslip");',
    		'wrapper_start'			=> '<li id="payslip_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Payslip'
    		);

		$btn_payroll_register_config = array(
    		'module'				=> G_Sprint_Modules::PAYROLL,
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'payroll_register',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#payroll_register',
    		'onclick' 				=> 'javascript:hashClick("#payroll_register");',
    		'wrapper_start'			=> '<li id="payroll_register_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Payroll Register'
    		);

        $btn_cash_file_config = array(
            'module'                => G_Sprint_Modules::PAYROLL,
            'parent_index'          => 'reports',
            'child_index'           => 'cash_file',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#cash_file',
            'onclick'               => 'javascript:hashClick("#cash_file");',
            'wrapper_start'         => '<li id="cash_file_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Cash File'
            );

		$btn_sss_config = array(
    		'module'				=> G_Sprint_Modules::PAYROLL,
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'sss',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#sss_r1a',
    		'onclick' 				=> 'javascript:hashClick("#sss_r1a");',
    		'wrapper_start'			=> '<li id="sss_r1a_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'SSS'
    		);
		
		$btn_philhealth_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'philhealth',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#philhealth',
    		'onclick' 				=> 'javascript:hashClick("#philhealth");',
    		'wrapper_start'			=> '<li id="philhealth_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'PhilHealth'
    		);

		$btn_pagibig_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'pagibig',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#pagibig',
    		'onclick' 				=> 'javascript:hashClick("#pagibig");',
    		'wrapper_start'			=> '<li id="pagibig_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Pagibig'
    		);

		$btn_tax_config = array(
    		'module'				=> 'payroll',
    		'parent_index'			=> 'reports',
    		'child_index'			=> 'tax',
    		'required_permission'		=> Sprint_Modules::PERMISSION_01,
    		'href' 					=> '#tax',
    		'onclick' 				=> 'javascript:hashClick("#tax");',
    		'wrapper_start'			=> '<li id="tax_nav" class="left_nav">',
    		'wrapper_end'			=> '</li>',
    		'caption' 				=> 'Tax'
    		);

        $btn_annual_tax_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'annual_tax',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#annual_tax',
            'onclick'               => 'javascript:hashClick("#annual_tax");',
            'wrapper_start'         => '<li id="annual_tax_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Annual Tax'
            );

        $btn_alpha_list_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'alphalist',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#alphalist',
            'onclick'               => 'javascript:hashClick("#alphalist");',
            'wrapper_start'         => '<li id="alphalist_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Alpha List'
            );

        $btn_bir_2316_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'bir_2316',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#bir_2316',
            'onclick'               => 'javascript:hashClick("#bir_2316");',
            'wrapper_start'         => '<li id="bir_2316_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'BIR 2316'
            );

        $btn_yearly_bonus_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'yearly_bonus',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#yearly_bonus',
            'onclick'               => 'javascript:hashClick("#yearly_bonus");',
            'wrapper_start'         => '<li id="yearly_bonus_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => '13th Month Bonus'
            );

        $btn_leave_converted_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'leave_converted',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#leave_converted',
            'onclick'               => 'javascript:hashClick("#leave_converted");',
            'wrapper_start'         => '<li id="leave_converted_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Leave converted'
            );

        $btn_other_earnings_config = array(
            'module'                => 'payroll',
            'parent_index'          => 'reports',
            'child_index'           => 'other_earnings',
            'required_permission'       => Sprint_Modules::PERMISSION_01,
            'href'                  => '#other_earnings',
            'onclick'               => 'javascript:hashClick("#other_earnings");',
            'wrapper_start'         => '<li id="other_earnings_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Other Earnings'
            );

        $btn_cost_center_config = array(
            'module'                => G_Sprint_Modules::PAYROLL,
            'parent_index'          => 'reports',
            'child_index'           => 'cost_center',
            'required_permission'   => Sprint_Modules::PERMISSION_01,
            'href'                  => '#cost_center',
            'onclick'               => 'javascript:hashClick("#cost_center");',
            'wrapper_start'         => '<li id="cost_center_nav" class="left_nav">',
            'wrapper_end'           => '</li>',
            'caption'               => 'Payroll Register'
            );  


		$this->var['btn_payslip'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_payslip_config);
		// $this->var['btn_payroll_register'] 		= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_payroll_register_config);
        $this->var['btn_cost_center']           = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_cost_center_config);
        $this->var['btn_cash_file']               = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_cash_file_config);
		$this->var['btn_sss'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_sss_config);
		$this->var['btn_philhealth'] 			= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_philhealth_config);
		$this->var['btn_pagibig'] 				= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_pagibig_config);
		$this->var['btn_tax'] 					= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_tax_config);
        //$this->var['btn_annual_tax']            = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_annual_tax_config);
        $this->var['btn_alpha_list']            = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_alpha_list_config);
        $this->var['btn_bir_2316']              = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_bir_2316_config);
        $this->var['btn_yearly_bonus']          = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_yearly_bonus_config);
        $this->var['btn_leave_converted_config']= G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_leave_converted_config);
        $this->var['btn_other_earnings_config'] = G_Button_Builder::createAnchorTagWithPermissionValidation($this->global_user_payroll_actions, $btn_other_earnings_config);

		$this->var['page_title'] = 'Reports';
		$this->var['sub_menu_payroll_management'] = true;
		$this->view->setTemplate('payroll/template_reports.php');
		$this->view->render('reports/index.php',$this->var);		
	}

}
?>