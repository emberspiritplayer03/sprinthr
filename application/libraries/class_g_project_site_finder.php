<?php
class G_Project_Site_Finder
{
	public static function findAll()
	{


		$sql = "

		 SELECT *
		 FROM " . G_PROJECT_SITES . "  
		 

		";

		return self::getRecords($sql);
	}

	public static function findProjectSiteByName($name)
	{


		$sql = "

	    	 SELECT id,name,location,description 
			 FROM " . G_PROJECT_SITES . "  
			 WHERE  LOWER(name) = " . Model::safeSql($name) . "
			 LIMIT 1 

	    	";

		return self::getRecord($sql);
	}

	public static function findById($id)
	{


		$sql = "

	    	 SELECT id,name,location,description 
			 FROM " . G_PROJECT_SITES . "  
			 WHERE id = " . Model::safeSql($id) . "
			 LIMIT 1 

	    	";

		return self::getRecord($sql);
	}


	private static function getRecord($sql)
	{
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


	private static function getRecords($sql)
	{
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


	private static function newObject($row)
	{

		$e = new G_Project_Site_Extends;

		$e->setId($row['id']);
		$e->setprojectname($row['name']);
		$e->setlocation($row['location']);
		$e->setProjectDescription($row['description']);
		$e->setDeviceId($row['device_id']);

		return $e;
	}
}
