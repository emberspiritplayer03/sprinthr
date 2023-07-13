<?php
class G_Payroll_Variables_Manager {

    public static function save(G_Payroll_Variables $gpv) {
        if (G_Payroll_Variables_Helper::isIdExist($gpv) > 0) {
            $sql_start = "UPDATE " . PAYROLL_VARIABLES . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gpv->getId());
            $sql = $sql_start . "
            SET               
                number_of_days =" . Model::safeSql($gpv->getNumberOfDays()) . " "
                . $sql_end . "  
            ";
            Model::runSql($sql);
            return mysql_insert_id();
        }       
    }

    public static function updateSelectedFieldValue( $id = 0, $field = '', $value = '' ) {
        $return = false;

       if( $id > 0 && $field != "" && $value != "" ){
            $sql_start = "UPDATE " . PAYROLL_VARIABLES . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($id);
            $sql = $sql_start . "
            SET               
                {$field} =" . Model::safeSql($value) . " "
                . $sql_end . "  
            ";
            Model::runSql($sql);
            $return = true;
       } 

        return $return;
    }
}
?>