<?php

class G_Role extends Role {

	const YES = "Yes";
	const NO  = "No";

	public function __construct() {}

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

	public function getAllRecordsIsNotArchive($order_by = "", $limit = "", $fields = array(), $user_role) {
		$data = array();
		$data = G_Role_Helper::sqlGetAllIsNotArchiveRecords($order_by, $limit, $fields, $user_role);
		return $data;
	}

	public function getRoleActions(){
		$data = array();

		if( !empty($this->id) ){
			$ra = new G_Role_Actions();
			$ra->setRoleId($this->id);
			$hr_data      		= $ra->getAllHRRoleActionsByRoleId();
			$payroll_data 		= $ra->getAllPayrollRoleActionsByRoleId();
			$dtr_data     		= $ra->getAllDTRRoleActionsByRoleId();
			$employee_data		= $ra->getAllEmployeeRoleActionsByRoleId();
			$audit_trail_data	= $ra->getAllAuditTrailRoleActionsByRoleId();

			$data['hr']      	= $hr_data;
			$data['payroll'] 	= $payroll_data;
			$data['dtr']     	= $dtr_data;
			$data['employee']	= $employee_data;
			$data['audit_trail']= $audit_trail_data;
		}
			
		return $data;
	}

	public function countTotalRecordsIsNotArchive() {
		$total_records = G_Role_Helper::sqlTotalRecordsIsNotArchive();		
		return $total_records;
	}

	//For chaining method
	public function addRole( $data = array() ){

		if( !empty( $data ) ){
			$return = array();
			foreach($data as $key => $value){
				if( property_exists($this, $key) ){
					$this->{$key} = $value;
				}
			}

			$id = self::save();

			if( !empty($id) ){
				$this->id = $id;
			}else{
				$this->id = "";
			}

		}else{
			$this->id = "";
		}

		return $this;
	}

	public function updateModuleActions( $modules = array() ){
		$return = array();
		if( $this->id > 0 ){			
			$ra = new G_Role_Actions();
			$ra->setRoleId($this->id);
			$ra->deleteAllActionsByRoleId(); //Delete all actions
			$is_success = $ra->bulkInsert($modules); //Recreate new actions

			if( $is_success ){
				$return['is_success'] = true;
				$return['message']    = 'Record Saved';
			}else{
				$return['is_success'] = false;
				$return['message']    = '';
			}	

		}else{
			$return['is_success'] = false;
			$return['message']    = '';
		}
		return $return;
	}

	public function addModuleActions( $modules =  array() ) {
		$return = array();		
		if( $this->id > 0 ){
			$ra = new G_Role_Actions();
			$ra->setRoleId($this->id);
			$is_success = $ra->bulkInsert($modules);
			if( $is_success ){
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

	public function deleteRoleAndActions() {
		$return = array();
		if( $this->id > 0 ){
			$ra = new G_Role_Actions();
			$ra->setRoleId($this->id);
			$ra->deleteAllActionsByRoleId(); //Delete all actions
			
			self::delete();

			$return['is_success'] = true;
			$return['message']    = 'Record deleted';

		}else{
			$return['is_success'] = false;
			$return['message']    = 'Record not found';
		}
		return $return;
	}
							
	public function save() {
		return G_Role_Manager::save($this);
	}
		
	public function delete() {
		G_Role_Manager::delete($this);
	}
}
?>