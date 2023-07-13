<?php
class G_Employee_Membership_Manager {
	public static function save(G_Employee_Membership $e) {
		if (G_Employee_Membership_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_MEMBERSHIP . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_MEMBERSHIP . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id				= " . Model::safeSql($e->getEmployeeId()) .",
			membership_type_id		= " . Model::safeSql($e->getMembershipTypeId()) .",
			membership_id 			= " . Model::safeSql($e->getMembershipId()) .",
			subscription_ownership	= " . Model::safeSql($e->getSubscriptionOwnership()) .",
			subscription_amount		= " . Model::safeSql($e->getSubscriptionAmount()) .",
			commence_date			= " . Model::safeSql($e->getCommenceDate()) .",
			renewal_date			= " . Model::safeSql($e->getRenewalDate()) ." 
			
			"
		
			. $sql_end ."	
		
		";	

		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Employee_Membership $e){
		if(G_Employee_Membership_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_MEMBERSHIP ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>