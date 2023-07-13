<?php
Loader::sysLibrary('calendar');

class G_Calendar_Restday extends Calendar {
    protected $employee_obj;
    protected $group_id;
    protected $day_name = array("Su", "Mo", "Tu", "We", "Th", "Fr", "Sa");
    protected $permission;

    public function __construct($year, $month) {
        parent::__construct($year, $month);
    }

    public function setEmployee($e) {
        $this->employee_obj = $e;
    }

    public function setGroupId( $value ) {
        $this->group_id = $value;
    }

    public function setPermission($value) {
        $this->permission = $value;
    }

    public function getEmployee() {
        return $this->employee_obj;
    }

    protected function getDayString($month, $day, $year) {
        $e = $this->employee_obj;
        $employee_id = $e->getId();
        $date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));

        $r = G_Restday_Finder::findByEmployeeAndDate($e, $date);
        if ($r) {
            if($this->permission == G_Sprint_Modules::PERMISSION_02) {
                return "<td class='cal-day cal-has-event'><a href='javascript:addRemoveRestday(". $month .",". $day .",". $year .", ". $r->getId() .", 0)'>" . $day . "</a></td>";
            }else{
                return "<td class='cal-day cal-has-event'>".$day."</td>";
            }
        } else {
            if($this->permission == G_Sprint_Modules::PERMISSION_02) {
                return "<td class='cal-day'><a href='javascript:addRemoveRestday(". $month .",". $day .",". $year .", 0, ". $employee_id .")'>" . $day . "</a></td>";
            }else{ 
                return "<td class='cal-day'>".$day."</a></td>";
            }
        }
    }

    protected function getGroupDayString($month, $day, $year) {        
        $group_id = $this->group_id;
        $date     = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));

        $r = G_Group_Restday_Finder::findByGroupIdAndDate($group_id, $date);
        if ($r) {
            if($this->permission == G_Sprint_Modules::PERMISSION_02) {
                return "<td class='cal-day cal-has-event'><a href='javascript:addRemoveGroupRestday(". $month .",". $day .",". $year .", ". $r->getId() ."," . $group_id . ")'>" . $day . "</a></td>";
            }else{
                return "<td class='cal-day cal-has-event'>".$day."</td>";
            }
        } else {
            if($this->permission == G_Sprint_Modules::PERMISSION_02) {
                return "<td class='cal-day'><a href='javascript:addRemoveGroupRestday(". $month .",". $day .",". $year .", 0, ". $group_id .")'>" . $day . "</a></td>";
            }else{ 
                return "<td class='cal-day'>".$day."</a></td>";
            }
        }
    }

    protected function getTodayString($month, $day, $year, $type = "e") {
        if( $type == 'e' ){
            return $this->getDayString($month, $day, $year);
        }elseif( $type == 'g' ){
            return $this->getGroupDayString($month, $day, $year);
        }
    }
}
?>