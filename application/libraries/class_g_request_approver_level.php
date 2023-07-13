<?php
class G_Request_Approver_Level extends Request_Approver_Level {
	
	public function __construct() {
		
	}

	public function bulkInsert( $values = array(), $fields = array() ) {
		return G_Request_Approver_Level_Manager::bulkInsert($values, $fields);
	}

	public function deleteAllByRequestApproversId() {
		$is_success = false;
		
		if( $this->request_approvers_id > 0 ) {
			$is_success = G_Request_Approver_Level_Manager::deleteAllByRequestApproversId($this->request_approvers_id);
		}

		return $is_success;
	}
							
	public function save() {
		return G_Request_Approver_Level_Manager::save($this);
	}
}
?>