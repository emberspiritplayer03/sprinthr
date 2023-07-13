<?php
class G_Fp_Attendance_Logs_Manager_V2 {	
	public static function delete(G_Attendance_Log_V2 $fp){
		$affected_rows = 0;
		if(G_Fp_Attendance_Logs_Helper_V2::sqlIsIdExists($fp->getId()) > 0){
			$sql = "
				DELETE FROM ". G_ATTENDANCE_LOG_V2 ."
				WHERE id =" . Model::safeSql($fp->getId());			
			Model::runSql($sql);
			$affected_rows = mysql_affected_rows();
		}	

		return $affected_rows;
	}
}
?>