<?php
class G_SSS_Manager {	
	public static function saveToEmployee(IEmployee $e, G_SSS $s) {		
		if ($s->getId() > 0) {
			$action = 'update';
			$sql_start = "UPDATE g_employee_contribution";
			$sql_end   = " WHERE employee_id = ". Model::safeSql($e->getId());		
		} else {
			$action = 'insert';
			$sql_start = "INSERT INTO g_employee_contribution";
			$sql_end   = ",employee_id = ". Model::safeSql($e->getId());
		}
		
		$sql = $sql_start ."
			SET
			sss_ee   = " . Model::safeSql($s->getEmployeeShare()) .",
			sss_er	 = " . Model::safeSql($s->getCompanyShare()) ."
			". $sql_end ."		
		";	

		Model::runSql($sql);
		if (mysql_errno() > 0) {
			return false;
		}
		if ($action == 'insert') {
			return mysql_insert_id();
		} else if ($action == 'update') {
			return true;
		}	
	}

	public static function update(G_SSS $e) {
		if ( $e ) {	
			$sql = "
				UPDATE ". SSS . " 
				SET
					company_share			= " . Model::safeSql($e->getCompanyShare()) .",
					employee_share			= " . Model::safeSql($e->getEmployeeShare()) .",
					company_ec				= " . Model::safeSql($e->getCompanyEc()) .",
					monthly_salary_credit	= " . Model::safeSql($e->getMonthlySalaryCredit()) .",
					from_salary				= " . Model::safeSql($e->getFromSalary()) .",
					to_salary				= " . Model::safeSql($e->getToSalary()) ."
				 WHERE id = ". Model::safeSql($e->getId());		
			Model::runSql($sql);
			$return = true;
		}else{
			$return = false;
		}

		return $return;		
	}

	public static function importSSSTable( $file = '' ) {		
		$total_failed_import  = 0;
		$total_success_import = 0;
		$sql 		          = "INSERT INTO " . SSS . "(monthly_salary_credit, from_salary, to_salary, employee_share,company_share,company_ec, provident_ee, provident_er, is_active)VALUES";

		try {
			$inputFileType = PHPExcel_IOFactory::identify($file);
			$objReader     = PHPExcel_IOFactory::createReader($inputFileType); 			
			$objPHPExcel   = $objReader->load($file);
		} catch (Exception $e) {			
			die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
		
		$rowIterator        = $objPHPExcel->getActiveSheet()->getRowIterator();	
		$failed_logs        = array();	
		$valid_col_index    = array("A","B","C","D","E","F","G","H");
		$required_col_index = array("A","B","C","D","E","F");
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
						$cols[]       = "'" . $column_value . "'";	//Store column value to array
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
					$values[] = "(" . implode(",",$cols) . ", 'Yes')";					
				}else{
					$total_failed_import++;
				}

				$is_blank    = 0;				
				$col_counter = 0;
			}
			$row_counter++;
		}
		
		$mysqli = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE); 		
		//Truncate SSS table
		$table_name   = SSS;
		$truncate_sql = "TRUNCATE `{$table_name}`";
		$truncate_result = $mysqli->query($truncate_sql);	
		
		//Insert new values		
		$sql   .= implode(",",$values) . ";";										
		$result = $mysqli->query($sql);		
		$total_succesful_import         = $mysqli->affected_rows;	
		$total_failed_import			= ($total_success_import - $mysqli->affected_rows) + $total_failed_import;

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