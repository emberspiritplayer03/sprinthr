<?php
class G_Employee_Project_Site_History_Finder {

  private static function newObject($row) {

    $e = new G_Employee_Project_Site_History;
    $e->setId($row['id']);
    $e->setEmployeeId($row['employee_id']);
    $e->setProjectId($row['project_id']);
    $e->setStartDate($row['start_date']);
    $e->setEndDate($row['end_date']);

    return $e;
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


  /*
    Get current project
  */
  public static function findCurrentProject(G_Employee $e) {
    $sql = "
      SELECT
      *
      FROM ". G_EMPLOYEE_JOB_HISTORY ." e
      WHERE e.employee_id = ". Model::safeSql($e->getId()) ."
      AND end_date=''
      LIMIT 1
    ";
    //echo $sql;
    return self::getRecord($sql);
  }


  public function getProjectSiteByEmployeeAndDate(G_Employee $e, $date){

    $sql = "
      SELECT
      *
      FROM ". G_PROJECT_SITES_HISTORY ." e WHERE
     ((". Model::safeSql($date) ." >= e.start_date AND (e.end_date = '0000-00-00' OR e.end_date = '')) OR (". Model::safeSql($date) ." >= e.start_date AND ". Model::safeSql($date) ." <= e.end_date))
      AND e.employee_id = ". Model::safeSql($e->getId()) ."
      AND e.start_date = 
        (
          SELECT es2.start_date 
          FROM ". G_PROJECT_SITES_HISTORY ." es2
          WHERE es2.start_date <= ". Model::safeSql($date) ."
          AND es2.employee_id = ". Model::safeSql($e->getId()) ."
          ORDER BY es2.start_date DESC
          LIMIT 1
        )
      ORDER BY e.start_date DESC
      LIMIT 1
    ";
    //echo $sql;

    //var_dump($sql);exit;
    return self::getRecord($sql);
  }

   public function getAllprojectSiteByEmployeeId($eid){

       $sql = "
          SELECT
          *
          FROM ". G_PROJECT_SITES_HISTORY ." e
          WHERE e.employee_id = ". Model::safeSql($eid) ."
        ";
        //echo $sql;
        return self::getRecords($sql);

    }






}
