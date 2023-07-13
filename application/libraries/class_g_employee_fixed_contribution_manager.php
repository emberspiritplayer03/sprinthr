<?php
class G_Employee_Fixed_Contribution_Manager {

    public static function save(G_Employee_Fixed_Contribution $gefc) {
        if (G_Employee_Fixed_Contribution_Helper::isIdExist($gefc) > 0 ) {
            $sql_start = "UPDATE ". G_FIXED_CONTRI . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($gefc->getId());      
        }else{
            $sql_start = "INSERT INTO ". G_FIXED_CONTRI . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            employee_id   = " . Model::safeSql($gefc->getEmployeeId()) . ",
            type         = " . Model::safeSql($gefc->getType()) . ",                  
            ee_amount    = " . Model::safeSql($gefc->getEEAmount()) . ",                  
            er_amount    = " . Model::safeSql($gefc->getERAmount()) . ",                              
            is_activated = " . Model::safeSql($gefc->getIsActivated()) . "
             "
            . $sql_end ."   
        
        ";               
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function delete(G_Employee_Fixed_Contribution $gefc){
        if(G_Employee_Fixed_Contribution_Helper::isIdExist($gr) > 0){
            $sql = "
                DELETE FROM ". G_FIXED_CONTRI ."               
                WHERE id =" . Model::safeSql($gefc->getId());
            Model::runSql($sql);
        }
    }

    public static function deleteAllByEmployeeId($employee_id = null){
        $sql = "
            DELETE FROM ". G_FIXED_CONTRI ."               
            WHERE employee_id =" . Model::safeSql($employee_id);        
        Model::runSql($sql);
    }
}
?>