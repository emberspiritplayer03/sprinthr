<?php
class G_Employee_Branch_History_Helper {

    /*
     * Ends the branch history of an employee
     */
    public static function ended(IEmployee $e, $date_ended) {
        $b = G_Employee_Branch_History_Finder::findCurrentBranch($e);
        if ($b) {
            $b->setEndDate($date_ended);
            $b->save();
        }
    }


    public static function resetActive(IEmployee $e, $date_ended) {
        $b = G_Employee_Branch_History_Finder::findCurrentBranch2($e);
        if ($b) {
            $b->setEndDate($date_ended);
            $b->save();
        }
    }
		
	public static function isIdExist(G_Employee_Branch_History $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_BRANCH_HISTORY ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>