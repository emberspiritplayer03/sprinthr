<?php
class G_Settings_Memo_Manager {
	public static function save(G_Settings_Memo $sm) {
		if (G_Settings_Memo_Helper::isIdExist($sm) > 0 ) {
			$sql_start = "UPDATE ". G_SETTINGS_MEMO . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($sm->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_SETTINGS_MEMO . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			title 			=" . Model::safeSql($sm->getTitle()) . ",
			content 			=" . Model::safeSql($sm->getContent()) . ",
			created_by 		=" . Model::safeSql($sm->getCreatedBy()) . ",
			is_archive     =" . Model::safeSql($sm->getIsArchive()) . ",
			date_created	=" . Model::safeSql($sm->getDateCreated()) . " "				
			. $sql_end ."	
		
		";		
		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Settings_Memo $sm){
		if(G_Settings_Memo_Helper::isIdExist($sm) > 0){
			$sql = "
				DELETE FROM ". G_SETTINGS_MEMO ."
				WHERE id =" . Model::safeSql($sm->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function archive(G_Settings_Memo $sm){
		if(G_Settings_Memo_Helper::isIdExist($sm) > 0){
			$sql = "
				UPDATE ". G_SETTINGS_MEMO ." 
				SET is_archive =" . Model::safeSql(G_Settings_Memo::YES) . "
				WHERE id =" . Model::safeSql($sm->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore(G_Settings_Memo $sm){
		if(G_Settings_Memo_Helper::isIdExist($sm) > 0){
			$sql = "
				UPDATE ". G_SETTINGS_MEMO ." 
				SET is_archive =" . Model::safeSql(G_Settings_Memo::NO) . "
				WHERE id =" . Model::safeSql($sm->getId());
			Model::runSql($sql);
		}	
	}
}
?>