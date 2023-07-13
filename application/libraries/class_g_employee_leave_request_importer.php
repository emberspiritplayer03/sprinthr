<?php
class G_Employee_Leave_Request_Importer {
    protected $file;

    public $imported_records;
    public $error_count;
    public $error_employee_code;
    public $total_records;
    public $code;

    public function __construct($file) {
        $this->file = $file;
    }

    public function import() {
        $data = new Excel_Reader($this->file);
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
            //$excel_lastname = (string) trim(utf8_encode($data->getValue($i, 'B')));
            //$excel_firstname = (string) trim(utf8_encode($data->getValue($i, 'C')));
            //$excel_middlename = (string) trim(utf8_encode($data->getValue($i, 'D')));
            $excel_leave_type = (string) trim(utf8_encode($data->getValue($i, 'B')));//B

            $date_applied = (string) trim($data->getValue($i, 'C')); //C
            $excel_date_applied = date('Y-m-d', strtotime($date_applied));

            $date_start = (string) trim($data->getValue($i, 'D')); //D
            $excel_date_start = date('Y-m-d', strtotime($date_start));

            $date_end = (string) trim($data->getValue($i, 'E')); //E
            $excel_date_end = date('Y-m-d', strtotime($date_end));

            $excel_is_paid = strtolower((string) trim(utf8_encode($data->getValue($i, 'F')))); //F
            $excel_comment = (string) trim(utf8_encode($data->getValue($i, 'G'))); //G

            $company_structure_id = $_SESSION['sprint_hr']['company_structure_id'];

            if($i>1) {

                if ($excel_employee_code) {
                    $e = G_Employee_Finder::findByEmployeeCode($excel_employee_code);

                    if (!$e) {
                        $error_count++;
                        $error_employee_code++; // no employee code
                        $code[] = $excel_employee_code;
                    }else {

                        $leave_type = G_Leave_Finder::findByName($excel_leave_type);

                        if(!$leave_type) {

                            //create new leave type
                            $leave_type = new G_Leave;
                            $leave_type->setCompanyStructureId($company_structure_id);
                            $leave_type->setName($excel_leave_type);
                            $is_paid = ($excel_is_paid=='yes') ? 1 : 0 ;
                            $leave_type->setIsPaid($is_paid);
                            $leave_type_id = $leave_type->save();
                        } else {
                            $leave_type_id = $leave_type->getId();
                        }

                       $last_insert_id = G_Employee_Leave_Request_Helper::addNewRequest($e->getId(), $leave_type_id, $excel_date_applied, $excel_date_start, $excel_date_end, $excel_comment, '', '', ucfirst($excel_is_paid));

                       if($last_insert_id){
                            //get list of employee approvers
                            $approvers_list = '';
                            $approver_id = [];
                            $employee_id = $e->getId();
                            $gra = new G_Request_Approver();
                            $gra->setEmployeeId($employee_id);
                            $approvers_list = $gra->getEmployeeRequestApprovers();

                            if($approvers_list){

                                    foreach($approvers_list as $level => $approver){
                                        
                                        $counter = 0;

                                        foreach($approver as $key => $value) {
                                            if($counter < 1){
                                                $approver_id[] = Utilities::encrypt($value['employee_id']);
                                            }

                                            $counter++;
                                        }
                                    }

                                    $request_id = $last_insert_id;
                                    $approvers    = $approver_id;
                                    $requestor_id = $employee_id;
                                    $request_type = G_Request::PREFIX_LEAVE;

                                    $r = new G_Request();
                                    $r->setRequestorEmployeeId($requestor_id);
                                    $r->setRequestId($request_id);
                                    $r->setRequestType($request_type);
                                    $r->saveEmployeeRequest($approvers); //Save request approvers
                             }
                       }


                        $imported_count++;
                    }
                } else {
                    $error_count++;
                    $error_employee_code++;
                }

                if($error_count > 0) {
                    $err = new G_Leave_Error;
                    $err->setId($row['id']);
                    $err->setEmployeeId();
                    $err->setEmployeeCode();
                    $err->setEmployeeName();
                    $err->setDateApplied($excel_date_applied);
                    $err->setDateStart($excel_date_start);
                    $err->setDateEnd($excel_date_end);
                    $err->setMessage("Employee does not exists!");
                    $err->setErrorTypeId(G_Leave_Error::EMPLOYEE_DOES_NOT_EXIST);
                    $err->addError();
                }

                $error_complete_name=0;
            }
        }
        $this->imported_records = $imported_count;
        $this->error_count = $error_count;
        $this->error_employee_code = $error_employee_code;
        $this->total_records = $total_row;
        $this->code = $code;
    }

}
?>