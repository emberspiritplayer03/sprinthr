<?php
class G_Request_Approver_Requestor extends Request_Approver_Requestor {
	const PREFIX_EMPLOYEE   = "e"; //highest priority
	const PREFIX_DEPARTMENT = "d"; 
	const PREFIX_GROUP      = "g";

	public function __construct() {
		
	}

	public function bulkInsert( $values = array(), $fields = array() ) {
		return G_Request_Approver_Requestor_Manager::bulkInsert($values, $fields);
	}

	public function deleteAllByRequestApproversId() {
		$is_success = false;
		
		if( $this->request_approvers_id > 0 ){
			$is_success = G_Request_Approver_Requestor_Manager::deleteAllByRequestApproversId($this->request_approvers_id);
		}

		return $is_success;
	}
							
	public function save() {
		return G_Request_Approver_Requestor_Manager::save($this);
	}
}
?>