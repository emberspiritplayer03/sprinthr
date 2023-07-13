<?php
class G_Company_Info_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_INFO ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function findByCompanyStructureId($csid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_INFO ." 
			WHERE company_structure_id = " . Model::safeSql($csid) ."			
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . COMPANY_INFO ." 			
			ORDER BY company_structure_id ASC			
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
		$gci = new G_Company_Info($row['id']);
		$gci->setCompanyStructureId($row['company_structure_id']);
		$gci->setAddress($row['address']);
		$gci->setPhone($row['phone']);	
		$gci->setFax($row['fax']);		
		$gci->setAddress1($row['address1']);
		$gci->setCity($row['city']);
		$gci->setAddress2($row['address2']);
		$gci->setState($row['state']);
		$gci->setZipCode($row['zip_code']);
		$gci->setRemarks($row['remarks']);
		$gci->setSssNumber($row['sss_number']);
		$gci->setPagibigNumber($row['pagibig_number']);
		$gci->setTinNumber($row['tin_number']);
		$gci->setPhilhealthNumber($row['philhealth_number']);
		$gci->setCompanyLogo($row['company_logo']);
		return $gci;
	}
}
?>