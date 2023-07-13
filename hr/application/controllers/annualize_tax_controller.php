<?php
class Annualize_Tax_Controller extends Controller
{
	function __construct()
	{	
		parent::__construct();
		
		Loader::appStyle('style.css');
		Loader::appMainScript('tax.js');		
		Loader::appMainUtilities();

		$this->sprintHdrMenu(G_Sprint_Modules::PAYROLL, 'earnings_deductions');
		//$this->redirectNoAccessModule(G_Sprint_Modules::PAYROLL, 'earnings_deductions');
		$data = G_Cutoff_Period_Helper::isPeriodLock($_GET['hpid']);		
		if($_GET['hpid']){
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'] = $data;
		}else{			
			$this->var['is_period_lock'] = $_SESSION['sprint_hr']['is_period_lock'];
		}
		
		if($_GET['from'] && $_GET['to'] && $_GET['hpid']){
			$this->var['download_url']    = url('reports/download_earnings?from=' . $_GET['from'] . '&to=' . $_GET['to'] . '&hpid=' . $_GET['hpid']);
            $this->var['period_selected'] = '<small style="font-size:15px;">Period: <b>' . Tools::convertDateFormat($_GET['from']) . ' </b> to <b>' . Tools::convertDateFormat($_GET['to']) . '</b></small>';
		}
		
		$this->eid                  = $this->global_user_eid;
		$this->company_structure_id = $this->global_user_ecompany_structure_id;				
		$this->c_date  				= Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
		$this->default_method       = 'index';					
		$this->var['leave']         = 'selected';			
		$this->var['employee']      = 'selected';
		$this->var['eid']           = $this->eid;	
		$this->var['departments']   = G_Company_Structure_Finder::findByParentID(Utilities::decrypt($this->global_user_ecompany_structure_id));		
		$this->validatePermission(G_Sprint_Modules::PAYROLL,'earnings_deductions','');				
	}

	function index()
	{			
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();
		Jquery::loadMainJqueryDatatable();
		Jquery::loadMainTextBoxList();	
		
		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'tax_annualization'; 		
		
		$year = date("Y");
        $this->var['start_year']  = 2015;        
		$this->var['location']    = 'tax_annualization';		
        $this->var['cutoff_id']   = $eid;   
		$this->var['page_title']  = "Annualize Tax";			
		$this->var['period']      = $period;			
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('annualized_tax/annualized_tax.php',$this->var);
	}

	function process_annual_tax()
	{
		$this->var['module'] = 'earnings'; 		
		
		$year = date("Y");
        $this->var['start_year']  = 2015;        
		$this->var['location']    = 'earnings';		          
		$this->var['page_title']  = "Annualized Tax";							
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('annualized_tax/annualized_tax.php',$this->var);
	}

	function process_tax()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();	
		Jquery::loadMainTipsy();		

		// $year = date("Y");
		$year = $_GET['year'];
		$year_previous = date("Y") - 1;
		$c    = G_Cutoff_Period_Finder::findAllByYear($year);      
		$c_previous = G_Cutoff_Period_Finder::findAllByYearNotLock($year_previous);      
		
		if(!empty($c_previous)) {
			$cutoff_periods_merge = array_merge($c_previous,$c);
		} else {
			$cutoff_periods_merge = $c;
		}

