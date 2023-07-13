<?php
class G_Holiday_Finder {
	public static function findById($id) {
		$sql = "
			SELECT h.id, h.public_id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type, h.holiday_year
			FROM ". G_HOLIDAY ." h
			WHERE h.id = ". Model::safeSql($id) ."	
			LIMIT 1		
		";
		return self::getRecord($sql);
	}

    /*
     * DEPRECATED - USE findByMonthDayYear() instead
     */
	public static function findByMonthAndDay($month, $day) {
		$sql = "
			SELECT h.id, h.public_id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type, h.holiday_year
			FROM ". G_HOLIDAY ." h
			WHERE h.holiday_month = ". Model::safeSql($month) ."	
			AND h.holiday_day = ". Model::safeSql($day) ."
			LIMIT 1
		";
		return self::getRecord($sql);
	}

    public static function findByMonthDayYear($month, $day, $year) {
        $sql = "
			SELECT h.id, h.public_id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type, h.holiday_year
			FROM ". G_HOLIDAY ." h
			WHERE h.holiday_month = ". Model::safeSql($month) ."
			AND h.holiday_day = ". Model::safeSql($day) ."
			AND h.holiday_year = ". Model::safeSql($year) ."
			LIMIT 1
		";
		
        return self::getRecord($sql);
    }

    public static function findByYear($year) {
        $sql = "
			SELECT h.id, h.public_id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type, h.holiday_year
			FROM ". G_HOLIDAY ." h
			WHERE h.holiday_year = ". Model::safeSql($year) ."
			ORDER BY h.holiday_month ASC
		";
        return self::getRecords($sql);
    }
	
	public static function findAll() {
		$sql = "
			SELECT h.id, h.public_id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type, h.holiday_year
			FROM ". G_HOLIDAY ." h
			ORDER BY h.holiday_month ASC
		";
		return self::getRecords($sql);
	}

    /*
     * TODO put in separate class
     * TODO put table name in constant file
     */
    public static function findDefaultHolidays() {
        $sql = "
			SELECT h.id, h.holiday_title, h.holiday_month, h.holiday_day, h.holiday_type
			FROM g_settings_holiday h
			ORDER BY h.holiday_month ASC
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
		$h = new G_Holiday;
		$h->setId($row['id']);
		$h->setPublicId($row['public_id']);
		$h->setTitle($row['holiday_title']);
		$h->setMonth($row['holiday_month']);
		$h->setDay($row['holiday_day']);
		$h->setType($row['holiday_type']);
        $h->setYear($row['holiday_year']);
		return $h;
	}
}
?>