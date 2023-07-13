<?php
class G_Overtime_Allowance_Manager {

        public static function save(G_Overtime_Allowance $g) {
        if (G_Overtime_Allowance_Helper::isIdExist($g) > 0) {
            $sql_start = "UPDATE " . G_OVERTIME_ALLOWANCE . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($g->getId());
        } else {
            $sql_start = "INSERT INTO " . G_OVERTIME_ALLOWANCE . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
                object_id               =" . Model::safeSql($g->getObjectId()) . ",
                object_type             =" . Model::safeSql($g->getObjectType()) . ",
                applied_day_type        =" . Model::safeSql($g->getAppliedDayType()) . ",
				ot_allowance            =" . Model::safeSql($g->getOtAllowance()) . ",
				multiplier              =" . Model::safeSql($g->getMultiplier()) . ",
                max_ot_allowance        =" . Model::safeSql($g->getMaxOtAllowance()) . ",	
                date_start              =" . Model::safeSql($g->getDateStart()) . ",    
                description             =" . Model::safeSql($g->getDescription()) . ",    	
                description_day_type    =" . Model::safeSql($g->getDescriptionDayType()) . ",      	
				date_created            =" . Model::safeSql($g->getDateCreated()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }

    public static function bulkInsert($values) {
        $sql = "INSERT INTO " . G_OVERTIME_ALLOWANCE . " (object_id,object_type,ot_allowance,multiplier,max_ot_allowance,date_start,description,date_created,applied_day_type,description_day_type)
            VALUES ".$values;
        Model::runSql($sql);
    }

    public static function delete(G_Overtime_Allowance $g) {
        if (G_Overtime_Allowance_Helper::isIdExist($g) > 0) {
            $sql = "
				DELETE FROM " . G_OVERTIME_ALLOWANCE . "
				WHERE id =" . Model::safeSql($g->getId());
            Model::runSql($sql);
        }
    }
}
?>