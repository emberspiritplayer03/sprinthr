<?php
class G_Employee_User_Manager {

        public static function save(G_Employee_User $geu) {
        if (G_Employee_User_Helper::isIdExist($geu) > 0) {
            $sql_start = "UPDATE " . G_EMPLOYEE_USER . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($geu->getId());
            $action    = "update";
        } else {
            $sql_start = "INSERT INTO " . G_EMPLOYEE_USER . " ";
            $sql_end   = " ";
            $action    = "save";
        }

        $sql = $sql_start . "

			SET
				company_structure_id =" . Model::safeSql($geu->getCompanyStructureId()) . ",
				employee_id          =" . Model::safeSql($geu->getEmployeeId()) . ",			
                username             =" . Model::safeSql($geu->getUsername()) . ",            
                password             =" . Model::safeSql($geu->getPassword()) . ",            
                role_id              =" . Model::safeSql($geu->getRoleId()) . ",            
                date_created         =" . Model::safeSql($geu->getDateCreated()) . ",            
                last_modified        =" . Model::safeSql($geu->getLastModified()) . ",            
				is_archive           =" . Model::safeSql($geu->getIsArchive()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        if( $action == "update" ){
            return $geu->getId();
        }else{
            return mysql_insert_id();
        }        
    }

    public static function delete(G_Employee_User $geu) {
        if (G_Employee_User_Helper::isIdExist($geu) > 0) {
            $sql = "
				DELETE FROM " . G_EMPLOYEE_USER . "
				WHERE id =" . Model::safeSql($geu->getId());
            Model::runSql($sql);
        }
    }

    public static function updatePasswordByEmployeeId($employee_id = 0, $password = '') {
        if( $employee_id > 0 ){
            $sql = "UPDATE " . G_EMPLOYEE_USER . " 
                SET password =" . Model::safeSql($password) . "
                WHERE employee_id =" . Model::safeSql($employee_id) . "
            ";
            Model::runSql($sql);
        }
    }
}
?>