<?php
class G_Request_Approver_Level_Manager {

    public static function save(G_Request_Approver_Level $grl) {
        if (G_Request_Approver_Level_Helper::isIdExist($grl) > 0 ) {
            $sql_start = "UPDATE ". REQUEST_APPROVERS_LEVEL . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($grl->getId());      
        }else{
            $sql_start = "INSERT INTO ". REQUEST_APPROVERS_LEVEL . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            request_approvers_id = " . Model::safeSql($grl->getRequestApproversId()) . ",
            employee_id          = " . Model::safeSql($grl->getEmployeeId()) . ",       
            employee_name        = " . Model::safeSql($grl->getEmployeeName()) . ",          
            level                = " . Model::safeSql($grl->getLevel()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function bulkInsert( $values = array(), $fields = array() ){
        $is_success = false;
        if( !empty($values) && !empty($fields) ){
            $sql_values = implode(",", $values);
            $sql_fields = implode(",", $fields);
            $sql = "
                INSERT INTO " . REQUEST_APPROVERS_LEVEL . "({$sql_fields})
                VALUES {$sql_values}
            ";
            
            Model::runSql($sql);
            $is_success = true;
        }

        return $is_success;

    }

    public static function deleteAllByRequestApproversId( $request_approvers_id = 0 ){
        $is_success = false;

        if( $request_approvers_id > 0 ){
            $sql = "
                DELETE FROM ". REQUEST_APPROVERS_LEVEL ."               
                WHERE request_approvers_id =" . Model::safeSql($request_approvers_id);
            Model::runSql($sql);
        }

        return $is_success;
    }

    public static function delete(G_Request_Approver_Level $grl){
        if(G_Request_Approver_Level_Helper::isIdExist($grl) > 0){
            $sql = "
                DELETE FROM ". REQUEST_APPROVERS_LEVEL ."               
                WHERE id =" . Model::safeSql($grl->getId());
            Model::runSql($sql);
        }
    }
}
?>