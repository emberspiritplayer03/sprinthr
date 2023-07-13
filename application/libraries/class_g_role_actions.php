<?php

class G_Role_Actions extends Role_Actions {
	
	public function __construct() {
		
	}

	public function getAllHRRoleActionsByRoleId() {
		$data = array();

		if( !empty($this->role_id) ){
			$fields   = array("parent_module","module","action");
			$data     = G_Role_Actions_Helper::getAllHRRoleActionsByRoleId($this->role_id, $fields);
			$new_data = array();

			foreach($data as $key => $value){
				foreach($value as $sub_key => $sub_value){
					$new_data[$value['module']] = $sub_value;
				}
			}
		}

		return $new_data;
	}

	public function getAllPayrollRoleActionsByRoleId() {
		$data = array();
		
		if( !empty($this->role_id) ){
			$fields   = array("parent_module","module","action");
			$data     = G_Role_Actions_Helper::getAllPayrollRoleActionsByRoleId($this->role_id, $fields);
			$new_data = array();

			foreach($data as $key => $value){
				foreach($value as $sub_key => $sub_value){
					$new_data[$value['module']] = $sub_value;
				}
			}
		}

		return $new_data;
	}

	public function getAllDTRRoleActionsByRoleId() {
		$data = array();
		
		if( !empty($this->role_id) ){
			$fields   = array("parent_module","module","action");
			$data     = G_Role_Actions_Helper::getAllDTRRoleActionsByRoleId($this->role_id, $fields);
			$new_data = array();

			foreach($data as $key => $value){
				foreach($value as $sub_key => $sub_value){
					$new_data[$value['module']] = $sub_value;
				}
			}
		}

		return $new_data;
	}

	public function getAllEmployeeRoleActionsByRoleId() {
		$data = array();
		
		if( !empty($this->role_id) ){
			$fields   = array("parent_module","module","action");
			$data     = G_Role_Actions_Helper::getAllEmployeeRoleActionsByRoleId($this->role_id, $fields);
			$new_data = array();

			foreach($data as $key => $value){
				foreach($value as $sub_key => $sub_value){
					$new_data[$value['module']] = $sub_value;
				}
			}
		}

		return $new_data;
	}

	public function getAllAuditTrailRoleActionsByRoleId() {
		$data = array();
		
		if( !empty($this->role_id) ){
			$fields   = array("parent_module","module","action");
			$data     = G_Role_Actions_Helper::getAllAuditTrailRoleActionsByRoleId($this->role_id, $fields);
			$new_data = array();

			foreach($data as $key => $value){
				foreach($value as $sub_key => $sub_value){
					$new_data[$value['module']] = $sub_value;
				}
			}
		}

		return $new_data;
	}

	public function deleteAllActionsByRoleId() {
		$return = false;
		if( $this->role_id > 0 ){			
			$return = G_Role_Actions_Manager::deleteAllActionsByRoleId($this->role_id);
		}
		return $return;
	}

	public function bulkInsert( $modules = array() ) {
		$return = false;
		if( $this->role_id > 0 ){			
			$return = G_Role_Actions_Manager::bulkInsert($this->role_id, $modules);
		}
		return $return;
	}
							
	public function save() {
		return G_Role_Actions_Manager::save($this);
	}
		
	public function delete() {
		G_Role_Actions_Manager::delete($this);
	}
}
?>