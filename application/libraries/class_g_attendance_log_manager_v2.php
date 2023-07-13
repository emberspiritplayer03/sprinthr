<?php
class G_Attendance_Log_Manager_V2 {

    /*
     *  $multiple_attendance - array of G_ATTENDANCE_LOG_V2
     */
    public static function saveMultiple($multiple_attendance) {
        $has_record = false;        
        foreach ($multiple_attendance as $a) {
            $insert_sql_values[] = "(". Model::safeSql($a->getId()) .",". Model::safeSql($a->getEmployeeId()) .",'". $a->getEmployeeCode() ."',". Model::safeSql($a->getDate()) .",". Model::safeSql($a->getTime()) .",". Model::safeSql($a->getType()) .",". Model::safeSql($a->getRemarks()) .",". Model::safeSql($a->getEmployeeName()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_ATTENDANCE_LOG_V2 ." (id, user_id, employee_code, date, time, type, remarks, employee_name)
                VALUES ". $insert_sql_value ."
            ";           
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            return true;
        }
    }

	/*
		$a - Instance of G_ATTENDANCE_LOG_V2
	*/
	public static function save($a) {
		if ($a->getId() > 0) {
			$action = 'update';
			$sql_start_v2 = "UPDATE ". G_ATTENDANCE_LOG_V2;
			$sql_end   = " WHERE id = ". Model::safeSql($a->getId());		
		} else {
			$action = 'insert';
			$sql_start_v2 = "INSERT INTO ". G_ATTENDANCE_LOG_V2;
			//$sql_end   = ",public_id = ". Model::safeSql($public_id);
		}
		
		$time = date("H:i:s",strtotime($a->getTime()));
		//$type = strtoupper($a->getType());
		$type = $a->getType();

		$sql_v2 = $sql_start_v2 ."
			SET
			employee_id = '". $a->getEmployeeId() ."',
			date = ". Model::safeSql($a->getDate()) .",
			type = ". Model::safeSql($type) .",
			time = ". Model::safeSql($time) .",
			device_number = ".Model::safeSql($a->getRemarks()).",
			project_site_id = ".Model::safeSql($a->getProjectSiteId()).",
			activity_name = ".Model::safeSql($a->getActivityName())."	
			". $sql_end ."	
		";

		Model::runSql($sql_v2);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}
	}
	
	/*
		Variables
		$sf - Instance of G_Schedule_Specific class
	*/		
	public static function delete($sf) {
		$sql = "
			DELETE FROM ". G_EMPLOYEE_SCHEDULE ."
			WHERE id = ". Model::safeSql($sf->getId()) ."
		";
		Model::runSql($sql);
		return (mysql_affected_rows() >= 1) ? true : false ;
	}	

	public static function updateUntransfferedDataByDate($date) {
		$sql = "
			UPDATE ". G_ATTENDANCE_LOG_V2 ."
			SET is_transferred =" . Model::safeSql(G_ATTENDANCE_LOG_V2::IS_TRANSFERRED) . "
			WHERE date =" . Model::safeSql($date) . "
				AND is_transferred =" . Model::safeSql(G_ATTENDANCE_LOG_V2::ISNOT_TRANSFERRED) . "
		";		
		Model::runSql($sql);
		//return (mysql_affected_rows() >= 1) ? true : false ;
	}	

	public static function resetLogsToNotTransferredByDateRange($date_from = '', $date_to = '') {
		$total_records_updated = 0;
		$sql = "
			UPDATE ". G_ATTENDANCE_LOG_V2 ."
			SET is_transferred =" . Model::safeSql(G_ATTENDANCE_LOG_V2::ISNOT_TRANSFERRED) . "
			WHERE date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
		";				
		Model::runSql($sql);
		$total_records_updated = mysql_affected_rows();				
		return $total_records_updated;
	}	

	public static function setBreakLogsToTransferredByDateRange($date_from = '', $date_to = '') {
		$total_records_updated = 0;
		$sql = "
			UPDATE ". G_ATTENDANCE_LOG_V2 ."
			SET is_transferred =" . Model::safeSql(G_ATTENDANCE_LOG_V2::IS_TRANSFERRED) . "
			WHERE date BETWEEN " . Model::safeSql($date_from) . " AND " . Model::safeSql($date_to) . "
			AND type NOT IN (
			'in', 'out'
			)
		";				
		Model::runSql($sql);
		$total_records_updated = mysql_affected_rows();				
		return $total_records_updated;
	}	

	public static function resetLogsToNotTransferredById($id) {
		$total_records_updated = 0;
		$sql = "
			UPDATE ". G_ATTENDANCE_LOG_V2 ."
			SET is_transferred = 0
			WHERE id =" . Model::safeSql($id);			
		Model::runSql($sql);
	}	

