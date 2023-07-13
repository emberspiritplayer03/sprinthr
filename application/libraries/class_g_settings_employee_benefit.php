<?php
class G_Settings_Employee_Benefit extends Settings_Employee_Benefit {
	
	protected $cutoff = 1;
	protected $multiplied_by = '';
	protected $b_is_valid    = true;
	protected $continue      = false;

	protected $file_to_import;
	protected $obj_reader;
	protected $a_import_data = array();

	const YES = "Yes";
	const NO  = "No";

	const OCCURANCE_FIRST_CUTOFF = 1;
	const OCCURANCE_SECOND_CUTOFF = 2;
	const OCCURANCE_ALL = 3;

	const MULTIPLIED_BY_PRESENT_DAYS = "present_days";

	public function __construct() {
		
	}

	public function setImportFile($file){
		$this->file_to_import = $file;
		$inputFileType    = PHPExcel_IOFactory::identify($this->file_to_import);
		$objReader        = PHPExcel_IOFactory::createReader($inputFileType);
		$this->obj_reader = $objReader->load($this->file_to_import);
		return $this;
	}

	public function isMultipliedByValidSelection() {
		$valid_options = $this->getMultipliedByOptions();
		if( !empty($this->multiplied_by) ){
			$selected = trim($this->multiplied_by);
			if( !array_key_exists($selected, $valid_options) ){
				$this->b_is_valid = false;
			}
		}

		return $this;
	}

	public function getMultipliedByOptions() {
		$options = array(self::MULTIPLIED_BY_PRESENT_DAYS => 'Present Days');
		return $options;
	}

	public function setCutOff( $value ){
		$this->cutoff = $value;
	}

	public function getCutOff() {
		return $this->cutoff;
	}

	public function setMultipliedBy( $value = '' ){
		$this->multiplied_by = $value;
	}

	public function getMultipliedBy() {
		return $this->multiplied_by;
	}

	/*Encrypting array with id as index name*/
	public function encryptId($data = array()){		
		$new_data = array();
		foreach( $data as $key => $value ){
			foreach($value as $sub_key => $sub_value){
				if( $sub_key == "id" ){
					$new_data[$key][$sub_key] = Utilities::encrypt($sub_value);
				}else{
					$new_data[$key][$sub_key] = $sub_value;
				}
			}
		}		
		return $new_data;
	}

	public function getBenefitOccuranceOptions() {
		$a_options = array(self::OCCURANCE_FIRST_CUTOFF => "First Cutoff", self::OCCURANCE_SECOND_CUTOFF => "Second Cutoff", self::OCCURANCE_ALL => "Every cutoff");
		return $a_options;
	}

	public function getAllRecordsIsNotArchive($order_by = "", $limit = "", $fields = array()) {
		$data = array();
		$data = G_Settings_Employee_Benefit_Helper::sqlGetAllIsNotArchiveRecords($order_by, $limit, $fields);
		return $data;
	}

	public function countTotalRecordsIsNotArchive() {
		$total_records = G_Settings_Employee_Benefit_Helper::sqlTotalRecordsIsNotArchive();		
		return $total_records;
	}

	public function getEmployeeUnregisteredBenefits($employee_id = 0) {
		$data = array();

		if( $employee_id > 0 ){
			$data = G_Settings_Employee_Benefit_Helper::sqlEmployeeUnregisteredBenefits($employee_id);
		}

		return $data;
	}

