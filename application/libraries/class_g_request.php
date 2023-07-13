<?php
class G_Request extends Request {
	const PREFIX_LEAVE              = 'lv';
	const PREFIX_OVERTIME           = 'ot';
	const PREFIX_OFFICIAL_BUSSINESS = 'ob';

	const PENDING 		= "Pending";
	const APPROVED 		= "Approved";
	const DISAPPROVED 	= "Disapproved";

	const YES 	= "Yes";
	const NO 	= "No";

	public function __construct() {
		
	}

	public function getValidRequestPrefixes(){
		$prefixes = array(self::PREFIX_LEAVE, self::PREFIX_OVERTIME, self::PREFIX_OFFICIAL_BUSSINESS);
		return $prefixes;
	}

	/*
		Usage :
		$data = array(
			'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg' => array('status' => 'Disapproved', 'remarks' => 'Test'), //First index encrypted id. Encrypted array values = array( 'status' (sql field name) => 'Approved' (sql update value))
			'wP4dkK9Ud-6p2NLXkg4M80I-vv0Zaal9dv88kdYYo8s' => array('status' => 'Approved', 'remarks' => 'Test123')
		);
		$date = date("Y-m-d H:i:s");
		$r = new G_Request();
		$r->setActionDate($date);
		$data = $r->updateRequestApproversDataById($data); // Returns array
	*/

	public function updateRequestApproversDataById( $data = array() ) {
		$return  = array();
		$counter = 0;
		
		if( !empty($data) ){
			$counter 		 = 0;					
			foreach( $data as $key => $value ){
				$id = Utilities::decrypt($key);				
				foreach($value as $subKey => $subValue){					
					$sql_data[$id][$subKey]       = $subValue;
					if( !empty($this->action_date) ){
						$sql_data[$id]['is_lock']     = self::YES;
						$sql_data[$id]['action_date'] = $this->action_date;
					}
				}				
				$counter++;
			}	
			G_Request_Manager::updateRequestApproversDataById($sql_data);			
		}

		//Send email to next approver
		$fields = array('request_id','approver_employee_id','request_type');
		$approver_details = G_Request_Helper::sqlGetNextApproverById($id, $fields);
		if( !empty($approver_details) ){
			$request_id   = $approver_details['request_id'];
			$request_type = $approver_details['request_type'];
			$approver_id  = $approver_details['approver_employee_id'];

			$e = G_Employee_Finder::findById($approver_id);
			if( $e ){			
				$r = new G_Request();
				$r->setRequestId($request_id);
				$r->setRequestType($request_type);
				$r->requestENotification($e);
			}
		}

		$return['is_success']			 = true;
		$return['message']   			 = 'Records was successfully updated';
		$return['total_records_updated'] = $counter;
		return $return;
	}

	/*
		Usage : 
		$id = 1;
		$date = date('Y-m-d');
		$request_type = G_Request::PREFIX_OVERTIME;

		$r = new G_Request();
    	$r->setRequestId($id);
    	$r->setRequestType($request_type);
    	$r->setActionDate($date);
    	$r->updateRequestStatus();
	*/

