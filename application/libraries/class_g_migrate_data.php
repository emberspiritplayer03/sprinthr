<?php
class G_Migrate_Data {

	public function __construct() {		
	}

    private function convertToDateFormat($value = '') {
        return date('Y-m-d',strtotime($value));
    }

	/**
	* Import temp data
	*
	* @param file
	* @return array
	*/
	public function importTempData( $file ) {
		$return = array('is_success' => false, 'message' => 'Total imported data 0');

		$inputFileType = PHPExcel_IOFactory::identify($file);
		$objReader     = PHPExcel_IOFactory::createReader($inputFileType); 				
		$this->obj_reader = $objReader->load($file);

		$read_sheet   = $this->obj_reader->getActiveSheet();        
        $import_data  = array(); 
        $employee_ids = array(); 

        $total_valid_records = 0;
        $total_not_imported  = 0;        
        $counter     = 0;    

        foreach ($read_sheet->getRowIterator() as $row) {                       
            $cellIterator = $row->getCellIterator();

            foreach ($cellIterator as $cell) {              
                $current_row    = $cell->getRow();
                $cell_value     = $cell->getFormattedValue();
                $column         = $cell->getColumn();
                $current_column = PHPExcel_Cell::columnIndexFromString($cell->getColumn());               
              
                if ($current_row == 1) {                   
                    $column_header[$column] = strtolower(trim($cell_value));                    
                }else{  
                    $column_header_value = strtolower(trim($column_header[$column])); 
                    if( $column_header_value == 'empid' ){
                        $fields = array('id');
                        $e = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode(trim($cell_value), $fields);
                        $employee_id = $e['id'];
                        //$import_data[$employee_id][$column_header_value] = trim($cell_value);
                    }else{
                        if( $column_header_value != '' ){
                            $import_data[$employee_id][$column_header_value] = trim($cell_value);                                                                      
                        }
                    }                                     
                }
            }
            /*if( $import_data[$counter]['employee_code'] != '' ){
            	//Check if employee code is valid
	            $fields = array('id');
	            $data   = G_Employee_Helper::sqlGetEmployeeDetailsByEmployeeCode($import_data[$counter]['employee_code'],$fields);
	            if( $data['id'] > 0 ){            	
	            	$employee_ids[] = $data['id'];
	            	$import_data[$counter]['eid'] = $data['id'];
	            	$total_valid_records++;
	            }else{
	            	unset($import_data[$counter]);
	            	$total_not_imported++;
	            }
            }*/

            //$counter++;
        }

        foreach( $import_data as $key => $data ){
            foreach( $data as $subKey => $subValue ){
                $date_time   = date("Y-m-d H:i:s");
                $temp_data[] = "(" . Model::safeSql($key) . "," . Model::safeSql($subKey) . "," .  Model::safeSql($subValue) . "," . Model::safeSql($date_time) . ")";                
            }
        }       
       
        /*if( $total_valid_records > 0 ){        	
        	$sql_values = implode(",", $temp_data);
            $sql_fields = 'employee_id,field,amount,created';
            $sql        = "
                INSERT INTO tmp_employee_payslip({$sql_fields})
                VALUES{$sql_values}
            ";     
            echo $sql;
            exit;             
            Model::runSql($sql);
            $total_records_inserted = mysql_affected_rows();

			$return['is_success'] = true;
			$return['message']    = "Total records processed " . $total_valid_records . " / Total records not imported " . $total_not_imported;
        }*/

        $sql_values = implode(",", $temp_data);
        $sql_fields = 'employee_id,field,amount,created';
        $sql        = "
            INSERT INTO tmp_employee_payslip({$sql_fields})
            VALUES{$sql_values}
        ";     
        /*echo $sql;
        exit;  */           
        Model::runSql($sql);
        $total_records_inserted = mysql_affected_rows();

        $return['is_success'] = true;
        $return['message']    = "Total records processed " . $total_records_inserted;
       

        return $return;
	}

    /**
    * Get all migrated data
    * If employee_ids empty will fetch all / if fields not specified will return all
    * 
    * @param array employee_ids
    * @param array fields
    * @return array
    */

    public function getAllMigratedData( $employee_ids = array(), $fields = array() ) {
        
        $sql_fields     = " * ";
        $sql_conditions = "";
        $conditions = array();        

        if( !empty($fields) ){
            $sql_fields = implode(",", $fields);
        }

        if( !empty($employee_ids) ){
            $string_ids   = implode(",", $employee_ids);
            $conditions[] = "employee_id IN ({$string_ids})";
        }

        if( !empty($conditions) ){
            $string_conditions = implode("AND", $conditions);
            $sql_conditions    = "WHERE {$string_conditions}";
        }

        $sql = "
            SELECT {$sql_fields}
            FROM tmp_employee_payslip
            {$sql_conditions}
        ";  
        $result = Model::runSql($sql,true);
        return $result;
    }
}
?>