	public static function updateAttendanceLogsById( $id = 0, $data = array(), $fields = array() ) {
		if( $id > 0 && !empty($data) && !empty($fields) ){			
			foreach( $fields as $field ){
				$a_updates[] = "{$field} =" . Model::safeSql($data[$field]);
			}

			$sql_updates = implode(",", $a_updates);
			$sql = "
				UPDATE ". G_ATTENDANCE_LOG_V2 ."
				SET {$sql_updates}
				WHERE id =" . Model::safeSql($id) . "
			";						
			Model::runSql($sql);
			//return (mysql_affected_rows() >= 1) ? true : false ;
			return true;
			
		}		
	}		

	public static function importLogs( $file = '' ) {		
		$total_failed_import  = 0;
		$total_success_import = 0;
		$sql 		          = "INSERT IGNORE INTO " . G_ATTENDANCE_LOG_V2 . "(employee_code, type, date, time)VALUES";

		try {
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader     = PHPExcel_IOFactory::createReader($inputFileType); 			
			$objPHPExcel   = $objReader->load($file);
		} catch (Exception $e) {			
			die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
		
		$rowIterator        = $objPHPExcel->getActiveSheet()->getRowIterator();	
		$failed_logs        = array();	
		$valid_col_index    = array("A","B","C");
		$required_col_index = array("A","B","C");
		$row_counter        = 0;		

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(true);	
			if($row_counter > 0){				
				$cols 		= array();
				$cols_index = array();
				foreach ($cellIterator as $cell) {										
					
					$column_name  = $cell->getColumn();
					$column_value = trim(addslashes($cell->getFormattedValue()));
					$col_counter++;

					if(in_array($column_name, $valid_col_index)){	
						if($column_name == "B"){															
							$column_value = strtolower($column_value);
						}

						if($column_name == "C"){

  							$date = date("Y-m-d",strtotime($column_value));
  							$time = date("H:i:s",strtotime($column_value));

  							$cols[] = Model::safeSql($date); 
  							$cols[] = Model::safeSql($time);
  						}
						
						if( $column_name <> "C" ){
							$cols[]       = "'" . $column_value . "'";	//Store column value to array
						}

						$cols_index[] = $column_name; //Store column index - will be use for checking for null values
					}
				}

				$is_in_array = true;
				foreach($required_col_index as $rc){
					if(!in_array($rc, $cols_index)){
						$is_in_array = false;
						$failed_logs[$row_counter]['error_description'] = "Cannot accept NULL value on required field";
						$failed_logs[$row_counter]['error_row_number']  = $row_counter + 1;	
					}
				}

				if( $is_in_array ){	
					//If required fields is not empty store data to sql array
					$total_success_import++;
					$values[] = "(" . implode(",",$cols) . ")";					
				}else{
					$total_failed_import++;
				}

				$is_blank    = 0;				
				$col_counter = 0;
			}
			$row_counter++;
		}
					
		$sql   .= implode(",",$values) . ";";			
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 							
		$result = $mysqli->query($sql);		
		$total_succesful_import         = $mysqli->affected_rows;	
		$total_failed_import			= ($total_success_import - $mysqli->affected_rows) + $total_failed_import;

		//Update inserted data
		$subSql = "
				UPDATE " . G_ATTENDANCE_LOG_V2 . " fp
					LEFT JOIN " . EMPLOYEE . " e ON fp.employee_code = e.employee_code
					SET fp.user_id = e.id,
    					fp.employee_name = CONCAT(e.lastname, ' ', e.firstname, ' ', e.middlename)
				WHERE fp.is_transferred = 0 AND fp.user_id = 0
		";
		$subResult = $mysqli->query($subSql);	

		//Convert array failed logs to string output
		$str_failed_logs = '';
		if( !empty($failed_logs) ){
			$str_failed_logs .= "<div class=\"alert alert-error\"><p><b>Error Logs</b></p>";
			$str_failed_logs .= "<ol>";	
			foreach($failed_logs as $f){
				$row_number      = $f['error_row_number'];
				$err_description = $f['error_description'];

				$str_failed_logs .= "<li>";
					$str_failed_logs .= "Excel row number <b>{$row_number}</b> : {$err_description}";
				$str_failed_logs .= "</li>";
			}
			$str_failed_logs .= "</ol>";
			$str_failed_logs .= "<p>Total successful import : <b>{$total_succesful_import}</b> / Total failed import : <b>{$total_failed_import}</b></p>";			
			$str_failed_logs .= "</div>";
		}else{
			$str_failed_logs .= "<div class=\"alert alert-success\"><p><b></b></p>";
			$str_failed_logs .= "<p>Total successful import : <b>{$total_succesful_import}</b> / Total failed import : <b>{$total_failed_import}</b></p>";			
			$str_failed_logs .= "</div>";
		}

		$return['error_logs']			= $str_failed_logs;
		$return['total_success_import'] = $total_succesful_import;
		$return['total_failed_import']  = $total_failed_import;
		return $return;
	}
}
?>