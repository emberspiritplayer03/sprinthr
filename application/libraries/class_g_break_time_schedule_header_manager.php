<?php
class G_Break_Time_Schedule_Header_Manager {

    public static function save(G_Break_Time_Schedule_Header $gbh) {
        if (G_Break_Time_Schedule_Header_Helper::isIdExist($gbh) > 0 ) {
            $sql_start = "UPDATE ". BREAK_TIME_SCHEDULE_HEADER . " ";
            $sql_end   = "WHERE id = ". Model::safeSql($gbh->getId());      
        }else{
            $sql_start = "INSERT INTO ". BREAK_TIME_SCHEDULE_HEADER . " ";
            $sql_end  = "";     
        }
        
        $sql = $sql_start ."
            SET
            schedule_in     = " . Model::safeSql($gbh->getScheduleIn()) . ",
            schedule_out    = " . Model::safeSql($gbh->getScheduleOut()) . ",                  
            break_time_schedules = " . Model::safeSql($gbh->getBreakTimeSchedules()) . ",                  
            applied_to      = " . Model::safeSql($gbh->getAppliedTo()) . ",                            
            date_start      = " . Model::safeSql($gbh->getDateStart()) . ",
            date_created    = " . Model::safeSql($gbh->getDateCreated()) . "
             "
            . $sql_end ."   
        
        ";        
        Model::runSql($sql);
        return mysql_insert_id();       
    }

    public static function delete(G_Break_Time_Schedule_Header $gbh){
        if(G_Break_Time_Schedule_Header_Helper::isIdExist($gbh) > 0){
            $sql = "
                DELETE FROM ". BREAK_TIME_SCHEDULE_HEADER ."               
                WHERE id =" . Model::safeSql($gbh->getId());
            Model::runSql($sql);
        }
    }
}
?>