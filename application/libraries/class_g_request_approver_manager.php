<?php
class G_Request_Approver_Manager {

    public static function save(G_Request_Approver $gra) {
        if (G_Request_Approver_Helper::isIdExist($gra) > 0 ) {
            $sql_start  = "UPDATE ". REQUEST_APPROVERS . " ";
            $sql_end    = "WHERE id = ". Model::safeSql($gra->getId());   
            $sql_action = "update";
        }else{
            $sql_start  = "INSERT INTO ". REQUEST_APPROVERS . " ";
            $sql_end    = "";     
            $sql_action = "insert";
        }
        
        $sql = $sql_start ."
            SET
            title           = " . Model::safeSql($gra->getTitle()) . ",
            approvers_name  = " . Model::safeSql($gra->getApproversName()) . ",
            requestors_name = " . Model::safeSql($gra->getRequestorsName()) . ",          
            date_created    = " . Model::safeSql($gra->getDateCreated()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        
        if( $sql_action == "update" ){
            $return_id = $gra->getId();
        }else{
            $return_id = mysql_insert_id();
        }
        
        return $return_id; 
    }

    public static function delete(G_Request_Approver $gra){
        if(G_Request_Approver_Helper::isIdExist($gra) > 0){
            $sql = "
                DELETE FROM ". REQUEST_APPROVERS ."               
                WHERE id =" . Model::safeSql($gra->getId());
            Model::runSql($sql);
        }
    }
}
?>