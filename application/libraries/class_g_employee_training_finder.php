<?php
class G_Employee_Training_Finder {

	public static $sql2;

	public static function findById($id) {
		$sql = "
			SELECT
				*

			FROM ". G_EMPLOYEE_TRAINING ." e
			WHERE e.id = ". Model::safeSql($id) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}


public static function findByEmployeeId($employee_id) {
        $sql = "
            SELECT
                *

            FROM ". G_EMPLOYEE_TRAINING ." e
            WHERE e.employee_id = ". Model::safeSql($employee_id) ."

        ";

        return self::getRecords($sql);
    }

	public static function returnSql($id, $from_date, $to_date, $provider, $location, $description)
	{
			$sql2 = "SELECT
			*

		FROM g_employee_training
		WHERE employee_id = ". Model::safeSql($id) ."
		AND from_date = ". Model::safeSql($from_date) ."
		AND to_date = ". Model::safeSql($to_date) ."
		AND provider = ". Model::safeSql($provider) ."
		AND location = ". Model::safeSql($location) ."
		AND description = ". Model::safeSql($description)."

	";
		return $sql2;
	}
	public static function findTraining($id, $from_date, $to_date, $provider, $location, $description)
	{
		//WHERE employee id,  from_date, to_date, provider, location

		$sql = "
			SELECT
				*

			FROM g_employee_training
			WHERE employee_id = ". Model::safeSql($id) ."
			AND from_date = ". Model::safeSql($from_date) ."
			AND to_date = ". Model::safeSql($to_date) ."
			AND provider = ". Model::safeSql($provider) ."
			AND location = ". Model::safeSql($location) ."
			AND description = ". Model::safeSql($description)."

		";


		//return self::getRecords($sql2);
		return self::getRecord($sql);
		//if returns true, training already exists; invalid entry
	}

	public static function findByEmployeeCode($employee_id) {
		$sql = "
			SELECT
				*

			FROM ". G_EMPLOYEE_TRAINING ." e
			WHERE e.employee_id = ". Model::safeSql($employee_id) ."

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

	private static function getRecords($sql2) {
		$result = Model::runSql($sql2);
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

		$e = new G_Employee_Training;
		$e->setId($row['id']);
		$e->setEmployeeId($row['employee_id']);
		$e->setFromDate($row['from_date']);
		$e->setToDate($row['to_date']);
		$e->setDescription($row['description']);
		$e->setProvider($row['provider']);
		$e->setLocation($row['location']);
		//$e->setCost($row['cost']);
		//$e->setRenewalDate($row['renewal_date']);

		return $e;
	}
}
?>
