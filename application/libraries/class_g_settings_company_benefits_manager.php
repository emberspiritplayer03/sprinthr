<?php
class G_Settings_Company_Benefits_Manager {

	public static function save(G_Company_Benefits $gcb) {
        if (G_Settings_Company_Benefits_Helper::isIdExist($gcb) > 0) {
            $sql_start = "UPDATE " . G_SETTINGS_COMPANY_BENEFITS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gcb->getId());
        } else {
            $sql_start = "INSERT INTO " . G_SETTINGS_COMPANY_BENEFITS . " ";
            $sql_end   = " ";
        }

        $sql = $sql_start . "

			SET
				company_structure_id =" . Model::safeSql($gcb->getCompanyStructureId()) . ",
				benefit_code   		 =" . Model::safeSql($gcb->getBenefitCode()) . ",
				benefit_name 		 =" . Model::safeSql($gcb->getBenefitName()) . ",				
				benefit_description  =" . Model::safeSql($gcb->getBenefitDescription()) . ",
				benefit_type 		 =" . Model::safeSql($gcb->getBenefitType()) . ",			
				benefit_amount 		 =" . Model::safeSql($gcb->getBenefitAmount()) . ",				
				is_archived  		 =" . Model::safeSql($gcb->getIsArchive()) . ",				
				is_taxable  		 =" . Model::safeSql($gcb->getIsTaxable()) . ",				
				date_created  		 =" . Model::safeSql($gcb->getDateCreated()) . " "
                . $sql_end . "	
		";
        Model::runSql($sql);
        return mysql_insert_id();
    }
	
	public static function archive(G_Company_Benefits $gcb) {
        if (G_Settings_Company_Benefits_Helper::isIdExist($gcb) > 0) {
            $sql_start = "UPDATE " . G_SETTINGS_COMPANY_BENEFITS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gcb->getId());

       		$sql = $sql_start . "
			SET						
				is_archived  		 =" . Model::safeSql(G_Settings_Company_Benefits::YES) . " "
                . $sql_end . "	
			";
		
       		Model::runSql($sql);
        
		}
    }
	
	public static function restore(G_Company_Benefits $gcb) {
        if (G_Settings_Company_Benefits_Helper::isIdExist($gcb) > 0) {
            $sql_start = "UPDATE " . G_SETTINGS_COMPANY_BENEFITS . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gcb->getId());

       		$sql = $sql_start . "
			SET						
				is_archived  		 =" . Model::safeSql(G_Settings_Company_Benefits::NO) . " "
                . $sql_end . "	
			";
		
       		Model::runSql($sql);
        
		}
    }

    public static function delete(G_Company_Benefits $gcb) {
        if (G_Settings_Company_Benefits_Helper::isIdExist($gcb) > 0) {
            $sql = "
				DELETE FROM " . G_SETTINGS_COMPANY_BENEFITS . "
				WHERE id =" . Model::safeSql($gcb->getId());
            Model::runSql($sql);
        }
    }
}
?>