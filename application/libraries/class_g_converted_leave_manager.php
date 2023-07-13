<?php
class G_Converted_Leave_Manager {
	public static function save(G_Convert_Leave $gl) {
		if (G_Converted_Leave_Helper::isIdExist($gl) > 0 ) {
			$sql_start = "UPDATE ". CONVERTED_LEAVES . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gl->getId());		
		}else{
			$sql_start = "INSERT INTO ". CONVERTED_LEAVES . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id      	  =" . Model::safeSql($gl->getEmployeeId()) . ",
			leave_id  			  =" . Model::safeSql($gl->getLeaveId()) . ",
			year 	 	   		  =" . Model::safeSql($gl->getYear()) . ",						
			total_leave_converted =" . Model::safeSql($gl->getTotalLeaveConverted()) . ",						
			amount 	 	   		  =" . Model::safeSql($gl->getAmount()) . ",						
			date_converted 	 	  =" . Model::safeSql($gl->getDateConverted()) . ",						
			created	  	   		  =" . Model::safeSql($gl->getCreated()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}

	public static function bulkInsertData( $a_bulk_insert = array() ) {
        $return = false;
        if( !empty( $a_bulk_insert ) ){
            $sql_values = implode(",", $a_bulk_insert);
            $sql        = "
                INSERT INTO " . CONVERTED_LEAVES . "(employee_id,leave_id,year,total_leave_converted,amount,date_converted,created)
                VALUES{$sql_values}
            ";                           
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }
		
	public static function delete(G_Convert_Leave $gl){
		if(G_Converted_Leave_Helper::isIdExist($gl) > 0){
			$sql = "
				DELETE FROM ". CONVERTED_LEAVES ."
				WHERE id =" . Model::safeSql($gl->getId());
			Model::runSql($sql);
		}	
	}

	public static function deleteAllByYear($year = 0){
		if( $year > 0 ){
			$sql = "
				DELETE FROM ". CONVERTED_LEAVES ."
				WHERE year =" . Model::safeSql($year);
			Model::runSql($sql);
		}		
	}
}
?>