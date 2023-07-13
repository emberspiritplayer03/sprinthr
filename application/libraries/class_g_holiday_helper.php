<?php
class G_Holiday_Helper {
    /*
     * Copies the default holiday settings to a year supplied
     */
    public static function copyDefaultHolidaySettings($year) {
        $is_already_added = G_Holiday_Finder::findByYear($year);

        if (!$is_already_added) {
            $default_holidays = G_Holiday_Finder::findDefaultHolidays();
            foreach ($default_holidays as $holiday) {
                $h = new G_Holiday;
                $h->setTitle($holiday->getTitle());
                $h->setDay($holiday->getDay());
                $h->setMonth($holiday->getMonth());
                $h->setYear($year);
                $h->setType($holiday->getType());
                $h->save();
            }
        }
    }

    /*
     * Adds holiday to company calendar
     *
     * @param string $title
     * @param int $year (ex: 2014)
     * @param int $month (1 to 12)
     * @param int $day (1 to 31)
     * @param const $holiday_type G_Holiday::LEGAL or G_Holiday::SPECIAL
     * @return bool true if successfully added
     */
    public static function addHoliday($title, $year, $month, $day, $holiday_type) {
        $hol = new G_Holiday();
        $hol->setTitle($title);
        $hol->setMonth($month);
        $hol->setDay($day);
        $hol->setType($holiday_type);
        $hol->setYear($year);
        return $hol->save(); 
    }

    public static function getHolidayByMonthDayYear( $month, $day, $year) {
        $sql = "
            SELECT h.*
            FROM ". G_HOLIDAY ." h
            WHERE h.holiday_month = ". Model::safeSql($month) ."
            AND h.holiday_day = ". Model::safeSql($day) ."
            AND h.holiday_year = ". Model::safeSql($year) ."
        ";
        
        $result = Model::runSql($sql,true);
        return $result;
    }

    public static function isDateHoliday( $month, $day, $year) {
        $sql = "
            SELECT COUNT(id) AS total
            FROM ". G_HOLIDAY ." h
            WHERE h.holiday_month = ". Model::safeSql($month) ."
            AND h.holiday_day = ". Model::safeSql($day) ."
            AND h.holiday_year = ". Model::safeSql($year) ."
        ";
        
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        if( $row['total'] > 0){
            $is_holiday = true;
        }else{
            $is_holiday = false;
        }

        return $is_holiday;
    }

    
}
?>