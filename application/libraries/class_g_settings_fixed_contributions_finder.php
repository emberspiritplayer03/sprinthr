<?php
class G_Settings_Fixed_Contributions_Finder {

	public function findAll(){
		$sql = "
			SELECT * from g_settings_fixed_contributions
			";

		return self::getRecords($sql);
	}

	public function findById($id){
		$sql = "
			SELECT * from g_settings_fixed_contributions
			where id=".  Model::safeSql($id) ."
			LIMIT 1";

		return self::getRecord($sql);
	}


	public function findByName($name){
		$sql = "
			SELECT * from g_settings_fixed_contributions
			where contribution=".  Model::safeSql($name) ."
			LIMIT 1";

		return self::getRecord($sql);
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
		//print_r($records);
		//echo $records->getId();
	
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
		
		$e = new G_Settings_Fixed_Contributions;
		$e->setId($row['id']);
		$e->setIsEnabled($row['is_enabled']);
		$e->setContributionName($row['contribution']);
		
	//	print_r($e);
		return $e;
	}

}


?>