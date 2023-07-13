<?php
class G_Incentive_Leave_History_Manager {
	public static function save(G_Incentive_Leave_History $ilh) {
		if (G_Incentive_Leave_History_Helper::isIdExist($ilh) > 0 ) {
			$sql_start = "UPDATE ". INCENTIVE_LEAVE_HISTORY . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($ilh->getId());		
		}else{
			$sql_start = "INSERT INTO ". INCENTIVE_LEAVE_HISTORY . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			month_number =" . Model::safeSql($ilh->getMonthNumber()) . ",
			year   	     =" . Model::safeSql($ilh->getYear()) . ",
			total_given  =" . Model::safeSql($ilh->getTotalGiven()) . ",						
			date_process =" . Model::safeSql($ilh->getDateProcess()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Incentive_Leave_History $ilh){
		if(G_Incentive_Leave_History_Helper::isIdExist($ilh) > 0){
			$sql = "
				DELETE FROM ". INCENTIVE_LEAVE_HISTORY ."
				WHERE id =" . Model::safeSql($ilh->getId());
			Model::runSql($sql);
		}	
	}
}
?>