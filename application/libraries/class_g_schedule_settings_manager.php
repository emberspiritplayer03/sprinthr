<?php
class G_Schedule_Settings_Manager {

        public static function save(G_Schedule_Settings $gra) {
        if (G_Schedule_Settings_Helper::isIdExist($gra) > 0) {
            $sql_start = "UPDATE " . V2_SCHEDULE_SETTINGS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gra->getId());
        } else {
            $sql_start = "INSERT INTO " . V2_SCHEDULE_SETTINGS . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
                shift       =   " . Model::safeSql($gra->getShift()) . ",
                flexible    =   " . Model::safeSql($gra->getFlexible()) . ",
                compressed  =   " . Model::safeSql($gra->getCompressed()) . ",
                staggered   =   " . Model::safeSql($gra->getStaggered()) . ",
                security    =   " . Model::safeSql($gra->getSecurity()) . ",
                actual      =   " . Model::safeSql($gra->getActual()) . ",
                per_trip    =   " . Model::safeSql($gra->getPerTrip()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }
}
?>