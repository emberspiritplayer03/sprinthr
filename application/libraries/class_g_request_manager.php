<?php
class G_Request_Manager {

    public static function save(G_Request $gr) {
        if (G_Request_Helper::isIdExist($gr) > 0 ) {
            $sql_start = "UPDATE ". REQUESTS . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($gr->getId());      
        }else{
            $sql_start = "INSERT INTO ". REQUESTS . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            requestor_employee_id = " . Model::safeSql($gr->getRequestorEmployeeId()) . ",
            request_id            = " . Model::safeSql($gr->getRequestId()) . ",                  
            request_type          = " . Model::safeSql($gr->getRequestType()) . ",                  
            approver_employee_id  = " . Model::safeSql($gr->getApproverEmployeeId()) . ",                  
            approver_name         = " . Model::safeSql($gr->getApproverName()) . ",                  
            status                = " . Model::safeSql($gr->getStatus()) . ",                  
            is_lock               = " . Model::safeSql($gr->getIsLock()) . ",                  
            remarks               = " . Model::safeSql($gr->getRemarks()) . ",                                      
            action_date           = " . Model::safeSql($gr->getActionDate()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function updateRequestApproversDataById( $data = array() ){
        if( !empty($data) ){
            $sql_start = "UPDATE ". REQUESTS;
            foreach( $data as $key => $value ){
                $sql_condition = "WHERE id =" . Model::safeSql($key);
                foreach( $value as $subKey => $subValue ){
                    $set_data[] = $subKey . "=" . Model::safeSql($subValue);
                }
                $string_set_data = implode(",", $set_data);
                $sql_set_data    = "SET {$string_set_data}";
                $sql = $sql_start . " " . $sql_set_data . " " . $sql_condition;                        
                Model::runSql($sql);
            }
        }

        return true;
    }

    public static function bulkInsertRequests($values){
        $sql = "INSERT INTO ". REQUESTS . " (requestor_employee_id,request_id,request_type,approver_employee_id,approver_name,status,is_lock,remarks,action_date) 
            VALUES ".$values."
        ";

        Model::runSql($sql);
        return mysql_insert_id();    
    }

    public static function resetToPendingApproversStatusByRequestIdAndRequestType( $request_id = 0, $request_type = '' ){
        if( $request_id > 0 && $request_type != '' ){
            $sql = "
                UPDATE ". REQUESTS ."               
                SET status =" . Model::safeSql(G_Request::PENDING) . ",
                    is_lock =" . Model::safeSql(G_Request::NO) . ",
                    action_date = NOW()
                WHERE request_id =" . Model::safeSql($request_id) . "
                    AND request_type =" . Model::safeSql($request_type) . "
            ";
            Model::runSql($sql);
        }
    }

    public static function resetToApprovedApproversStatusByRequestIdAndRequestType( $request_id = 0, $request_type = '' ){
        if( $request_id > 0 && $request_type != '' ){
            $sql = "
                UPDATE ". REQUESTS ."               
                SET status =" . Model::safeSql(G_Request::APPROVED) . ",
                    is_lock =" . Model::safeSql(G_Request::YES) . ",
                    action_date = NOW()
                WHERE request_id =" . Model::safeSql($request_id) . "
                    AND request_type =" . Model::safeSql($request_type) . "
            ";
            Model::runSql($sql);
        }
    }

    public static function resetToDisApprovedApproversStatusByRequestIdAndRequestType( $request_id = 0, $request_type = '' ){
        if( $request_id > 0 && $request_type != '' ){
            $sql = "
                UPDATE ". REQUESTS ."               
                SET status =" . Model::safeSql(G_Request::DISAPPROVED) . ",
                    is_lock =" . Model::safeSql(G_Request::YES) . ",
                    action_date = NOW()
                WHERE request_id =" . Model::safeSql($request_id) . "
                    AND request_type =" . Model::safeSql($request_type) . "
            ";
            Model::runSql($sql);
        }
    }

    public static function deleteAllRequestByRequestIdAndRequestType($request_id = 0, $request_type = ''){
        if( $request_id > 0 && $request_type != '' ){
            $sql = "
                DELETE FROM ". REQUESTS ."               
                WHERE request_id =" . Model::safeSql($request_id) . "
                    AND request_type =" . Model::safeSql($request_type) . "
            ";
            Model::runSql($sql);
        }
    }

    public static function delete(G_Request $gr){
        if(G_Request_Helper::isIdExist($gr) > 0){
            $sql = "
                DELETE FROM ". REQUESTS ."               
                WHERE id =" . Model::safeSql($gr->getId());
            Model::runSql($sql);
        }
    }
}
?>