<?php
class G_Fp_Attendance_Logs_Manager {	
	public static function delete(G_Fp_Attendance_Logs $fp){
		$affected_rows = 0;
		if(G_Fp_Attendance_Logs_Helper::sqlIsIdExists($fp->getId()) > 0){
			$sql = "
				DELETE FROM ". G_ATTENDANCE_LOG ."
				WHERE id =" . Model::safeSql($fp->getId());			
			Model::runSql($sql);
			$affected_rows = mysql_affected_rows();
		}	

		return $affected_rows;
	}
}
?>