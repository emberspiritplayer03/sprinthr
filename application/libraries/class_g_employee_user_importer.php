<?php
class G_Employee_User_Importer {
    protected $file;

    public $imported_records;
    public $error_count;
    public $error_employee_code;
    public $error_employee_user;
    public $error_username;
    public $error_invalid_username;

    public $total_records;
    public $code;
    public $existing_username;
    public $existing_employee_user;
    public $existing_invalid_username;

    public function __construct($file) {
        $this->file = $file;
    }

    public function import($role_id,$company_structure_id) {
        $data = new Excel_Reader($this->file);
        $total_row = $data->countRow();

        $error_count = 0;
        $imported_count = 0;

        $error_employee_code = 0;
        $error_employee_user = 0;
        $error_invalid_username = 0;
        $error_username = 0;

        for ($i = 1; $i <= $total_row; $i++) {

            $excel_employee_code = (string) trim($data->getValue($i, 'A'));
            $excel_username = (string) trim(utf8_encode($data->getValue($i, 'B')));//B
            $excel_password = (string) utf8_encode($data->getValue($i, 'C'));//C
            $excel_password = Utilities::encrypt($excel_password);

            if($i>1) {

                if ($excel_employee_code) {
                    $e = G_Employee_Finder::findByEmployeeCode($excel_employee_code);

                    $eu = new G_Employee_User();
                    $eu->setUsername($excel_username);
                    $is_exists = $eu->isUserNameExists();
                    $is_invalid  = $eu->isUserNameWithSpecialChar();

                    if (!$e) {
                        $error_count++;
                        $error_employee_code++; // no employee code
                        $code[] = $excel_employee_code;
                    }elseif($is_invalid){
                        $error_count++;
                        $error_invalid_username++;
                        $existing_invalid_username[] = $excel_username;
                    }elseif($is_exists > 0){
                        $error_count++;
                        $error_username++;
                        $existing_username[] = $excel_username;
                    }else {

                        $eu = G_Employee_User_Finder::findByEmployeeId($e->getId());

                        if($eu) {
                            $error_count++;
                            $error_employee_user++; 
                            $existing_employee_user[] = $excel_employee_code;
                        }else{
                            $geu = new G_Employee_User();
                            $geu->setCompanyStructureId($company_structure_id);
                            $geu->setEmployeeId($e->getId());        
                            $geu->setUsername($excel_username);                
                            $geu->setPassword($excel_password);                
                            $geu->setRoleId($role_id);                
                            $geu->setDateCreated(date("Y-m-d 00:00:00"));                             
                            $geu->setIsArchive('No');  
                            $geu->save();
                            $imported_count++;
                        }
                        
                    }
                } else {
                    $error_count++;
                    $error_employee_code++;
                    $code[] = $excel_employee_code;
                }

            }
        }
        $this->imported_records             = $imported_count;
        $this->error_count                  = $error_count;
        $this->error_employee_code          = $error_employee_code;
        $this->error_employee_user          = $error_employee_user;
        $this->error_username               = $error_username;
        $this->error_invalid_username       = $error_invalid_username;
        $this->total_records                = $total_row;

        $this->code                         = $code;
        $this->existing_username            = $existing_username;
        $this->existing_employee_user       = $existing_employee_user;
        $this->existing_invalid_username    = $existing_invalid_username;
    }

}
?>