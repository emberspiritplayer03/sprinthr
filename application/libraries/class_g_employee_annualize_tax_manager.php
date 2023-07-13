<?php
class G_Employee_Annualize_Tax_Manager {

    public static function save(G_Employee_Annualize_Tax $at) {
        if (G_Employee_Annualize_Tax_Helper::isIdExist($at) > 0 ) {
            $sql_start = "UPDATE ". ANNUALIZE_TAX . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($at->getId());      
        }else{
            $sql_start = "INSERT INTO ". ANNUALIZE_TAX . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            employee_id             =" . Model::safeSql($at->getEmployeeId()) . ",
            year                    =" . Model::safeSql($at->getYear()) . ",                  
            from_date               =" . Model::safeSql($at->getFromDate()) . ",                  
            to_date                 =" . Model::safeSql($at->getToDate()) . ",                  
            gross_income_tax        =" . Model::safeSql($at->getGrossIncomeTax()) . ",                                        
            less_personal_exemption =" . Model::safeSql($at->getLessPersonalExemption()) . ",                                        
            taxable_income          =" . Model::safeSql($at->getTaxableIncome()) . ",                                        
            tax_due                 =" . Model::safeSql($at->getTaxDue()) . ",                                        
            tax_withheld_payroll    =" . Model::safeSql($at->getTaxWithHeldPayroll()) . ",                                        
            tax_refund_payable      =" . Model::safeSql($at->getTaxRefundPayable()) . ",                                        
            cutoff_start_date       =" . Model::safeSql($at->getCutoffStartDate()) . ",                                        
            cutoff_end_date         =" . Model::safeSql($at->getCutoffEndDate()) . ",                                        
            date_created            =" . Model::safeSql($at->getDateCreated()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    /**
    * Bulk insert 
    *
    *@param array a_bulk_insert
    *@param array fields
    *@return int
    */
    public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $total_records_inserted = 0;
        if( !empty($a_bulk_insert) && !empty($fields) ){
            $sql_values = implode(",", $a_bulk_insert);
            $sql_fields = implode(",", $fields);
            $sql        = "
                INSERT INTO " . ANNUALIZE_TAX . "({$sql_fields})
                VALUES{$sql_values}
            ";    
                                  
            Model::runSql($sql);
            $total_records_inserted = mysql_affected_rows();
           
        }
        return $total_records_inserted;
    }

    /**
    *Delete existing data by employeeids and cutoff start/end date
    *
    *@param array employee_ids
    *@param string cutoff_start
    *@param string cutoff_end
    *
    *@return void
    */

    public static function deleteExistingDataByEmployeeIdsAndDateRange($employee_ids = array(), $range = array() ){
        $sql_employee_ids = implode(",", $employee_ids);       
        if( !empty( $range ) ){
            $sql_add_conditions = "";
            $start_date = date("Y-m-d",strtotime($range['from']));
            $end_date   = date("Y-m-d",strtotime($range['to']));

            if( !empty($employee_ids) ){
                $string_employee_ids = implode(",", $employee_ids);
                $sql_add_conditions  = " AND employee_id IN({$string_employee_ids})";
            }

            $sql = "
                DELETE FROM ". ANNUALIZE_TAX ."
                WHERE from_date =" . Model::safeSql($start_date) . "
                    AND to_date =" . Model::safeSql($end_date) . "
                    {$sql_add_conditions}
            ";      
            Model::runSql($sql);
        }
    }

    public static function delete(G_Employee_Annualize_Tax $at){
        if(G_Employee_Annualize_Tax_Helper::isIdExist($at) > 0){
            $sql = "
                DELETE FROM ". ANNUALIZE_TAX ."               
                WHERE id =" . Model::safeSql($at->getId());
            Model::runSql($sql);
        }
    }
}
?>