	public function updateRequestStatus() {
		if( !empty($this->request_id) && !empty($this->request_type) ){
			$is_approved    = false;
			$is_disapproved = false;
			$summary     = G_Request_Helper::sqlRequestDataSummaryByRequestIdAndRequestType($this->request_id, $this->request_type);
			$total_approvers   = $summary['total_approvers'];
			$total_approved    = $summary['total_approved'];
			$total_disapproved = $summary['total_disapproved'];
			if( $total_approvers == $total_approved ){
				$is_approved = true;
			}
			
			if( $total_disapproved >= 1 ){
				$is_disapproved = true;
			}

			switch ($this->request_type) {
				case self::PREFIX_LEAVE:					

					if( $is_disapproved ){
						$leave = G_Employee_Leave_Request_Finder::findById($this->request_id);					
						if( $leave ){
							$leave->disApproveRequest();
						}
					}elseif( $is_approved ){
						$leave =G_Employee_Leave_Request_Finder ::findById($this->request_id);					
						if( $leave ){
							//$leave->approve();
							$leave->approveRequest();		
						}
					}

					break;
				case self::PREFIX_OVERTIME:												
					/*if( $is_approved ){
						$overtime = G_Overtime_Finder::findById($this->request_id);		
						if( $overtime ){
						  $overtime->setStatus(G_Overtime::STATUS_APPROVED);
                		  $overtime->save();
						}
					}*/

					if( $is_disapproved ){
						$overtime = G_Overtime_Finder::findById($this->request_id);		
						if( $overtime ){
						  $overtime->setStatus(G_Overtime::STATUS_DISAPPROVED);
                		  $overtime->save();
						}
					}elseif( $is_approved ){
						$overtime = G_Overtime_Finder::findById($this->request_id);		
						if( $overtime ){
						  $overtime->setStatus(G_Overtime::STATUS_APPROVED);
                		  $overtime->save();
						}
					}

					break;
				case self::PREFIX_OFFICIAL_BUSSINESS:
					/*if( $is_approved ){
						$ob = G_Employee_Official_Business_Request_Finder::findById($this->request_id);
						if( $ob ){
							$ob->approve();
						}
					}*/	

					if( $is_disapproved ){
						$ob = G_Employee_Official_Business_Request_Finder::findById($this->request_id);
						if( $ob ){
							$ob->disapprove();
						}
					}elseif( $is_approved ){
						$ob = G_Employee_Official_Business_Request_Finder::findById($this->request_id);
						if( $ob ){
							$ob->approve();
						}
					}
									
					break;
					default:					
					break;
			}
		}	
	}

	/*
		Usage :
		//Set all approvers status to Pending
		$id = 2;
		$request = new G_Request();
		$request->setRequestId($id);
		$request->setRequestType(G_Request::PREFIX_LEAVE);
		$request->resetToPendingApproversStatusByRequestIdAndRequestType(); 
	*/

	public function resetToPendingApproversStatusByRequestIdAndRequestType() {
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'No approvers found for the selected request.';

		if( !empty($this->request_id) && !empty($this->request_type) ){
			$total_approvers = G_Request_Helper::sqlTotalApproversByRequestIdAndRequestType($this->request_id, $this->request_type);

			if( $total_approvers > 0 ){
				G_Request_Manager::resetToPendingApproversStatusByRequestIdAndRequestType($this->request_id, $this->request_type);
			}
		}

		return $return;
	}

	/*
		Usage :
		//Set all approvers status to Approved
		$id = 2;
		$request = new G_Request();
		$request->setRequestId($id);
		$request->setRequestType(G_Request::PREFIX_LEAVE);
		$request->resetToApprovedApproversStatusByRequestIdAndRequestType(); 
	*/

	public function resetToApprovedApproversStatusByRequestIdAndRequestType() {
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'No approvers found for the selected request.';

		if( !empty($this->request_id) && !empty($this->request_type) ){
			$total_approvers = G_Request_Helper::sqlTotalApproversByRequestIdAndRequestType($this->request_id, $this->request_type);

			if( $total_approvers > 0 ){
				G_Request_Manager::resetToApprovedApproversStatusByRequestIdAndRequestType($this->request_id, $this->request_type);
			}
		}

		return $return;
	}

	/*
		Usage :
		//Set all approvers status to Disapproved
		$id = 2;
		$request = new G_Request();
		$request->setRequestId($id);
		$request->setRequestType(G_Request::PREFIX_LEAVE);
		$request->resetToDisApprovedApproversStatusByRequestIdAndRequestType(); 
	*/

	public function resetToDisApprovedApproversStatusByRequestIdAndRequestType() {
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'No approvers found for the selected request.';

		if( !empty($this->request_id) && !empty($this->request_type) ){
			$total_approvers = G_Request_Helper::sqlTotalApproversByRequestIdAndRequestType($this->request_id, $this->request_type);

			if( $total_approvers > 0 ){
				G_Request_Manager::resetToDisApprovedApproversStatusByRequestIdAndRequestType($this->request_id, $this->request_type);
			}
		}

		return $return;
	}
							
