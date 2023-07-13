<?php
class G_Role_Actions_Manager {

        public static function save(G_Role_Actions $gra) {
        if (G_Role_Actions_Helper::isIdExist($gra) > 0) {
            $sql_start = "UPDATE " . ROLE_ACTIONS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gra->getId());
        } else {
            $sql_start = "INSERT INTO " . ROLE_ACTIONS . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				role_id =" . Model::safeSql($gra->getRoleId()) . ",
				module  =" . Model::safeSql($gra->getModule()) . ",			
				action  =" . Model::safeSql($gra->getAction()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }

    public static function bulkInsert($role_id = 0, $data = array()) {
        $return = false;
        $values = array();

        if( !empty($data) && $role_id > 0 ){
            foreach( $data as $key => $values ){
                foreach($values as $sub_key => $value)
                $mod_values[] = "(" . $role_id . "," . Model::safeSql($key) . "," . Model::safeSql($sub_key) . "," . Model::safeSql($value) . ")";
            }

            if( !empty($mod_values) ){
                $sql_values = implode(",", $mod_values);
                $sql = " 
                    INSERT INTO " . ROLE_ACTIONS . "(role_id,parent_module,module,action)VALUES{$sql_values}
                ";
             
                Model::runSql($sql);
                $return = true;
            }
        }

        return $return;
    }

    public static function deleteAllActionsByRoleId( $role_id = 0 ) {
        $return = false;
        if( $role_id > 0 ){
            $sql = "
                DELETE FROM " . ROLE_ACTIONS . "
                WHERE role_id =" . Model::safeSql($role_id);       
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }

    public static function delete(G_Role_Actions $gra) {
        if (G_Role_Actions_Helper::isIdExist($gra) > 0) {
            $sql = "
				DELETE FROM " . ROLE_ACTIONS . "
				WHERE id =" . Model::safeSql($gra->getId());
            Model::runSql($sql);
        }
    }
}
?>