<?php
error_reporting(1);
define("BASE_PATH", str_replace("\\", "/", realpath(dirname(__FILE__))).'/');

class Request_Approvers extends UnitTestCase {
    function testcase01_request_approvers_no_duplicates() {       
        $title      = "Admin Requests";
        $approvers = array
        (
            1 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4',
            2 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg',
            3 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg'
        );
        $requestors = "7:E,3:D,2:E";
        $date       = date("Y-m-d H:i:s");
        
        $gr = new G_Request_Approver();
        $gr->setTitle($title);
        $gr->setApprovers($approvers);
        $gr->setRequestors($requestors);
        $gr->setDateCreated($date);
        $data = $gr->addRequestApprovers();
        
        Utilities::displayArray($data);
        
        $expected_result = false;
        $result          = $data['is_success'];

        $this->assertEqual($result, $expected_result);

        //Delete saved data - for tdd only
        $last_inserted_id = $data['last_id'];        
        $gr->setId($last_inserted_id);
        $gr->deleteRequestApprovers();
    }

    function testcase02_add_request_approvers() {       
        $title      = "Admin Requests";
        $approvers = array
        (
            1 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4',
            2 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg'           
        );

        $requestors = "NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg:E,bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4:D";
        $date       = date("Y-m-d H:i:s");
        
        $gr = new G_Request_Approver();
        $gr->setTitle($title);
        $gr->setApprovers($approvers);
        $gr->setRequestors($requestors);
        $gr->setDateCreated($date);
        $data = $gr->addRequestApprovers();
        
        Utilities::displayArray($data);
        
        $expected_result = true;
        $result          = $data['is_success'];

        $this->assertEqual($result, $expected_result);

        //Delete saved data - for tdd only
        $last_inserted_id = $data['last_id'];        
        $gr->setId($last_inserted_id);
        $gr->deleteRequestApprovers();
    }

    function testcase03_create_employee_request() {       
        $approvers = array
        (
            1 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4', //Encrypted employee id
            2 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg' //Encrypted employee id           
        );

        $request_id   = 1;
        $request_type = G_Request::PREFIX_LEAVE;
        $requestor_id = 1;

        //Create request approvers
        $title      = "TDD Requests";
        $approvers = array
        (
            1 => 'bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4',
            2 => 'NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg'           
        );

        $requestors = "NltI_8hiiaR7gMKq87Dzo27f53HGpQJQqmpuo-gOcJg:E,bHKwB7wfDub8XwHn2L2a-Kd4MI2jdmsyWfcL5xEolp4:D";
        $date       = date("Y-m-d H:i:s");
        
        $gr = new G_Request_Approver();
        $gr->setTitle($title);
        $gr->setApprovers($approvers);
        $gr->setRequestors($requestors);
        $gr->setDateCreated($date);
        $request_approvers = $gr->addRequestApprovers();

        $leave = G_Employee_Leave_Request_Finder::findById($request_id);
        if( empty($leave) ){
            $company_structure_id = 1;
            $employee_id          = 1;
            $leave_id             = 1;
            $date_applied         = "2014-01-12";
            $time_applied         = "03:52:30";
            $date_start           = "2014-01-22";
            $date_end             = "2014-01-23";
            $is_halfday           = G_Employee_Leave_Request::NO;
            $comment              = "TDD Leave Request";
            $status               = G_Employee_Leave_Request::PENDING;
            $is_paid              = G_Employee_Leave_Request::NO;
            $created_by           = "Admin";

            $leave = new G_Employee_Leave_Request();        
            $leave->setCompanyStructureId($company_structure_id);
            $leave->setEmployeeId($employee_id);
            $leave->setLeaveId($leave_id);
            $leave->setDateApplied($date_applied);
            $leave->setTimeApplied($time_applied);
            $leave->setDateStart($date_start);
            $leave->setDateEnd($date_end);
            $leave->setApplyHalfDayDateStart($is_halfday);     
            $leave->setLeaveComments($comment);
            $leave->setIsApproved($status);
            $leave->setIsPaid($is_paid);
            $leave->setCreatedBy($created_by);         
            $leave_data = $leave->saveRequest();
            $request_id = $leave_data['last_inserted_id'];
            $leave = G_Employee_Leave_Request_Finder::findById($request_id);
        }
        
        $r = new G_Request();
        $r->setRequestorEmployeeId($requestor_id);
        $r->setRequestId($request_id);
        $r->setRequestType($request_type);
        $data = $r->saveEmployeeRequest($approvers);
        
        Utilities::displayArray($leave);
        Utilities::displayArray($request_approvers);
        Utilities::displayArray($data);
        
        $expected_result = true;
        $result          = $data['is_success'];

        $this->assertEqual($result, $expected_result);

        //Delete request - for tdd only
        $r->deleteAllRequestByRequestIdAndRequestType();
        $leave->delete();

        $last_inserted_id = $request_approvers['last_id'];        
        $gr->setId($last_inserted_id);
        $gr->deleteRequestApprovers();
    }
}
?>