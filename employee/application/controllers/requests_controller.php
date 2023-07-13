<?php
class Requests_Controller extends Controller
{
	function __construct()
	{
		parent::__construct();
		Loader::appStyle('style.css');
		$this->isLogin();
		$this->c_date = Tools::getCurrentDateTime('Y-m-d H:i:s','Asia/Manila');
	}

	function view()
	{
		Jquery::loadMainInlineValidation2();
		Jquery::loadMainJqueryFormSubmit();		

		Loader::appMainScript('employee_request.js');
		Loader::appMainScript('employee_request_base.js');

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
		$this->view->setTemplate('template_view_request.php');
		if( $is_valid ){
			$this->view->render('requests/view_request.php',$this->var);
		}else{					
			echo "<div class=\"alert alert-error\">Invalid request type</div><br />";
		}
	}

	function ajax_edit_request_form() {    
		$type = $_GET['type'];
		$eid  = $_GET['eid'];

		if( !empty($eid) && !empty($type) ){
			switch ($type) {
				case G_Request::PREFIX_OVERTIME:
					$this->_edit_ot_request();
					break;
				
				default:
					echo "Invalid request data";
					break;
			}
		}else{
			echo "Invalid request data";
		}
    }

    function _edit_ot_request() {
    	$id = Utilities::decrypt($_GET['eid']);
    	$r  = G_Request_Finder::findById($id);
    	if( $r ){
    		$oid = $r->getRequestId();
    		$o   = G_Overtime_Finder::findById($oid);
	        if ($o) {        
	        	$this->var['eid']      = Utilities::encrypt($o->getId());
	        	$this->var['date'] 	   = $o->getDate();
	            $this->var['time_in']  = Tools::timeFormat($o->getTimeIn());
	            $this->var['time_out'] = Tools::timeFormat($o->getTimeOut());
	            $this->var['date_string'] = Tools::convertDateFormat($o->getDate());
	            $this->var['action']      = url('overtime/_edit_overtime');
	            $this->view->render('overtime/form/ajax_edit_overtime_form.php',$this->var);
	        }else{
	        	echo "Record not found";
	        }
    	}else{
    		echo "Record not found";
    	}		
    }

	function _e_approve_request()
	{
		$data   = $_POST;
		$status = $_POST['status'];
		//Utilities::verifyFormToken($_POST['token']);    			

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

       	$json['token'] = Utilities::createFormToken();
		echo json_encode($json);
	}
}
?>