	public function save() {
		return G_Request_Manager::save($this);
	}

	/*
		Usage :
		For single request
		$approvers => array
        (
            [1] => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4', //Encrypted employee id
            [2] => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg' //Encrypted employee id           
        );
        $request_id   = 1;
        $request_type = G_Request::PREFIX_LEAVE;
        $requestor_id = 1;
        $r = new G_Request();
        $r->setRequestorEmployeeId($requestor_id);
        $r->setRequestId($request_id);
        $r->setRequestType($request_type);
        $return = $r->saveEmployeeRequest($approvers);
	*/

	public function saveEmployeeRequest( $approvers = array() ) {
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'Cannot save record.';

		if( !empty($this->request_id) && !empty($this->request_type) && !empty($approvers) && !empty($this->requestor_employee_id) ){
			$counter = 1;
			foreach( $approvers as $approver ){
				$id   = Utilities::decrypt($approver);
				$e    = G_Employee_Finder::findById($id);
				$approver_name = '';
				if( $e ){
					
					if( $counter == 1 ){
						$first_approver_id = $e->getId(); //For sending notification to first approver
						$counter++;
					}

					$approver_name = $e->getLastName() .", ". $e->getFirstName() ." ". $e->getMiddleName();

					if($this->status == G_Request::APPROVED) {
						$r_status = G_Request::APPROVED;
					}else{
						$r_status = G_Request::PENDING;
					}

					$requests[] = array(
						"requestor_employee_id" 	=> Model::safeSql($this->requestor_employee_id),
						"request_id" 				=> Model::safeSql($this->request_id),
						"request_type" 				=> Model::safeSql($this->request_type),
						"approver_employee_id" 		=> Model::safeSql($e->getId()),
						"approver_name"				=> Model::safeSql($approver_name),
						"status" 					=> Model::safeSql($r_status),
						"is_lock" 					=> Model::safeSql(G_Request::NO),
						"remarks" 					=> Model::safeSql(''),
						"action_date" 				=> Model::safeSql('')
					);
				}
			}

			if( !empty($requests) ){
				$this->deleteAllRequestByRequestIdAndRequestType(); //Delete request old data
				$last_inserted_id = $this->bulkInsertRequests($requests);

				if( $first_approver_id > 0 ){
					//Send notification to 1st approver
					$request_id   = $this->request_id;
					$request_type = $this->request_type;

					/*$e = G_Employee_Finder::findById($first_approver_id);
					if( $e ){			
						$r = new G_Request();
						$r->setRequestId($request_id);
						$r->setRequestType($request_type);
						$r->requestENotification($e);
					}*/
				}
			}

			$return['is_success'] = true;
			$return['message']    = "Employee request was successfully saved.";
		}  

		return $return;
	}

	public function requestENotification(G_Employee $e) {
		$return = array();
		$return['is_success'] = false;

		if( !empty($this->request_id) && !empty($this->request_type) && !empty($e) ) {
			$contacts     = $e->getContactDetails();
			if( !empty($contacts) ){
				
				$employee_eid   = Utilities::encrypt($e->getId());
				$request_eid    = Utilities::encrypt($this->request_id);
				$request_type   = $this->request_type;
				$employee_email = $contacts->getWorkEmail() != '' ? $contacts->getWorkEmail() : $contacts->getOtherEmail();
				$employee_name  = ucwords($e->getFirstName());

				$data['request_eid']    = $request_eid;
				$data['employee_eid']   = $employee_eid;
				$data['request_type']   = $request_type;				
				$data['employee_name']  = $employee_name;

				$email = new Sprint_Email();
				$email->setTo($employee_email);
				$email->eEmployeeRequestNotification($data);

				$return['is_success'] = true;
			}		
		}

		return $return;
	}

	public function deleteAllRequestByRequestIdAndRequestType() {
		if( !empty( $this->request_id ) && !empty($this->request_type) ){
			G_Request_Manager::deleteAllRequestByRequestIdAndRequestType($this->request_id, $this->request_type);
		}
	}
	
