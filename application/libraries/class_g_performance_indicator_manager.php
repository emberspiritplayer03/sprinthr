<?php
class G_Performance_Indicator_Manager {
	public static function save(G_Performance_Indicator $gsl) {
		if (G_Performance_Indicator_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_PERFORMANCE_INDICATOR . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_PERFORMANCE_INDICATOR . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			performance_id	 = " . Model::safeSql($gsl->getPerformanceId()) . ",
			title		     = " . Model::safeSql($gsl->getTitle()) . ",
			description      = " . Model::safeSql($gsl->getDescription()) . ",
			rate_min         = " . Model::safeSql($gsl->getRateMin()) . ",
			rate_max         = " . Model::safeSql($gsl->getRateMax()) . ",
			rate_default     = " . Model::safeSql($gsl->getRateDefault()) . ",
			order_by	     = " . Model::safeSql($gsl->getOrderBy()) . ",
			is_active         = " . Model::safeSql($gsl->getIsActive()) . "
			"
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Performance_Indicator $gsl){
		if(G_Performance_Indicator_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_PERFORMANCE_INDICATOR ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>