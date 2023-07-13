<?php
class G_Request_Approver_Requestor_Manager {

    public static function save(G_Request_Approver_Requestor $grr) {
        if (G_Request_Approver_Requestor_Helper::isIdExist($grr) > 0 ) {
            $sql_start = "UPDATE ". REQUEST_APPROVERS_REQUESTORS . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($grr->getId());      
        }else{
            $sql_start = "INSERT INTO ". REQUEST_APPROVERS_REQUESTORS . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            request_approvers_id = " . Model::safeSql($grr->getRequestApproversId()) . ",
            employee_id          = " . Model::safeSql($grr->getEmployeeId()) . ",                  
            employee_name        = " . Model::safeSql($grr->getEmployeeName()) . "
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
                INSERT INTO " . REQUEST_APPROVERS_REQUESTORS . "({$sql_fields})
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
                DELETE FROM ". REQUEST_APPROVERS_REQUESTORS ."               
                WHERE request_approvers_id =" . Model::safeSql($request_approvers_id);
            Model::runSql($sql);
        }

        return $is_success;
    }

    public static function delete(G_Request_Approver_Requestor $grr){
        if(G_Request_Approver_Requestor_Helper::isIdExist($grr) > 0){
            $sql = "
                DELETE FROM ". REQUEST_APPROVERS_REQUESTORS ."               
                WHERE id =" . Model::safeSql($grr->getId());
            Model::runSql($sql);
        }
    }
}
?>