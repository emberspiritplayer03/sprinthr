<?php
/**
 * @Software			: HR & Payroll Web
 * @Company 			: Gleent Innovative Technologies
 * @Developement Team	: Marvin Dungog, Marlito Dungog, Bryan Bio, Jeniel Mangahis, Bryann Revina
 * @Design Team			: Jayson Alipala
 * @Author				: Bryann Revina
 */
 
class G_Settings_Dependent_Relationship_Manager {
	public static function save(G_Settings_Dependent_Relationship $g) {
		if (G_Settings_Dependent_Relationship_Helper::isIdExist($g) > 0 ) {
			$sql_start = "UPDATE ". DEPENDENT_RELATIONSHIP . "";
			$sql_end   = "WHERE id = ". Model::safeSql($g->getId());		
		}else{
			$sql_start = "INSERT INTO ". DEPENDENT_RELATIONSHIP . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($g->getCompanyStructureId()) .",
			relationship			= ". Model::safeSql($g->getRelationship()) ." "
			. $sql_end ."	
		
		";				
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Dependent_Relationship $g){
		if(G_Settings_Dependent_Relationship_Helper::isIdExist($g) > 0){
			$sql = "
				DELETE FROM ". DEPENDENT_RELATIONSHIP ."
				WHERE id =" . Model::safeSql($g->getId());
			Model::runSql($sql);
		}
	
	}
}
?>