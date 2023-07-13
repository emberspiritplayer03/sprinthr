<?php
class G_Employee_Evaluation_Finder {


    public static function getEvaluationFields() {
        return "ev.id, ev.employee_id, ev.score, ev.attachments, ev.evaluation_date, ev.next_evaluation_date, ev.is_archive, ev.date_created";
    }


    public static function findById($id){

    	 $sql = "
			SELECT ". self::getEvaluationFields() ."
			FROM g_employee_evaluation ev
			WHERE ev.id = ". Model::safeSql($id) ."
			LIMIT 1
		";
        return self::getRecord($sql);

    }

    public static function findByEmployeeId($employee_id){


    	 $sql = "
			SELECT ". self::getEvaluationFields() ."
			FROM g_employee_evaluation ev
			WHERE ev.employee_id = ". Model::safeSql($employee_id) ." AND is_archive='No'

			ORDER BY ev.evaluation_date DESC
			
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
		
		$e = new G_Employee_Evaluation;
		$e->setId($row['id']);
		$e->setEmployeeId2($row['employee_id']);
		$e->setEvaluationDate($row['evaluation_date']);
		$e->setNextEvaluationDate($row['next_evaluation_date']);
		$e->setDateCreated($row['date_created']);
		$e->setIsArchive($row['is_archive']);
		$e->setScore($row['score']);
		$e->setAttachment($row['attachments']);
	//	print_r($e);
		return $e;
	}
	


  }

  ?>