	public function updateBenefit(){
		$return = array();

		if( !empty($this->id) && !empty($this->code) && !empty($this->name) && $this->amount >= 0 ){			
			$is_success       = $this->save();
			if( $is_success > 0 ){
				$return['is_success'] = true;
				$return['message']    = 'Record Saved';
			}else{
				$return['is_success'] = false;
				$return['message']    = 'Cannot save record';
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Cannot save record';
		}

		return $return;
	}

	/*
		Note : Will only delete benefits with no enrollees
		Usage :
		    $id = 1;
		    $b  = new G_Settings_Employee_Benefit();
		    $b->setId($id);
		    $return = $b->deleteBenefit() //Will return array
	*/

	public function deleteBenefit() {
		$return = array();

		if( !empty($this->id) ){
			$is_with_enrollees = G_Employee_Benefits_Main_Helper::sqlCountTotalEmployeesEnrolledToBenefit($this->id);
			if( $is_with_enrollees > 0 ){				
				$return['is_success'] = false;
				$return['message']    = "<b>Cannot delete record</b><p>There <b>{$is_with_enrollees}</b> enrollees to selected benefit. Untag / Remove all enrollees from benefit before deleting</p>";
			}else{
				self::delete();
				$return['is_success'] = true;
				$return['message']    = 'Record deleted';
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Record not found';
		}

		return $return;
	}

	public function saveBenefit() {
		$return = array();

		if( !empty($this->code) && !empty($this->name) && $this->amount >= 0 && $this->b_is_valid ){
			$this->is_archive = self::NO;			
			$is_success       = $this->save();
			if( $is_success > 0 ){
				$return['is_success'] = true;
				$return['message']    = 'Record Saved';
			}else{
				$return['is_success'] = false;
				$return['message']    = 'Cannot save record';
			}
		}else{
			$return['is_success'] = false;
			$return['message']    = 'Cannot save record';
		}

		return $return;
	}

	public function createImportBulkData(){						
		$read_sheet  = $this->obj_reader->getActiveSheet();
		$a_benefits  = array();
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
                    $cell_value          = trim($cell_value);
                    if( $cell_value != "" && $cell_value > 0 ){
                    	if( !array_key_exists($cell_value, $a_benefits[$column_header_value] ) ){
	                    	switch ($column_header_value) {
		                        case 'position allowance':
		                       		$i_amount        = $cell_value / 2;
		                       		$i_occurance     = self::OCCURANCE_ALL;
		                       		$s_is_taxable    = self::NO; 
		                       		$s_is_archive    = self::NO;
		                       		$s_date_created  = $this->date_created;
		                       		$s_mulitplied_by = '';		                        	
		                        	$s_code          = "POSITION_ALLOWANCE_{$cell_value}";
		                        	$s_name          = "POSITION ALLOWANCE :{$cell_value}";		
		                        	$a_benefits[$column_header_value][$cell_value][] = "({$i_amount},{$i_occurance},'{$s_is_taxable}','{$s_is_archive}','{$s_date_created}','{$s_mulitplied_by}','{$s_code}','{$s_name}')";                         	
		                            break;
		                        case 'ctpa/sea':
		                        	$i_amount        = $cell_value;
		                       		$i_occurance     = self::OCCURANCE_ALL;
		                       		$s_is_taxable    = self::NO; 
		                       		$s_is_archive    = self::NO;
		                       		$s_date_created  = $this->date_created;
		                       		$s_mulitplied_by = self::MULTIPLIED_BY_PRESENT_DAYS;		                        	
		                        	$s_code          = "CTPA/SEA_{$cell_value}";
		                        	$s_name          = "CTPA/SEA :{$cell_value}";
		                        	$a_benefits[$column_header_value][$cell_value][] = "({$i_amount},{$i_occurance},'{$s_is_taxable}','{$s_is_archive}','{$s_date_created}','{$s_mulitplied_by}','{$s_code}','{$s_name}')"; 		   	                           	
		                            break;
		                        case 'other allowance':	    
		                        	$i_amount        = $cell_value;
		                       		$i_occurance     = self::OCCURANCE_ALL;
		                       		$s_is_taxable    = self::NO; 
		                       		$s_is_archive    = self::NO;
		                       		$s_date_created  = $this->date_created;
		                       		$s_mulitplied_by = self::MULTIPLIED_BY_PRESENT_DAYS;		                        	
		                        	$s_code          = "OTHER ALLOWANCE_{$cell_value}";
		                        	$s_name          = "OTHER ALLOWANCE :{$cell_value}";   
		                        	$a_benefits[$column_header_value][$cell_value][] = "({$i_amount},{$i_occurance},'{$s_is_taxable}','{$s_is_archive}','{$s_date_created}','{$s_mulitplied_by}','{$s_code}','{$s_name}')";                     	
		                            break;                                            
		                        default:                           
		                            break;
		                    }		                    
	                	}
                    }
                }
			}
		}

		if( count($a_benefits) > 0 ){
			$this->a_import_data = $a_benefits;
			$this->continue 	 = true;
		}		
		return $this;
	}

	public function importBulkSaveCustom() {
		if( $this->continue && !empty($this->a_import_data) ){
			$a_sql_data = array();
			$s_sql_data = "";
			foreach( $this->a_import_data as $import_data ){
				foreach( $import_data as $data => $value){
					foreach( $value as $v ){  
						$a_sql_data[] = $v;
					}
				}
			}

			if( count($a_sql_data) > 0 && !empty($a_sql_data) ){				
				$is_success = G_Settings_Employee_Benefit_Manager::bulkInsertData($a_sql_data);
				if( $is_success ){
					$return['is_success'] = true;
					$return['message']    = 'Record(s) was successfully saved';
				}

			}
		}
	}

	public function importBulkSave($bulk_data = array(), $fields = array()) {
		$return['is_success'] = false;
		$return['message']    = 'Cannot import data';
				
		if( !empty($bulk_data) ){
			$this->a_import_data = $bulk_data;
		}

		if( !empty($this->a_import_data) ){
			$return = G_Settings_Employee_Benefit_Manager::bulkInsertData($this->a_import_data, $fields);
		}

		return $return;
	}

	public function bulkSave() {

	}
							
	public function save() {
		return G_Settings_Employee_Benefit_Manager::save($this);
	}
		
	public function delete() {
		G_Settings_Employee_Benefit_Manager::delete($this);
	}
}
?>