<?php
class G_Sprint_Variables_Manager {

    public static function save(G_Sprint_Variables $sv) {
        if (G_Sprint_Variables_Helper::isIdExist($sv) > 0) {
            $sql_start = "UPDATE " . SPRINT_VARIABLES . " ";
            $sql_end   = "WHERE id =" . Model::safeSql($sv->getId());
            $action    = 'update';
        } else {
            $sql_start = "INSERT INTO " . SPRINT_VARIABLES . " ";
            $sql_end   = " ";
            $action    = 'insert';
        }

        $sql = $sql_start . "
            SET
                variable_name  =" . Model::safeSql($sv->getVariableName()) . ",                
                custom_value_a =" . Model::safeSql($sv->getCustomValueA()) . ", 
                value          =" . Model::safeSql($sv->getValue()) . " "
                . $sql_end . "  
        ";

        Model::runSql($sql);
        
        if( $action == 'insert' ){
            return mysql_insert_id();
        }else{
            return $sv->getId();
        }
    }

    public static function updateVariableValue(G_Sprint_Variables $sv) {
        $sql = "
            UPDATE " . SPRINT_VARIABLES . "
            SET                
                value         =" . Model::safeSql($sv->getValue()) . ",
                custom_value  =" . Model::safeSql($sv->getCustomValueA()) . ",
            WHERE variable_name =" . Model::safeSql($sv->getVariableName()) . "
        ";        
        Model::runSql($sql);
    }
}
?>