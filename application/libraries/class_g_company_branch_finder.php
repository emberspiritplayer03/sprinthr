<?php
class G_Company_Branch_Finder {

	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}
	
	public static function searchByCompanyName($query) {
			
	}
	
	public static function findByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."	
			".$order_by."
			".$limit."		
		";		

		return self::getRecords($sql);
	}

    public static function findMain() {
        /*
         * TODO REMOVE MAGIC NUMBER
         */
        $sql = "
			SELECT *
			FROM " . COMPANY_BRANCH ."
			WHERE company_structure_id = 1
			LIMIT 1
		";

        return self::getRecord($sql);
    }
	
	public static function findAllIsNotArchiveByCompanyStructureId($csid, $order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 
			WHERE company_structure_id =". Model::safeSql($csid) ."	
				AND is_archive =" . Model::safeSql(G_Company_Branch::NO) . " 
			".$order_by."
			".$limit."		
		";		

		return self::getRecords($sql);
	}
	
	public static function findByLocationId($lid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 
			WHERE location_id =". Model::safeSql($lid) ."			
		";
		return self::getRecords($sql);
	}
	
	public static function findByName($name){
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 
			WHERE name =". Model::safeSql($name) ."			
		";
		return self::getRecord($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . COMPANY_BRANCH ." 		
		";
		return self::getRecords($sql);
	}
	
	public static function findByHoliday(G_Holiday $h) {
		$sql = "
			SELECT b.* 
			FROM " . COMPANY_BRANCH ." b, ". G_HOLIDAY_BRANCH ." hb
			WHERE b.id = hb.company_branch_id
			AND hb.holiday_id = ". Model::safeSql($h->getId()) ."
			ORDER BY b.name
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
		$gcb = new G_Company_Branch($row['id']);
		$gcb->setCompanyStructureId($row['company_structure_id']);
		$gcb->setName($row['name']);
		$gcb->setProvince($row['province']);	
		$gcb->setCity($row['city']);				
		$gcb->setAddress($row['address']);
		$gcb->setZipCode($row['zip_code']);
		$gcb->setPhone($row['phone']);
		$gcb->setFax($row['fax']);
		$gcb->setLocationId($row['location_id']);
		$gcb->setIsArchive($row['is_archive']);
		return $gcb;
	}
}
?>