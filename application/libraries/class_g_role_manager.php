<?php
class G_Role_Manager {

    public static function save(G_Role $gr) {
        if (G_Role_Helper::isIdExist($gr) > 0) {
            $sql_start = "UPDATE " . ROLES . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gr->getId());
        } else {
            $sql_start = "INSERT INTO " . ROLES . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				name          =" . Model::safeSql($gr->getName()) . ",
				description   =" . Model::safeSql($gr->getDescription()) . ",			
                is_archive    =" . Model::safeSql($gr->getIsArchive()) . ",            
                date_created  =" . Model::safeSql($gr->getDateCreated()) . ",            	
				last_modified =" . Model::safeSql($gr->getLastModified()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }

    public static function delete(G_Role $gr) {
        if (G_Role_Helper::isIdExist($gr) > 0) {
            $sql = "
				DELETE FROM " . ROLES . "
				WHERE id =" . Model::safeSql($gr->getId());
            Model::runSql($sql);
        }
    }
}
?>