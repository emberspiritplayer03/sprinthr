<?php
class G_Break_Time_Schedule_Details_Manager {

    public static function save(G_Break_Time_Schedule_Details $gbd) {
        if (G_Break_Time_Schedule_Details_Helper::isIdExist($gbd) > 0 ) {
            $sql_start = "UPDATE ". BREAK_TIME_SCHEDULE_DETAILS . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($gbd->getId());      
        }else{
            $sql_start = "INSERT INTO ". BREAK_TIME_SCHEDULE_DETAILS . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            header_id = " . Model::safeSql($gbd->getHeaderId()) . ",
            obj_id    = " . Model::safeSql($gbd->getObjId()) . ",                  
            obj_type  = " . Model::safeSql($gbd->getObjType()) . ",                  
            break_in  = " . Model::safeSql($gbd->getBreakIn()) . ",                  
            break_out = " . Model::safeSql($gbd->getBreakOut()) . ",  
            applied_to_legal_holiday   = " . Model::safeSql($gbd->getAppliedToLegalHoliday()) . ",  
            applied_to_special_holiday = " . Model::safeSql($gbd->getAppliedToSpecialHoliday()) . ",  
            applied_to_restday         = " . Model::safeSql($gbd->getAppliedToRestDay()) . ",  
            applied_to_regular_day     = " . Model::safeSql($gbd->getAppliedToRegularDay()) . ",  
            to_deduct = " . Model::safeSql($gbd->getToDeduct()) . ",
            to_required_logs = " . Model::safeSql($gbd->getToRequiredLogs()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function deleteByHeaderId( $header_id = 0 ){
         $sql = "
            DELETE FROM ". BREAK_TIME_SCHEDULE_DETAILS ."               
            WHERE header_id =" . Model::safeSql($header_id);
        Model::runSql($sql);
    }

    public static function delete(G_Break_Time_Schedule_Details $gbd){
        if(G_Break_Time_Schedule_Details_Helper::isIdExist($gr) > 0){
            $sql = "
                DELETE FROM ". BREAK_TIME_SCHEDULE_DETAILS ."               
                WHERE id =" . Model::safeSql($gbd->getId());
            Model::runSql($sql);
        }
    }
}
?>