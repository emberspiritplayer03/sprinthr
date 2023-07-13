<?php
class G_Overtime_Error_Finder {
    public static $default_fields = "o.*";
    public static $limit_string = '';
    public static $count_fields = '';
    public static $is_count_records = false;

    public static function setLimit($start, $end = 0) {
        $start = (int) $start;
        $end = (int) $end;
        if ($start >= 0 && $end >= 1) {
            self::$limit_string = " LIMIT {$start}, {$end}";
        } else if ($start >= 1) {
            self::$limit_string = " LIMIT {$start}";
        }
    }

    public static function countRecords() {
        self::$is_count_records = true;
        self::$count_fields = 'COUNT(*) AS total';
    }

    public static function getFields() {
        if (self::$is_count_records) {
            return self::$count_fields;
        } else {
            return self::$default_fields;
        }
    }

    public static function findByEmployeeIdAndDate($employee_id, $date) {
        $sql = "
			SELECT * FROM " . G_OVERTIME_ERROR . " o
			WHERE o.employee_id = " . Model::safeSql($employee_id) . "
			AND o.date_attendance = " . Model::safeSql($date) . "
			LIMIT 1
		";
        return self::getRecord($sql);
    }

    public static function findUnfixedByEmployeeIdAndDate($employee_id, $date) {
        $sql = "
			SELECT * FROM " . G_OVERTIME_ERROR . " o
			WHERE o.employee_id = " . Model::safeSql($employee_id) . "
			AND o.date_attendance = " . Model::safeSql($date) . "
			AND o.is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
			LIMIT 1
		";
        return self::getRecord($sql);
    }

	public static function findImportError($total_error) {
		$sql = "
			SELECT id,employee_code,employee_name, date_attendance,time_in,time_out,message FROM
			(SELECT id,employee_code,date_attendance,time_in,time_out,message FROM " . G_OVERTIME_ERROR . " ORDER BY ID DESC LIMIT " . Model::safeSql($total_error) .")	 as db
			ORDER BY date_attendance ASC
		";
		return self::getRecords($sql);
	}
	
	public static function findAllErrorsNotFixed() {
		$sql = "
			SELECT ". self::getFields() ."
			FROM " . G_OVERTIME_ERROR . " o
			WHERE o.is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
			ORDER BY o.date_attendance DESC, o.time_in DESC
		";
		return self::getRecords($sql);
	}

    public static function findAllErrorsNotFixedByPeriod($start_date, $end_date) {
        $sql = "
			SELECT ". self::getFields() ."
			FROM " . G_OVERTIME_ERROR . " o
			WHERE o.is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
			AND o.date_attendance BETWEEN ". Model::safeSql($start_date) ." AND ". Model::safeSql($end_date) ."
			ORDER BY o.date_attendance DESC, o.time_in DESC
		";
        return self::getRecords($sql);
    }

    public static function findAllErrorsNotFixedByGroupId($group_id) {
        $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_OVERTIME_ERROR ." o, ". EMPLOYEE ." e
			WHERE o.is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
            AND o.employee_id = e.id
            AND e.department_company_structure_id = ". Model::safeSql($group_id) ."
			ORDER BY o.date_attendance DESC, o.time_in DESC
		";
        return self::getRecords($sql);
    }

    public static function findAllErrorsNotFixedByGroupIdAndPeriod($group_id, $start_date, $end_date) {
        $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_OVERTIME_ERROR ." o, ". EMPLOYEE ." e
			WHERE o.is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
            AND o.employee_id = e.id
            AND e.department_company_structure_id = ". Model::safeSql($group_id) ."
            AND o.date_attendance BETWEEN ". Model::safeSql($start_date) ." AND ". Model::safeSql($end_date) ."
			ORDER BY o.date_attendance DESC, o.time_in DESC
		";
        return self::getRecords($sql);
    }

	/*
	 * DEPRECATED - USE G_Overtime_Error_Helper::countAllErrorsNotFixed();
	 */
	public static function countAllErrorsNotFixed() {
		$sql = "
			SELECT COUNT(id) AS total FROM " . G_OVERTIME_ERROR . "
			WHERE is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
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
        if (self::$is_count_records) {
            self::$count_fields = '';
            self::$is_count_records = false;
            return self::getTotalRecords($sql);
        }

        if (self::$limit_string) {
            $sql = $sql . self::$limit_string;
        }
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

    private static function getTotalRecords($sql) {
        $result = Model::runSql($sql);
        $row = Model::fetchAssoc($result);
        return $row['total'];
    }
	
	private static function newObject($row) {
		$error = new G_Overtime_Error;
		$error->setId($row['id']);
		$error->setEmployeeId($row['employee_id']);
		$error->setEmployeeCode($row['employee_code']);
		$error->setEmployeeName($row['employee_name']);
		$error->setDate($row['date_attendance']);
		$error->setTimeIn($row['time_in']);
		$error->setTimeOut($row['time_out']);
		$error->setMessage($row['message']);
		$error->setErrorTypeId($row['error_type_id']);
        $error->setIsFixed($row['is_fixed']);

		return $error;
	}
}
?>