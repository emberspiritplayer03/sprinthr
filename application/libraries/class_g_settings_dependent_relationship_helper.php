<?php
/**
 * @Software			: HR & Payroll Web
 * @Company 			: Gleent Innovative Technologies
 * @Developement Team	: Marvin Dungog, Marlito Dungog, Bryan Bio, Jeniel Mangahis, Bryann Revina
 * @Design Team			: Jayson Alipala
 * @Author				: Bryann Revina
 */
 
class G_Settings_Dependent_Relationship_Helper {
	public static function isIdExist(G_Settings_Dependent_Relationship $g) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . DEPENDENT_RELATIONSHIP ."
			WHERE id = ". Model::safeSql($g->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . DEPENDENT_RELATIONSHIP			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>