<?php
class G_Settings_Employee_Benefit_Manager {

        public static function save(G_Settings_Employee_Benefit $gseb) {
        if (G_Settings_Employee_Benefit_Helper::isIdExist($gseb) > 0) {
            $sql_start     = "UPDATE " . G_SETTINGS_EMPLOYEE_BENEFITS . " ";
            $sql_end       = "WHERE id = " . Model::safeSql($gseb->getId());
            $is_new_record = false;
        } else {
            $sql_start     = "INSERT INTO " . G_SETTINGS_EMPLOYEE_BENEFITS . " ";
            $sql_end       = " ";
            $is_new_record = true;
        }

        $sql = $sql_start . "

			SET
				code               =" . Model::safeSql($gseb->getCode()) . ",
				name               =" . Model::safeSql($gseb->getName()) . ",				
                description        =" . Model::safeSql($gseb->getDescription()) . ",              
                amount             =" . Model::safeSql($gseb->getAmount()) . ",               
                is_taxable         =" . Model::safeSql($gseb->getIsTaxable()) . ",              
                cutoff             =" . Model::safeSql($gseb->getCutOff()) . ",
                multiplied_by      =" . Model::safeSql($gseb->getMultipliedBy()) . ",
                is_archive         =" . Model::safeSql($gseb->getIsArchive()) . ",    
                date_created       =" . Model::safeSql($gseb->getDateCreated()) . ",              
				date_last_modified =" . Model::safeSql($gseb->getDateLastModified()) . " "
                . $sql_end . "	
		";

        Model::runSql($sql);
        
        if( $is_new_record ){
            $return_data = mysql_insert_id();
        }else{
            $return_data = $gseb->getId();
        }
        return $return_data;
    }

    public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $return['is_success'] = false;
        $return['last_id']    = 0;
        if( !empty( $a_bulk_insert ) ){

            if( !empty($fields) ){
                $sql_fields = implode(",", $fields);
            }else{
                $sql_fields = "amount,cutoff,is_taxable,is_archive,date_created,multiplied_by,code,name";
            }

            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . G_SETTINGS_EMPLOYEE_BENEFITS . "({$sql_fields})
                VALUES{$sql_values}
            ";                        
            Model::runSql($sql);
            
            $return['is_success'] = true;
            $return['last_id']    = mysql_insert_id();
        }
        return $return;
    }

    public static function delete(G_Settings_Employee_Benefit $gseb) {
        if (G_Settings_Employee_Benefit_Helper::isIdExist($gseb) > 0) {
            $sql = "
				DELETE FROM " . G_SETTINGS_EMPLOYEE_BENEFITS . "
				WHERE id =" . Model::safeSql($gseb->getId());
            Model::runSql($sql);
        }
    }
}
?>