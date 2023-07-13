<?php

class G_Settings_Grace_Period_Exempted_Finder {

  public static function findById($id) {
		$sql = "
			SELECT 
				*
			FROM ". G_GRACE_EXEMPTED ." e
			WHERE e.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
  }

  public function findByEmployeeId($eid){

  	$sql = "
			SELECT 
				*
			FROM ". G_GRACE_EXEMPTED ." e
			WHERE e.employee_id = ". Model::safeSql($eid) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);

  }


  public static function findAll() {
		$sql = "
			SELECT 
				gp.id, e.firstname, e.lastname, e.middlename, e.employee_code
			FROM ". G_GRACE_EXEMPTED ." gp, g_employee e
			where gp.employee_id = e.id
			ORDER BY e.lastname ASC	
		";
		//return self::getRecord($sql);
		$result = Model::runSql($sql,true);		
		return $result;
		
  }


  public static function isIdExist(G_Settings_Grace_Period_Exempted $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_GRACE_EXEMPTED ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
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
		
		$e = new G_Settings_Grace_Period_Exempted;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		
		return $e;
	}


}


?>