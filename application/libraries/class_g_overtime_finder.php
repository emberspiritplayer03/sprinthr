<?php
class G_Overtime_Finder {
    public static $default_fields = "o.id, o.employee_id, o.date as date, o.time_in, o.time_out, o.reason, o.status, o.is_archived, o.date_in, o.date_out, o.date_created";
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
	
	public static function findById($id) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o
			WHERE o.id = ". Model::safeSql($id) ."	
			LIMIT 1
		";        
		return self::getRecord($sql);
	}

    public static function findAllPending() {
        return self::findAllByStatus(G_Overtime::STATUS_PENDING);
    }

    public static function findAllPendingByPeriod($start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndPeriod(G_Overtime::STATUS_PENDING, $start_date, $end_date, $additional_query);
    }

    public static function findAllApprovedByPeriod($start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndPeriod(G_Overtime::STATUS_APPROVED, $start_date, $end_date, $additional_query);
    }

    public static function findAllDisapprovedByPeriod($start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndPeriod(G_Overtime::STATUS_DISAPPROVED, $start_date, $end_date, $additional_query);
    }

    public static function findAllByStatusAndPeriod($status, $start_date, $end_date, $additional_query = '') {
        $s_query = "";

        if( !empty($additional_query) ){
            $s_query = trim($additional_query);
        }

       /* $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o
			WHERE o.status = ". Model::safeSql($status) ."
			AND o.date BETWEEN ". Model::safeSql($start_date) ." AND ". Model::safeSql($end_date) ."
            {$s_query}
			ORDER BY o.date DESC
		";*/
        $sql = "
            SELECT ". self::getFields() ."
            FROM ". G_EMPLOYEE_OVERTIME ." o, ".G_EMPLOYEE." e
            WHERE o.status = ". Model::safeSql($status) ."
            AND o.employee_id = e.id
            AND o.date BETWEEN ". Model::safeSql($start_date) ." AND ". Model::safeSql($end_date) ."
            {$s_query}
            ORDER BY e.lastname, o.date ASC
        ";

        return self::getRecords($sql);
    }

    public static function findAllApprovedByGroupId($group_id) {
        return self::findAllByStatusAndGroupId(G_Overtime::STATUS_APPROVED, $group_id);
    }

    public static function findAllDisapprovedByGroupId($group_id) {
        return self::findAllByStatusAndGroupId(G_Overtime::STATUS_DISAPPROVED, $group_id);
    }

    public static function findAllPendingByGroupId($group_id) {
        return self::findAllByStatusAndGroupId(G_Overtime::STATUS_PENDING, $group_id);
    }

    public static function findAllByStatusAndGroupId($status, $group_id) {
        $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o, ". EMPLOYEE ." e
			WHERE o.status = ". Model::safeSql($status) ."
            AND o.employee_id = e.id
            AND e.department_company_structure_id = ". Model::safeSql($group_id) ."
			ORDER BY o.date DESC
		";
        return self::getRecords($sql);
    }

    public static function findAllPendingByGroupIdAndPeriod($group_id, $start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndGroupIdAndPeriod(G_Overtime::STATUS_PENDING, $group_id, $start_date, $end_date, $additional_query);
    }

    public static function findAllApprovedByGroupIdAndPeriod($group_id, $start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndGroupIdAndPeriod(G_Overtime::STATUS_APPROVED, $group_id, $start_date, $end_date, $additional_query);
    }

    public static function findAllDisapprovedByGroupIdAndPeriod($group_id, $start_date, $end_date, $additional_query = '') {
        return self::findAllByStatusAndGroupIdAndPeriod(G_Overtime::STATUS_DISAPPROVED, $group_id, $start_date, $end_date, $additional_query);
    }

    public static function findAllByStatusAndGroupIdAndPeriod($status, $group_id, $start_date, $end_date, $additional_query = '') {
        $s_query = "";

        if( !empty($additional_query) ){
            $s_query = trim($additional_query);
        }

        $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o, ". EMPLOYEE ." e
			WHERE o.status = ". Model::safeSql($status) ."
            AND o.employee_id = e.id
            AND e.department_company_structure_id = ". Model::safeSql($group_id) ."
            AND o.date BETWEEN ". Model::safeSql($start_date) ." AND ". Model::safeSql($end_date) ."  
            {$s_query}          
			ORDER BY o.date DESC
		";
        return self::getRecords($sql);
    }

    public static function findAllApproved() {
        return self::findAllByStatus(G_Overtime::STATUS_APPROVED);
    }

    public static function findAllDisapproved() {
        return self::findAllByStatus(G_Overtime::STATUS_DISAPPROVED);
    }

    public static function findAllByStatus($status) {
        $sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o
			WHERE o.status = ". Model::safeSql($status) ."
			ORDER BY o.date DESC
		";
        return self::getRecords($sql);
    }
	
	public static function findByEmployeeAndDate($e, $date) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o
			WHERE o.employee_id = ". Model::safeSql($e->getId()) ."
			AND o.date = ". Model::safeSql($date) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    public static function findByEmployeeIdAndDate($employee_id = 0, $date) {
        $sql_date = date("Y-m-d",strtotime($date));
        $sql = "
            SELECT ". self::getFields() ."
            FROM ". G_EMPLOYEE_OVERTIME ." o
            WHERE o.employee_id = ". Model::safeSql($employee_id) ."
            AND o.date = ". Model::safeSql($sql_date) ."
            LIMIT 1
        ";
        return self::getRecord($sql);
    }

	public static function findByEmployeeAndDateAndStatus($e, $date, $status = G_Overtime::STATUS_APPROVED) {
		$sql = "
			SELECT ". self::getFields() ."
			FROM ". G_EMPLOYEE_OVERTIME ." o
			WHERE o.employee_id = ". Model::safeSql($e->getId()) ."
			AND o.date = ". Model::safeSql($date) ."
            AND o.status = ". Model::safeSql($status) ."
			LIMIT 1
		";
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
		$o = new G_Overtime;
		$o->setId($row['id']);
		$o->setDate($row['date']);
		$o->setTimeIn($row['time_in']);
		$o->setTimeOut($row['time_out']);
		$o->setEmployeeId($row['employee_id']);
        $o->setDateIn($row['date_in']);
        $o->setDateOut($row['date_out']);
        if ($row['is_archived'] == G_Overtime::ARCHIVED_YES) {
            $o->setAsArchived();
        } else {
            $o->setAsUnarchived();
        }
        $o->setStatus($row['status']);
        $o->setReason($row['reason']);
        $o->setDateCreated($row['date_created']);
		return $o;
	}
}
?>