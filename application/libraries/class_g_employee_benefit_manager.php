<?php
class G_Employee_Benefit_Manager {

        public static function save(G_Employee_Benefit $geb) {
        if (G_Employee_Benefit_Helper::isIdExist($geb) > 0) {
            $sql_start = "UPDATE " . G_EMPLOYEE_BENEFITS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($geb->getId());
        } else {
            $sql_start = "INSERT INTO " . G_EMPLOYEE_BENEFITS . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				obj_id	      =" . Model::safeSql($geb->getObjId()) . ",
				obj_type      =" . Model::safeSql($geb->getObjType()) . ",
				apply_to_all  =" . Model::safeSql($geb->getApplyToAll()). ",
				benefit_id    =" . Model::safeSql($geb->getBenefitId()) . ",				
				date_created  =" . Model::safeSql($geb->getDateCreated()) . " "
                . $sql_end . "	
		";
		
        Model::runSql($sql);
        return mysql_insert_id();
    }
	
	public static function deleteAllByBenefitId($benefit_id) {
        $sql = "
			DELETE FROM " . G_EMPLOYEE_BENEFITS . "
			WHERE benefit_id =" . Model::safeSql($benefit_id);		
		Model::runSql($sql);
    }

    public static function delete(G_Employee_Benefit $geb) {
        if (G_Employee_Benefit_Helper::isIdExist($geb) > 0) {
            $sql = "
				DELETE FROM " . G_EMPLOYEE_BENEFITS . "
				WHERE id =" . Model::safeSql($geb->getId());
            Model::runSql($sql);
        }
    }
}
?>