<?php
class G_Group_Restday_Manager {

    public static function save(G_Group_Restday $grd) {
        if (G_Group_Restday_Helper::isIdExist($grd) > 0 ) {
            $sql_start = "UPDATE ". GROUP_RESTDAY . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($grd->getId());      
        }else{
            $sql_start = "INSERT INTO ". GROUP_RESTDAY . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            group_id = " . Model::safeSql($grd->getGroupId()) . ",                                         
            date     = " . Model::safeSql($grd->getDate()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function saveMultiple( $data = array() ) {
        if( !empty($data) ){           
            $sql_values = implode(",", $data);
            $sql        = "INSERT INTO ". GROUP_RESTDAY . "(group_id,date)VALUES {$sql_values}";           
            Model::runSql($sql);
        }
    }

    public static function delete(G_Group_Restday $grd){
        if(G_Group_Restday_Helper::isIdExist($grd) > 0){
            $sql = "
                DELETE FROM ". GROUP_RESTDAY ."               
                WHERE id =" . Model::safeSql($grd->getId());
            Model::runSql($sql);
        }
    }
}
?>