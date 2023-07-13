<?php
class G_Tax_Table_Manager {
	public static function save(G_Tax_Table $gtt) {
		if (G_Tax_Table_Helper::isIdExist($gtt) > 0 ) {
			$sql_start = "UPDATE ". G_TAX_TABLE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gtt->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_TAX_TABLE . " ";
			$sql_end  = " ";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id =" . Model::safeSql($gtt->getCompanyStructureId()) . ",
			pay_frequency  	     =" . Model::safeSql($gtt->getPayFrequency()) . ",
			status		         =" . Model::safeSql($gtt->getStatus()) . ",					
			d0   				 =" . Model::safeSql($gtt->getD0()) . ",																
			d1   				 =" . Model::safeSql($gtt->getD1()) . ",			
			d2   				 =" . Model::safeSql($gtt->getD2()) . ",			
			d3   				 =" . Model::safeSql($gtt->getD3()) . ",			
			d4   				 =" . Model::safeSql($gtt->getD4()) . ",			
			d5   				 =" . Model::safeSql($gtt->getD5()) . ",			
			d6   				 =" . Model::safeSql($gtt->getD6()) . ",			
			d7   				 =" . Model::safeSql($gtt->getD7()) . ",			
			d8  				 =" . Model::safeSql($gtt->getD8()) . " "				
			. $sql_end ."	
		
		";			
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Tax_Table $gtt){
		if(G_Tax_Table_Helper::isIdExist($gtt) > 0){
			$sql = "
				DELETE FROM ". G_TAX_TABLE ."
				WHERE id =" . Model::safeSql($gtt->getId());
			Model::runSql($sql);
		}	
	}
}
?>