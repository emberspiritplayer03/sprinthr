<?php
class Benchmark_Bio_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');

		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function view_request()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		

		$employee_eid = $_GET['employee_eid'];
		$type         = $_GET['type'];
		$request_eid  = $_GET['request_eid'];
		$is_valid     = false;		
		if( !empty($employee_eid) && !empty($type) && !empty($request_eid) ){			
			$r = new G_Request();
			$valid_prefixes = $r->getValidRequestPrefixes();			
			if( in_array($type, $valid_prefixes) ){				
				$request_id  = Utilities::decrypt($request_eid);
				$employee_id = Utilities::decrypt($employee_eid);

				$e = G_Employee_Finder::findById($employee_id);
				if( $e ){
					$request_data = $e->getRequestForApprovalDetails($request_id, $type);
					if( $request_data ){
						switch ($type) {
							case G_Request::PREFIX_LEAVE:
								$sub_file = '_leave_details.php';
								break;
							case G_Request::PREFIX_OVERTIME:
								$sub_file = '_overtime_details.php';
								break;
							case G_Request::PREFIX_OFFICIAL_BUSSINESS:
								$sub_file = '_ob_details.php';
								break;
							default:
								$sub_file = '';
								break;
						}
						$is_valid = true;
					}
				}
			}
		}
		
		$this->var['sub_file']         = 'requests/' . $sub_file;
		$this->var['is_valid']     	   = $is_valid;		
		$this->var['request_type']     = $request_data['request_type'];
		$this->var['reid']             = $request_data['request_eid'];
		$this->var['aeid']  	   	   = $request_data['approver_eid']; 
		$this->var['greid']        	   = $request_data['approvers_details']['id'];
		$this->var['a_status']     	   = $request_data['approvers_details']['status'];
		$this->var['action_date'] 	   = $request_data['approvers_details']['action_date'];
		$this->var['approver_remarks'] = $request_data['approvers_details']['remarks'];
		$this->var['request_data'] = $request_data['request_details'];		
		$this->var['token']        = Utilities::createFormToken();
 		$this->var['page_title']   = 'View Request';
		$this->view->setTemplate('template.php');
		if( $is_valid ){
			$this->view->render('benchmark/bio/view_request.php',$this->var);
		}else{					
			echo "<div class=\"alert alert-error\">Invalid request type</div><br />";
		}
	}

	function _email_approve_request()
	{
		$data   = $_POST;
		$status = $_POST['status'];
		Utilities::verifyFormToken($_POST['token']);    			

		$json['is_success'] = false;
       	$json['message']    = "Invalid form entries";

		if( !empty($data) ){
			$reid    = $data['reid'];
			$aeid    = $data['aeid'];
			$greid   = $data['greid'];
			$type    = $data['request_type'];
			$remarks = $data['approver-remarks'];

			$r = G_Request_Finder::findByRequestIdAndRequestType(Utilities::decrypt($reid), $type);
			if( $r ){ 
				$r->setActionDate($this->c_date);		
	 			if( $status == 'Approve' ){
	 				$data = array(
						$greid => array('status' => G_Request::APPROVED, 'remarks' => $remarks)					
					);
					$json = $r->updateRequestApproversDataById($data);	
					$r->updateRequestStatus();			
					if( $json['is_success'] ){
						$json['message'] = "Request was successfully approved";
					}
				}elseif( $status == 'Disapprove' ){
					$data = array(
						$greid => array('status' => G_Request::DISAPPROVED, 'remarks' => $remarks)					
					);
					$json = $r->updateRequestApproversDataById($data);			
					$r->updateRequestStatus();	
	       			if( $json['is_success'] ){
						$json['message'] = "Request was successfully disapproved";
					}
				}
			}
		}

		echo json_encode($json);
	}
}
?>