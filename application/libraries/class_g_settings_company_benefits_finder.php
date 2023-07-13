<?php
class G_Settings_Company_Benefits_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_COMPANY_BENEFITS ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . G_SETTINGS_COMPANY_BENEFITS ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}

	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}

    private static function newObject($row) {
               
        $gcb = new G_Settings_Company_Benefits();
        $gcb->setId($row['id']);
        $gcb->setCompanyStructureId($row['company_structure_id']);
        $gcb->setBenefitCode($row['benefit_code']);
        $gcb->setBenefitName($row['benefit_name']);
        $gcb->setBenefitDescription($row['benefit_description']);
		$gcb->setBenefitType($row['benefit_type']);
		$gcb->setBenefitAmount($row['benefit_amount']);
		$gcb->setIsTaxable($row['is_taxable']);
        $gcb->setIsArchive($row['is_archived']);
        $gcb->setDateCreated($row['date_created']);        
        return $gcb;
    }

}
?>