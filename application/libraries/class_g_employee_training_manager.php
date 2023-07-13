<?php
class G_Employee_Training_Manager {

	public static function save(G_Employee_Training $e) {
		if (G_Employee_Training_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_TRAINING . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_TRAINING . "";
			$sql_end   = "";
		}

		/*$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			from_date		   	= " . Model::safeSql($e->getFromDate()) .",
			to_date		   		= " . Model::safeSql($e->getToDate()) .",
			description			= " . Model::safeSql($e->getDescription()) .",
			provider	   		= " . Model::safeSql($e->getProvider()) .",
			location	   		= " . Model::safeSql($e->getLocation()) .",
			cost		   		= " . Model::safeSql($e->getCost()) .",
			renewal_date   		= " . Model::safeSql($e->getRenewalDate()) ."
			"

			. $sql_end ."

		";	*/
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			from_date		   	= " . Model::safeSql($e->getFromDate()) .",
			to_date		   		= " . Model::safeSql($e->getToDate()) .",
			description			= " . Model::safeSql($e->getDescription()) .",
			provider	   		= " . Model::safeSql($e->getProvider()) .",
			location	   		= " . Model::safeSql($e->getLocation()) .
			""

			. $sql_end ."

		";
		Model::runSql($sql);
		return mysql_insert_id();
	}

	public static function insertTraining(G_Employee_Training $e)
	{
		$sql = "INSERT INTO ". G_EMPLOYEE_TRAINING .
		"(employee_id, from_date, to_date, description, provider, location)".
		"VALUES (
			".Model::safeSql($e->getEmployeeId()) . ",".
			"". Model::safeSql($e->getFromDate()) . ",".
			"". Model::safeSql($e->getToDate()) . ",".
			"". Model::safeSql($e->getDescription()) .",".
			"" . Model::safeSql($e->getProvider()) .",".
			"" . Model::safeSql($e->getLocation()) ."".
			")";

			Model::runSql($sql);
			return $sql;

	}

	public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
				$return = false;
				if( !empty( $a_bulk_insert ) ){
					$sql_fields = "employee_id, from_date, to_date, description, provider, location";

					if( !empty($fields) ){
						$sql_fields = implode(",", $fields);
					}

						$sql_values = implode(",", $a_bulk_insert);
						$sql        = "
								INSERT INTO " . G_EMPLOYEE_TRAINING . "({$sql_fields})
								VALUES{$sql_values}
						";

						Model::runSql($sql);
						$return = true;
				}
				return $return;
		}

	public static function delete(G_Employee_Training $e){
		if(G_Employee_Training_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_TRAINING ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}

	}
}
?>
