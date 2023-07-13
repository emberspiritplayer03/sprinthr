<?php

class TestLeave extends UnitTestCase {
	
	function testUpdateLeaveRequest() {

		$row['id'] = 107;
		$row['leave_id'] = 1;
		$row['employee_id'] = 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg';
		$row['edit_date_applied'] = '2012-10-06';
		$row['edit_date_start'] = '2012-10-01';
		$row['edit_date_end'] = '2012-10-02';
		$row['edit_number_of_days'] = 5;
		$row['is_paid'] = 'No';		
		$row['is_approved'] = 'Approved';

		echo "<pre>";
		print_r($row);

		$e = G_Employee_Leave_Request_Finder::findById($row['id']);

		if($e) {
			$is_approved =  $e->getIsApproved();
			$leave_id = $e->getLeaveId();
			$employee_id = $e->getEmployeeId();	

			$e->setId($row['id']);
			$e->setCompanyStructureId($this->company_structure_id);
			$employee_id = 1;// Utilities::decrypt($row['employee_id']);
			$e->setEmployeeId($employee_id);
			$e->setLeaveId($row['leave_id']);
			$e->setDateApplied($row['edit_date_applied']);
			$e->setDateStart($row['edit_date_start']);
			$e->setDateEnd($row['edit_date_end']);
			$e->setLeaveComments($row['leave_comments']);
			$e->setIsPaid($row['is_paid']);
			$e->setIsApproved($row['is_approved']);	
			//$e->save();
		}
		
		if (strtolower($row['is_approved'])=='approved') {
			
			$start_date = strtotime($row['date_start']);
			$end_date = strtotime($row['date_end']);
			$emp = G_Employee_Finder::findById($row['employee_id']);
			if ($emp) {
				if ($start_date && $end_date) {
					$start_date = date('Y-m-d', $start_date);
					$end_date = date('Y-m-d', $end_date);
					
					$dates = Tools::getBetweenDates($start_date, $end_date);
					foreach ($dates as $date) {
						//G_Attendance_Helper::updateAttendance($emp, $date);								
					}	
				}
			}
		}		

		if(strtolower($is_approved)=='pending' && strtolower($row['is_approved'])=='approved') {
			$number_of_days = $row['edit_number_of_days'];
			
			$available = G_Employee_Leave_Available_Finder::findByEmployeeIdLeaveId($employee_id,$leave_id);
			if($available) {
				$available->lessLeaveAvailable($number_of_days);
			}		
		}
	
		
	}
		
}
?>