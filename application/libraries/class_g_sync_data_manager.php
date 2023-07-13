<?php
class G_Sync_Data_Manager {

        public static function save(G_Sync_Data $gai) {
        if (G_Sync_Data_Helper::isIdExist($gai) > 0) {
            $sql_start = "UPDATE " . SYNC_DATA . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gai->getId());
        } else {
            $sql_start = "INSERT INTO " . SYNC_DATA . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				table_name =" . Model::safeSql($gai->getTableName()) . ",
				pk_id_local  =" . Model::safeSql($gai->getPkIdLocal()) . ",
                pk_id_live  =" . Model::safeSql($gai->getPkIdLive()) . ",
                action  =" . Model::safeSql($gai->getAction()) . ",
                is_sync  =" . Model::safeSql($gai->getIsSync()) . ",
                date_created  =" . Model::safeSql($gai->getDateCreated()) . ",			
				date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                . $sql_end . "	
		";

        Model::runSql($sql);
        return mysql_insert_id();
    }


    public static function delete(G_Sync_Data $gai) {
        if (G_Sync_Data_Helper::isIdExist($gai) > 0) {
            $sql = "
				DELETE FROM " . SYNC_DATA . "
				WHERE id =" . Model::safeSql($gai->getId());
            Model::runSql($sql);
        }
    }

    public static function insertLocal(G_Sync_Data $gai) {
        if ($gai) {
            $sql_start = "INSERT INTO " . SYNC_DATA . " ";


            $sql = $sql_start . "

                SET
                    table_name =" . Model::safeSql($gai->getTableName()) . ",
                    pk_id_local  =" . Model::safeSql($gai->getPkIdLocal()) . ",
                    pk_id_live  =" . Model::safeSql($gai->getPkIdLive()) . ",
                    action  =" . Model::safeSql($gai->getAction()) . ",
                    is_sync  =" . Model::safeSql($gai->getIsSync()) . ",
                    date_created  =" . Model::safeSql($gai->getDateCreated()) . ",          
                    date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                    . $sql_end . "  
            ";

            Model::runSql($sql);
        } 
   
    }

    public static function updateLocal(G_Sync_Data $gai) {
        if ($gai) {
            $sql_start = "UPDATE " . SYNC_DATA . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gai->getId());

            $sql = $sql_start . "

                SET
                    table_name =" . Model::safeSql($gai->getTableName()) . ",
                    pk_id_local  =" . Model::safeSql($gai->getPkIdLocal()) . ",
                    pk_id_live  =" . Model::safeSql($gai->getPkIdLive()) . ",
                    action  =" . Model::safeSql($gai->getAction()) . ",
                    is_sync  =" . Model::safeSql($gai->getIsSync()) . ",
                    date_created  =" . Model::safeSql($gai->getDateCreated()) . ",          
                    date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                    . $sql_end . "  
            ";

            Model::runSql($sql);
        } 
   
    }

    public static function deleteLocal(G_Sync_Data $gai) {
        if ($gai) {
           $sql = "
                DELETE FROM " . SYNC_DATA . "
                WHERE id =" . Model::safeSql($gai->getId());
            Model::runSql($sql);
        } 
   
    }

    public static function insertLive(G_Sync_Data $gai) {
        if ($gai) {
            $sql_start = "INSERT INTO " . SYNC_DATA . " ";


            $sql = $sql_start . "

                SET
                    table_name =" . Model::safeSql($gai->getTableName()) . ",
                    pk_id_local  =" . Model::safeSql($gai->getPkIdLocal()) . ",
                    pk_id_live  =" . Model::safeSql($gai->getPkIdLive()) . ",
                    action  =" . Model::safeSql($gai->getAction()) . ",
                    is_sync  =" . Model::safeSql($gai->getIsSync()) . ",
                    date_created  =" . Model::safeSql($gai->getDateCreated()) . ",          
                    date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                    . $sql_end . "  
            ";

            $c = new Mysqli_Connect();
            $result = $c->query($sql);
        } 
   
    }

    public static function updateLive(G_Sync_Data $gai) {
        if ($gai) {
            $sql_start = "UPDATE " . SYNC_DATA . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gai->getId());

            $sql = $sql_start . "

                SET
                    table_name =" . Model::safeSql($gai->getTableName()) . ",
                    pk_id_local  =" . Model::safeSql($gai->getPkIdLocal()) . ",
                    pk_id_live  =" . Model::safeSql($gai->getPkIdLive()) . ",
                    action  =" . Model::safeSql($gai->getAction()) . ",
                    is_sync  =" . Model::safeSql($gai->getIsSync()) . ",
                    date_created  =" . Model::safeSql($gai->getDateCreated()) . ",          
                    date_modified  =" . Model::safeSql($gai->getDateModified()) . " "
                    . $sql_end . "  
            ";

            $c = new Mysqli_Connect();
            $result = $c->query($sql);
        } 
    }

    public static function deleteLive(G_Sync_Data $gai) {
        if ($gai) {
           $sql = "
                DELETE FROM " . SYNC_DATA . "
                WHERE id =" . Model::safeSql($gai->getId());

            $c = new Mysqli_Connect();
            $result = $c->query($sql);
        } 
   
    }

}
?>