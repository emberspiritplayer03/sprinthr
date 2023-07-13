<?php
class Holiday_Controller extends Controller
{
	function __construct()
	{
	
		parent::__construct();
		Loader::appMainScript('holiday_base.js');
		Loader::appMainScript('holiday.js');			
		Loader::appMainUtilities();		
		Loader::appStyle('style.css');
		$this->var['holiday'] = 'selected';
	}

	function index() {	
		Jquery::loadMainTextBoxList();
		Style::loadTableThemes();
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();
		Jquery::loadMainTipsy();
		$this->var['page_title'] = 'Holiday';
		$this->var['token'] = Utilities::createFormToken();
		$this->var['action'] = url('holiday/_add_holiday');
		$this->var['branches'] = G_Company_Branch_Finder::findAll();
	
		$this->view->setTemplate('template_holiday.php');
		$this->view->render('holiday/index.php', $this->var);		
	}
	
	function _add_holiday() {
		Utilities::verifyFormToken($_POST['token']);
		$error = 0;
		if (!Tools::hasValue($_POST['holiday_name'])) { $error++; }
		if (!Tools::hasValue($_POST['month'])) { $error++; }
		if (!Tools::hasValue($_POST['day'])) { $error++; }
		if (!Tools::hasValue($_POST['holiday_type'])) { $error++; }
		if (empty($_POST['branches'])) { $error++; }

		if ($error == 0) {		
			$h = new G_Holiday();
			$h->setTitle($_POST['holiday_name']);
			$h->setMonth($_POST['month']);
			$h->setDay($_POST['day']);
			$h->setType($_POST['holiday_type']);
			$id = $h->save();
			if ($id) {
				$holiday = G_Holiday_Finder::findById($id);
				foreach ($_POST['branches'] as $branch_id) {
					if ($branch_id != 'all') {		
						$b = G_Company_Branch_Finder::findById($branch_id);
						$holiday->addCompanyBranch($b);
					}
				}
				$holiday_date = date('Y') .'-'. $_POST['month'] .'-'. $_POST['day'];
				if (strtotime($holiday_date) <= strtotime(date('Y-m-d'))) {
					G_Attendance_Helper::updateAttendanceByAllActiveEmployees($holiday_date);
				}				
				$return['message'] = 'New holiday has been added';	
				$return['is_added'] = true;
				$return['token'] = Utilities::createFormToken();		
			} else {
				$return['message'] = 'An error occured. Please contact the developer';	
				$return['is_added'] = false;
			}		
		} else {
			$return['message'] = 'An error occured. Please contact the developer';	
			$return['is_added'] = false;
		}
		echo json_encode($return);
	}
	
	function _delete_holiday() {
		$holiday_id = (int) $_POST['holiday_id'];		
		$is_deleted = false;
		$h = G_Holiday_Finder::findById($holiday_id);
		if ($h) {
			$month = $h->getMonth();
			$day = $h->getDay();				
			$is_deleted = $h->delete();		
		} else {
			$return['message'] = 'An error occured.';
			$return['is_deleted'] = false;	
		}
		$return['is_deleted'] = $is_deleted;
		if ($is_deleted) {
			$holiday_date = date('Y') .'-'. $month .'-'. $day;
			if (strtotime($holiday_date) <= strtotime(date('Y-m-d'))) {
				G_Attendance_Helper::updateAttendanceByAllActiveEmployees($holiday_date);
			}
			$return['message'] = 'Holiday has been deleted';
			$return['is_deleted'] = true;
		} else {
			$return['message'] = 'An error occurred.';
			$return['is_deleted'] = false;
		}
		echo json_encode($return);
	}
	
	function _edit_holiday() {
		$is_valid_token = true;
		if (!Utilities::isFormTokenValid($_POST['token'])) {
			$is_valid_token = false;
		}
		$holiday_id = (int) $_POST['holiday_id'];
		$error = 0;
		if ($holiday_id <= 0) { $error++; }
		if (!Tools::hasValue($_POST['holiday_name_'])) { $error++; }
		if (!Tools::hasValue($_POST['month_'])) { $error++; }
		if (!Tools::hasValue($_POST['day_'])) { $error++; }
		if (!Tools::hasValue($_POST['holiday_type_'])) { $error++; }
		if (empty($_POST['branches_'])) { $error++; }
		
		if ($error == 0 && $is_valid_token) {
			$holiday = G_Holiday_Finder::findById($holiday_id);
			if ($holiday) {
				$holiday->setTitle($_POST['holiday_name_']);
				$holiday->setMonth($_POST['month_']);
				$holiday->setDay($_POST['day_']);
				$holiday->setType($_POST['holiday_type_']);
				$holiday->save();
				
				$selected_branches = $_POST['branches_'];
				$old_branches = explode(',', $_POST['old_branch_ids']);
				$to_be_deleted = array_diff($old_branches, $selected_branches);
				$to_be_added = array_diff($selected_branches, $old_branches);
				if (!empty($to_be_added)) {		
					foreach ($to_be_added as $branch_id) {
						if ($branch_id != 'all' && $branch_id != '') {		
							$b = G_Company_Branch_Finder::findById($branch_id);
							$holiday->addCompanyBranch($b);
						}
					}
				}
				if (!empty($to_be_deleted)) {
					foreach ($to_be_deleted as $branch_id) {
						if ($branch_id != 'all' && $branch_id != '') {
							$b = G_Company_Branch_Finder::findById($branch_id);
							$holiday->removeCompanyBranch($b);
						}
					}
				}
				$holiday_date = date('Y') .'-'. $_POST['month_'] .'-'. $_POST['day_'];
				if (strtotime($holiday_date) <= strtotime(date('Y-m-d'))) {
					G_Attendance_Helper::updateAttendanceByAllActiveEmployees($holiday_date);
				}
				$return['message'] = 'Holiday has been saved.';	
				$return['is_saved'] = true;				
			} else {
				$return['message'] = 'An error occurred.';	
				$return['is_saved'] = false;	
			}
		} else {
			$return['message'] = 'An error occurred.';	
			$return['is_saved'] = false;
		}
		echo json_encode($return);
	}
	
	function ajax_show_holiday_list() {
		$this->var['holidays'] = G_Holiday_Finder::findAll();		
		$this->view->noTemplate();
		$this->view->render('holiday/ajax_holiday_list.php',$this->var);
	}
	
	function ajax_edit_holiday() {
		$this->var['holiday_id'] = $holiday_id = (int) $_GET['holiday_id'];
		$this->var['holiday'] = $h = G_Holiday_Finder::findById($holiday_id);		
		$this->var['branches'] = $branches = G_Company_Branch_Helper::convertToArray(G_Company_Branch_Finder::findAll());
		$this->var['selected_branches'] = $selected_branches = G_Company_Branch_Helper::convertToArray(G_Company_Branch_Finder::findByHoliday($h));
		foreach ($selected_branches as $branch_id => $branch_name) {
			$selected_branch_ids[] = $branch_id;	
		}
		$this->var['selected_branch_ids'] = implode(',', $selected_branch_ids);
		$total_branches = count($branches);
		$total_selected_branches = count($selected_branches);
		$this->var['is_select_all'] = false;
		if ($total_branches == $total_selected_branches) {
			$this->var['is_select_all'] = true;	
		}
		$this->var['token'] = Utilities::createFormToken();
		$this->var['action'] = url('holiday/_edit_holiday');
		$this->view->noTemplate();
		$this->view->render('holiday/forms/ajax_edit_holiday_form.php',$this->var);
	}
}
?>