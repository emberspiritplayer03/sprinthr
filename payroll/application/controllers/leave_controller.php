<?php
class Leave_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		
		
		Loader::appMainScript('leave.js');

		Loader::appStyle('style.css');
		$this->var['employee'] = 'selected';
	
		$this->company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];	

	}

	function index()
	{
		Yui::loadMainDatatable();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTextBoxList();

		$this->var['import_action'] = url('leave/_import_leave_excel');
		$c = G_Company_Structure_Finder::findById($this->company_structure_id);
		$this->var['leaves'] = $leave = G_Leave_Finder::findByCompanyStructureId($c);
		
		$this->var['page_title'] = 'Leave Management';
		$this->view->setTemplate('template_leave.php');
		$this->view->render('leave/index.php',$this->var);
	}
	
	function _load_list()
	{
		$this->var['token'] = Utilities::createFormToken();
	
		$c = G_Company_Structure_Finder::findById($this->company_structure_id);
		
		$this->var['leaves'] = $leave = G_Leave_Finder::findByCompanyStructureId($c);
	
		$this->view->noTemplate();
		$this->view->render('leave/leave_list/index.php',$this->var);	
	}
	
	
	function _insert_new_employee_leave()
	{

		Utilities::verifyFormToken($_POST['token']);

		$row = $_POST;
		if($row['employee_id']!='' && $this->company_structure_id!='') {
			$e = new G_Employee_Leave_Request;
			$e->setId($row['id']);
			$e->setCompanyStructureId($this->company_structure_id);
			$e->setEmployeeId($row['employee_id']);
			$e->setLeaveId($row['leave_id']);
			$e->setDateApplied($row['date_applied']);
			$e->setDateStart($row['date_start']);
			$e->setDateEnd($row['date_end']);
			$e->setLeaveComments($row['leave_comments']);
			$e->setIsPaid($row['is_paid']);
			$e->setIsApproved($row['is_approved']);	
			$e->save();
			
			if ($row['is_approved']) {
				$start_date = strtotime($row['date_start']);
				$end_date = strtotime($row['date_end']);
				$emp = G_Employee_Finder::findById($row['employee_id']);
				if ($emp) {
					if ($start_date && $end_date) {
						$start_date = date('Y-m-d', $start_date);
						$end_date = date('Y-m-d', $end_date);
						
						$dates = Tools::getBetweenDates($start_date, $end_date);
						foreach ($dates as $date) {
							G_Attendance_Helper::updateAttendance($emp, $date);								
						}	
					}
				}
			}
			
			echo 1;
		}else {
			echo 0;				
		}
	}
	
	function _json_encode_employee_leave_list()
	{
		
		Utilities::ajaxRequest();
		$field_list = array('leave type',
							'employee_code',
							'lastname',
							'firstname',
							'date filed',
							'status',
							'date started');

		$colon_count = substr_count($_GET['search'], ':'); 
		if($colon_count>0) {/* if has a colon*/

				$search = G_Employee_Leave_Request_Helper::getDynamicQueries($_GET['search']);
				
		}else {
			//no colon
			
			if($_GET['search']) {
				$search = " AND (e.firstname like '%". $_GET['search'] ."%' OR e.lastname like '%". $_GET['search'] ."%' ";
				$search .= " OR e.middlename like '%". $_GET['search'] ."%' ";
				$search .= "OR e.employee_code like '%".$_GET['search']."%' OR a.date_applied like '%".$_GET['search']."%' ";
				$search .= " OR l.name like '%".$_GET['search']."%' OR a.is_approved LIKE '".$_GET['search']."%' ) ";
			}
			
		}
		
		$limit = 'LIMIT ' . $_GET['startIndex'] . ', ' . $_GET['results'];
		if($_GET['sort']=='date_applied') {
			$sort = 'a.date_applied';	
		}elseif($_GET['sort']=='employee_name') {
			$sort = 'e.lastname';
		}elseif($_GET['sort']=='leave_type') {
			$sort='l.name';
		}elseif($_GET['sort']=='date_start') {
			$sort='a.date_start';
		}elseif($_GET['sort']=='date_end') {
			$sort='a.date_end';
		}elseif($_GET['sort']=='is_approved') {
			$sort='a.is_approved';
		}
		
		$order_by = ($_GET['sort'] != '') ? 'ORDER BY ' .$sort  . ' ' . $_GET['dir']  :  ' ORDER BY a.id desc' ;
		
		$cs_id = 	$this->company_structure_id;
		$employee = G_Employee_Leave_Request_Helper::findBySearch($cs_id,$search,$order_by,$limit);
	
		foreach ($employee as $key=> $object) { 
			$data[$key] = $object;
			$data[$key]['id'] = Utilities::encrypt($object['id']);
			$data[$key]['employee_id'] = Utilities::encrypt($object['employee_id']);
			$data[$key]['hash'] = Utilities::encrypt($object['hash']);
			$data[$key]['is_approved'] = $object['is_approved'];
			$data[$key]['date_start'] = Date::convertDateIntIntoDateString($object['date_start']);
			$data[$key]['date_end'] = Date::convertDateIntIntoDateString($object['date_end']);
			$data[$key]['date_applied'] = Date::convertDateIntIntoDateString($object['date_applied']);
			$data[$key]['action'] = "<div id='dropholder'><a href='#employee_leave' onclick='javascript:hashClick(0,{eid:".$object['employee_id'].",id:".$object['id']."});'>Display</a></div>";
		}
				
		$count_total = G_Employee_Leave_Request_Helper::findBySearch($cs_id,$search);

		$total = count($employee);
		$total_records = count($count_total) ;
	
		header("Content-Type: application/json"); 
		echo "{\"recordsReturned\":{$total}, \"totalRecords\": {$total_records}, \"records\": " . json_encode($data) . "}";	
	}
	
	function _import_leave_excel()
	{
		ini_set("memory_limit", "999M");
		set_time_limit(999999999999999999999);
				
		$file = $_FILES['leave']['tmp_name'];
		//$file = BASE_PATH . 'files/files/attendance.xls';
		$data = new Excel_Reader($file);
		$total_row = $data->countRow();
		
		$error_count = 0;
		$imported_count = 0;
		
		$error_employee_code = 0;
		$error_complete_name = 0;
		$error_date_start =0;
		$error_date_end = 0;
		$error_date_applied =0;
		for ($i = 1; $i <= $total_row; $i++) {

				$excel_employee_code = (string) trim($data->getValue($i, 'A'));				
				$excel_lastname = (string) trim(utf8_encode($data->getValue($i, 'B')));
				$excel_firstname = (string) trim(utf8_encode($data->getValue($i, 'C')));
				$excel_middlename = (string) trim(utf8_encode($data->getValue($i, 'D')));
				$excel_leave_type = (string) trim(utf8_encode($data->getValue($i, 'E')));
				
				$date_applied = (string) trim($data->getValue($i, 'F'));
				$excel_date_applied = date('Y-m-d', strtotime($date_applied));
				
				$date_start = (string) trim($data->getValue($i, 'G'));
				$excel_date_start = date('Y-m-d', strtotime($date_start));
				
				$date_end = (string) trim($data->getValue($i, 'H'));
				$excel_date_end = date('Y-m-d', strtotime($date_end));
				
				$is_paid = (string) trim(utf8_encode($data->getValue($i, 'I')));
				
				$excel_is_paid = ($is_paid=='yes')? 'yes' : 'no' ;
				$excel_comment = (string) trim(utf8_encode($data->getValue($i, 'J')));
				$excel_is_approved = (string) trim(utf8_encode($data->getValue($i, 'K')));
				$excel_is_approved = ($excel_is_approved=='') ? 'Pending' : $excel_is_approved;								
				$company_structure_id = $this->company_structure_id;
				
			if($i>1) {
	
				if ($excel_employee_code) {
					$e = G_Employee_Finder::findByEmployeeCode($excel_employee_code);
					if (!$e) {
						$error_count++;
						$error_employee_code++; // no employee code
						$code[] = $excel_employee_code;
					}else {
						
						$leave_type = G_Leave_Finder::findByName($excel_leave_type);
						if($leave_type) {
							
							$l = new G_Employee_Leave_Request;
							$l->setCompanyStructureId($company_structure_id);
							$l->setEmployeeId($e->getId());
							$l->setLeaveId($leave_type->getId());
							$l->setDateApplied($excel_date_applied);
							$l->setDateStart($excel_date_start);
							$l->setDateEnd($excel_date_end);
							$l->setLeaveComments($excel_comment);
							$l->setIsPaid($excel_is_paid);
							$l->setIsApproved($excel_is_approved);
							$l->save();
							
							if (strtotime($excel_date_start) && strtotime($excel_date_end)) {
								$start_date = date('Y-m-d', strtotime($excel_date_start));
								$end_date = date('Y-m-d', strtotime($excel_date_end));
								
								$dates = Tools::getBetweenDates($start_date, $end_date);
								foreach ($dates as $date) {
									G_Attendance_Helper::updateAttendance($e, $date);								
								}	
							}
							$imported_count++;
						}else {
							
							//create new leave type
							$leave_type = new G_Leave;
							$leave_type->setCompanyStructureId($company_structure_id);	
							$leave_type->setName($excel_leave_type);
							$is_paid = ($excel_is_paid=='yes') ? 1 : 0 ;
							$leave_type->setIsPaid($is_paid);
							$leave_type_id = $leave_type->save();
							//create leave request
							$l = new G_Employee_Leave_Request;
							$l->setCompanyStructureId($company_structure_id);
							$l->setEmployeeId($e->getId());
							$l->setLeaveId($leave_type_id);
							$l->setDateApplied($excel_date_applied);
							$l->setDateStart($excel_date_start);
							$l->setDateEnd($excel_date_end);
							$l->setLeaveComments($excel_comment);
							$l->setIsApproved($excel_is_approved);
							$l->save();
							
							if (strtotime($excel_date_start) && strtotime($excel_date_end)) {
								$start_date = date('Y-m-d', strtotime($excel_date_start));
								$end_date = date('Y-m-d', strtotime($excel_date_end));
								
								$dates = Tools::getBetweenDates($start_date, $end_date);
								foreach ($dates as $date) {
									G_Attendance_Helper::updateAttendance($e, $date);								
								}	
							}						
							$imported_count++;	
							
						}	
					}			
				}else {
					//search by name
					$error_count++;
					$error_employee_code++;
					
				}
				
				$error_complete_name=0;
			}
		}
				
		if ($imported_count > 0) {
			$return['is_imported'] = true;
			if ($error_count > 0) {
				$total_row = $total_row - 1; // minus the excel title header
				$msg =  $imported_count. ' of '.$total_row .' records has been successfully imported.';
				if($error_employee_code>0) {
					$msg .= '<br> '. $error_employee_code.' error(s) found in Employee Code.<br>
							List of Employee Code does not exist<br>
					';	
					foreach($code as $key=>$value) {
						$msg .= "Row: " .$value.'<br>';
					}
				}
	
				$return['message']= $msg;
			} else {
				$return['message'] = $imported_count . ' Record(s) has been successfully imported.';
			}
		} else {
			$return['message'] = 'There was a problem importing the leave. Please contact the administrator.';
		}
		//echo json_encode($return);	
		echo $return['message'];
	}
	
	function _load_employee_leave()
	{
		$eid = (int) $_GET['eid'];
		$lid = (int) $_GET['lid'];
		
		$_SESSION['hr']['lid'] = $lid;
		$_SESSION['hr']['eid'] = $eid;
		
		Utilities::ajaxRequest();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		
		
		$all_request = G_Employee_Leave_Request_Finder::findByEmployeeId($eid);
		$request = G_Employee_Leave_Request_Finder::findById($lid);
		$employee = G_Employee_Helper::findByEmployeeId($eid);
		
		$this->load_summary_photo();
		$availables = G_Employee_Leave_Available_Finder::findByEmployeeId($eid);
		$this->var['all_request'] = $all_request;
		$this->var['availables'] = $availables;
		$this->var['employee'] = $employee;
		$this->var['details'] = $request;
		$this->var['page_title'] = 'Leave Management';
		$this->view->noTemplate();
		$this->view->render('leave/leave_list/form/edit_employee_leave.php',$this->var);
	}
	
	function load_summary_photo()
	{
		$employee_id = $_GET['eid'];
		$e = G_Employee_Finder::findById($employee_id);
		$file = PHOTO_FOLDER.$e->getPhoto();
		
		if(Tools::isFileExist($file)==true && $e->getPhoto()!='') {
			$this->var['filemtime'] = md5($e->getPhoto()).date("His");
			$this->var['filename'] = $file;
			
		}else {
			$this->var['filename'] = BASE_FOLDER. 'images/profile_noimage.gif';
		
		}	
		
	}
	
	function _load_token()
	{
		
	}
	
	function _update_leave_request()
	{
		
		$row = $_POST;

		$e = G_Employee_Leave_Request_Finder::findById($row['id']);

		$e->setId($row['id']);
		$e->setCompanyStructureId($this->company_structure_id);
		$employee_id = Utilities::decrypt($row['employee_id']);
		$e->setEmployeeId($employee_id);
		$e->setLeaveId($row['leave_id']);
		$e->setDateApplied($row['edit_date_applied']);
		$e->setDateStart($row['edit_date_start']);
		$e->setDateEnd($row['edit_date_end']);
		$e->setLeaveComments($row['leave_comments']);
		$e->setIsPaid($row['is_paid']);
		$e->setIsApproved($row['is_approved']);	
		$e->save();
		
		if ($row['is_approved']) {
			$start_date = strtotime($row['date_start']);
			$end_date = strtotime($row['date_end']);
			$emp = G_Employee_Finder::findById($row['employee_id']);
			if ($emp) {
				if ($start_date && $end_date) {
					$start_date = date('Y-m-d', $start_date);
					$end_date = date('Y-m-d', $end_date);
					
					$dates = Tools::getBetweenDates($start_date, $end_date);
					foreach ($dates as $date) {
						G_Attendance_Helper::updateAttendance($emp, $date);								
					}	
				}
			}
		}		
		
		//create bawas
		echo 1;
	}
	
}
?>