	public function bulkInsertRequests($requests = array()) {
		foreach($requests as $key => $value) {
			$values[] = "(" . implode(",",$value) . ")";
		}
		$r = implode(",",$values) . ";";
		return G_Request_Manager::bulkInsertRequests($r);
	}

	/*
		Usage :
		$request_id  = Utilities::decrypt($_GET['eid']);
		$approvers   = new G_Request();
		$approvers->setRequestId($request_id);
		$data = $approvers->getLeaveRequestApproversStatus();
	*/

	public function getLeaveRequestApproversStatus(){
		$data = array();

		if( !empty($this->request_id) ){
			$request_type = self::PREFIX_LEAVE;
			$fields    = array("id","approver_name","status","remarks","is_lock");
			$order_by  = "ORDER BY id ASC"; //approvers level order 
			$approvers = G_Request_Helper::sqlFetchDataByRequestIdAndRequestType($this->request_id, $request_type, $fields);
			
			$total_approvers         = count($approvers);
			$data['total_approvers'] = $total_approvers;
			$data['approvers'] 	     = $approvers;
		}

		return $data;
	}

	/*
		Usage :
		$request_id  = Utilities::decrypt($_GET['eid']);
		$approvers   = new G_Request();
		$approvers->setRequestId($request_id);
		$data = $approvers->getObRequestApproversStatus();
	*/

	public function getObRequestApproversStatus(){
		$data = array();

		if( !empty($this->request_id) ){
			$request_type = self::PREFIX_OFFICIAL_BUSSINESS;
			$fields    = array("id","approver_name","status","remarks","is_lock");
			$order_by  = "ORDER BY id ASC"; //approvers level order 
			$approvers = G_Request_Helper::sqlFetchDataByRequestIdAndRequestType($this->request_id, $request_type, $fields);
			
			$total_approvers         = count($approvers);
			$data['total_approvers'] = $total_approvers;
			$data['approvers'] 	     = $approvers;
		}

		return $data;
	}

	public function getOvertimeRequestApproversStatus(){
		$data = array();

		if( !empty($this->request_id) ){
			$request_type = self::PREFIX_OVERTIME;
			$fields    = array("id","approver_name","status","remarks","is_lock");
			$order_by  = "ORDER BY id ASC"; //approvers level order 
			$approvers = G_Request_Helper::sqlFetchDataByRequestIdAndRequestType($this->request_id, $request_type, $fields);
			
			$total_approvers         = count($approvers);
			$data['total_approvers'] = $total_approvers;
			$data['approvers'] 	     = $approvers;
		}

		return $data;
	}

	public function getPendingForApprovalRequest(){
		$data = array();
		$data['needs_approval'] = 0;

		if( !empty($this->approver_employee_id) ){
			$fields    = array("id","request_id","request_type","approver_employee_id");
			$order_by  = "ORDER BY id ASC"; //approvers level order 
			$pending_requests = G_Request_Helper::sqlGetPendingRequestByApproverEmployeeId($this->approver_employee_id, $this->request_type, $fields);

			foreach($pending_requests as $key => $value) {
				$request_type = $value['request_type'];
				$fields    = array("id","request_id","approver_employee_id","approver_name","status","is_lock");
				$order_by  = "ORDER BY id ASC"; //approvers level order 
				$approvers = G_Request_Helper::sqlFetchDataByRequestIdAndRequestType($value['request_id'], $request_type, $fields);

				$previous_status = "";
				foreach($approvers as $sub_key => $sub_value) {
					if($previous_status == G_Request::APPROVED && $sub_value["approver_employee_id"] == $value["approver_employee_id"] ) {
						//for second level++
						$data['needs_approval']++;
						$data['requests'][] = $sub_value['id'];
					}elseif($previous_status == "" && $sub_value["approver_employee_id"] == $value["approver_employee_id"]) {
						//for first level
						$data['needs_approval']++;
						$data['requests'][] = $sub_value['id'];
					}
					$previous_status = $sub_value["status"];
				}

			}
		}

		return $data;
	}

}
?>