		$this->var['recent'] = 'class="selected"';				
		$this->var['module'] = 'earnings'; 				
		$this->var['cutoff_periods'] = $cutoff_periods_merge;
		$this->var['token']			 = Utilities::createFormToken();
        $this->var['start_year']     = 2014;
        $this->var['end_year']		 = date("Y");
        $this->var['months']         = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');  		
		$this->var['page_title']     = "Process Annual Tax";					
		$this->view->setTemplate('payroll/template.php');
		$this->view->render('annualized_tax/forms/annualize_tax.php',$this->var);
	}
	
	function _process_annual_tax()
	{
		$data   = $_POST;		
		$year   = date('Y');
		$bonus  = new G_Yearly_Bonus();
		$bonus->setMonthStart($data['start_month']);
		$bonus->setMonthEnd($data['end_month']);			

		if( $data['use-import-file'] == 'Yes' ){	
		 	$yearly_bonus_data = ['year' => $year, 'cutoff' => $data['cutoff_period'], 'file' => $_FILES['yearly_bonus_file']['tmp_name']];					
			$json   = $bonus->importYearlyBonus($yearly_bonus_data);
		}else{			
			$yearly_bonus_data = ['year' => $year, 'cutoff' => $data['cutoff_period'], 'action' => 2, 'selected' => array()];					
			$json   = $bonus->processYearlyBonus($yearly_bonus_data);
		}

		echo json_encode($json);
	}

	function _process_tax()
	{

		$data = $_POST;

	 // $data['cutoff_period'] = '2019-12-11/2019-12-25';

		$cutoff_period_array = explode("/", $data['cutoff_period']);

		
		$cutoff_period_first_date  = $cutoff_period_array[0];
		$cutoff_period_second_date = $cutoff_period_array[1];

		$cutoff_year1 = date("Y", strtotime($cutoff_period_first_date));
		$cutoff_year2 = date("Y", strtotime($cutoff_period_second_date));

		if($cutoff_year1 == date("Y") && $cutoff_year2 == date("Y")) {
			$year = date("Y");
		} else {
			$year = $cutoff_year1;
		}

		$tax = new G_Annualize_Tax();
		$tax->setYear($year);	
		$tax->setCutoffPeriod($data['cutoff_period']);			

		/*if( date("Y") == 2016 ) {
			$json = $tax->setDefaultFromAndEndDate()->validate($cutoff_period)->annualizeTaxCustomize();
		} else {
			$json = $tax->setDefaultFromAndEndDate()->validate($cutoff_period)->annualizeTax();
		}*/

		$current_employee_year = $year;
		$employees_array = array();
		
		//$employees_array[] = 157;
		//$employees_array[] = 151;
		$employees_array = G_Employee_Helper::getCurrentEmployeeByYear($current_employee_year);

		/* 
		 * Get annual hmo premium data (Custom)
		*/
		$import_hmo_data  = array(); 
		if( $data['use-import-file'] == 'Yes' ){	
		 	$yearly_hmo_import_data = [
		 		'year' => $current_employee_year, 
		 		'file' => $_FILES['yearly_hmo_file']['tmp_name']
		 	];	

			$inputFileType = PHPExcel_IOFactory::identify($yearly_hmo_import_data['file']);
			$objReader     = PHPExcel_IOFactory::createReader($inputFileType); 				
			$this->obj_reader = $objReader->load($yearly_hmo_import_data['file']);

			$read_sheet   = $this->obj_reader->getActiveSheet();
	        $employee_ids = array(); 

	        $total_valid_records = 0;
	        $total_not_imported  = 0;        
	        $counter     = 0;    

	        foreach ($read_sheet->getRowIterator() as $row) {                       
	            $cellIterator = $row->getCellIterator();

	            foreach ($cellIterator as $cell) {              
	                $current_row    = $cell->getRow();
	                $cell_value     = $cell->getFormattedValue();
	                $column         = $cell->getColumn();
	                $current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
	              
	                if ($current_row == 1) {                   
	                    $column_header[$column] = strtolower(trim($cell_value));  

	                }else{                                       
	                    $column_header_value = strtolower(trim($column_header[$column]));  
	                    
	                    switch ($column_header_value) {
	                        case 'employee code':
	                            if( $cell_value != '' ){                            	
	                                $import_hmo_data[$counter]['employee_code'] = trim($cell_value);                                                                                                                     
	                            }
	                            break; 
	                       	case 'earning title':
	                       					if($cell_value != ''){

	                       							$import_hmo_data[$counter]['earning_title'] = trim($cell_value);    
	                       					}
	                       					break;
	                        case 'amount':
	                        	$import_hmo_data[$counter]['amount'] = trim($cell_value);
	                        case 'taxable' :
	                        	if( $cell_value != ''){
	                        			$import_hmo_data[$counter]['taxable'] = trim($cell_value);
	                        	}
	                        	break;

	                        default:                              
	                            break;
	                    }                   
	                }
	            }
	            if( $import_hmo_data[$counter]['employee_code'] != '' && $import_hmo_data[$counter]['amount'] != ''){
	            	//Check if employee code is valid
		            $fields = array('id','number_dependent');

		            $data   = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($import_hmo_data[$counter]['employee_code'],$fields);
		            if( $data['id'] > 0 ){            	
		            	$import_hmo_data[$counter]['eid'] = $data['id'];
		            	$total_valid_records++;
		            }else{
		            	unset($import_hmo_data[$counter]);
		            	$total_not_imported++;
		            }
	            }            
	            $counter++;
	        }	
		}
		// echo "<pre>";
		// var_dump($import_hmo_data);
		// echo "</pre>";
		// echo "<pre>";
		// var_dump($employees_array);
		// echo "</pre>";
		// echo "<pre>";
		// var_dump($employees_array);
		// echo "</pre>";
		// $employees_array = array('157');
		
	
		$json = $tax->setDefaultFromAndEndDate($year)->validate($cutoff_period)->annualizeTax($employees_array, $import_hmo_data);
		// exit();
		echo json_encode($json);
		// echo $sk

	}

	function _load_annualize_tax_by_year() 
	{
		$year = $_GET['year'];
		$e    = new G_Employee();
		$data = $e->getAnnualizedTaxByYear($year);	
			
		$this->var['tax_data'] = $data;		
		$this->view->render('annualized_tax/_annualized_tax_list_dt.php',$this->var);
	}
}
?>