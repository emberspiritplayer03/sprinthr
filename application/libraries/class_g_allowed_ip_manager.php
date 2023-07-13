<?php
class G_Allowed_Ip_Manager {

    public static function save(G_Allowed_Ip $gai) {
        if (G_Allowed_Ip_Helper::isIdExist($gai) > 0) {
            $sql_start = "UPDATE " . ALLOWED_IP . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gai->getId());
        } else {
            $sql_start = "INSERT INTO " . ALLOWED_IP . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				ip_address =" . Model::safeSql($gai->getIpAddress()) . ",
				employee_id  =" . Model::safeSql($gai->getEmployeeId()) . ",
                date_created  =" . Model::safeSql($gai->getDateCreated()) . ",			
				date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }


    public static function delete(G_Allowed_Ip $gai) {
        if (G_Allowed_Ip_Helper::isIdExist($gai) > 0) {
            $sql = "
				DELETE FROM " . ALLOWED_IP . "
				WHERE id =" . Model::safeSql($gai->getId());
            Model::runSql($sql);
        }
    }
}
?>