<?php
class G_Performance_Manager {
	public static function save(G_Performance $gsl) {
		if (G_Performance_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_PERFORMANCE . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_PERFORMANCE . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id	= " . Model::safeSql($gsl->getCompanyStructureId()) . ",
			title					= " . Model::safeSql($gsl->getTitle()) . ",
			job_id		         	= " . Model::safeSql($gsl->getJobId()) . ",
			description        		= " . Model::safeSql($gsl->getDescription()) . ",
			date_created       		= " . Model::safeSql($gsl->getDateCreated()) . ",
			created_by        		= " . Model::safeSql($gsl->getCreatedBy()) . ",
			is_archive        		= " . Model::safeSql($gsl->getIsArchive()) . "
			"
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
	
	public static function archive(G_Performance $gsl){
		if(G_Performance_Helper::isIdExist($gsl) > 0){
			$sql = "
				UPDATE ". G_PERFORMANCE ."
					SET is_archive =" . Model::safeSql(G_Performance::YES) . "
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore(G_Performance $gsl){
		if(G_Performance_Helper::isIdExist($gsl) > 0){
			$sql = "
				UPDATE ". G_PERFORMANCE ."
					SET is_archive =" . Model::safeSql(G_Performance::NO) . "
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
		
	public static function delete(G_Performance $gsl){
		if(G_Performance_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_PERFORMANCE ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>