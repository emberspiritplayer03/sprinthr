<?php
class G_Overtime_Error_Helper {
    public static function updateOvertimeError($a, $e) {
        if ($a && $e) {
            $err = new G_Overtime_Error_Checker();
            $err->checkByAttendanceAndEmployee($a, $e);
            if ($err->hasError()) {
                $err->saveErrors();
            } else {
                $err->fixErrors();
            }
        }
    }

    public static function countAllErrorsNotFixed() {
        $sql = "
			SELECT COUNT(id) AS total FROM " . G_OVERTIME_ERROR . "
			WHERE is_fixed = " . Model::safeSql(G_Overtime_Error::ERROR_FIXED_NO) . "
		";
        $result = Model::runSql($sql);
        $row = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function getFixActionString($error) {
        $error_type_id = $error->getErrorTypeId();
        $employee_id = $error->getEmployeeId();
        $date = $error->getDate();

        switch ($error_type_id):
            case G_Overtime_Error::OT_OUT_GREATER_THAN_ACTUAL_OUT:
                $string = '<a class="link_option" href="javascript:void(0)" onclick="editOvertime('. $employee_id .', \''. $date .'\')" title="Edit"><i class="icon-edit"><span class="tooltip" title="Change Overtime"></span></i> Change Overtime</a>';
            break;
            case G_Overtime_Error::ERROR_NO_ACTUAL_TIME:
                $string = '<a class="link_option" href="javascript:void(0)" onclick="editActualTime('. $employee_id .', \''. $date .'\')" title="Edit"><i class="icon-edit"><span class="tooltip" title="Change Actual Time"></span></i> Change Actual Time</a>';
            break;
            //case G_Overtime_Error::ACTUAL_OUT_LESS_THAN_ACTUAL_IN:
            //    $string = '<a class="link_option" href="javascript:void(0)" onclick="editActualTime('. $employee_id .', \''. $date .'\')" title="Edit"><i class="icon-edit"><span class="tooltip" title="Change Actual Time"></span></i> Change Actual Time</a>';
            //break;
        endswitch;

        return $string;
    }
}
?>