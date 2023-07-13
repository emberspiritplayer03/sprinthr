<?php
	class G_Frequency_Finder {

		public static function findAll()
		{
			$sql = "
			SELECT * 
			FROM " .G_FREQUENCY. " 
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
		
		$e = new G_Frequency;
		$e->setId($row['id']);
		$e->setFrequencyType($row['frequency_type']);
	

	//	print_r($e);
		return $e;
	}

	}
?>