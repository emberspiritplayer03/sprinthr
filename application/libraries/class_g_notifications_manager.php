<?php
class G_Notifications_Manager {
	public static function save(G_Notifications $n) {
		if (G_Notifications_Helper::isIdExist($n) > 0 ) {
			$sql_start = "UPDATE ". G_NOTIFICATIONS . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($n->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_NOTIFICATIONS . " ";
			$sql_end  = "";		
		}
				
		$sql = $sql_start ."
			SET
			event_type                                       =" . Model::safeSql($n->getEventType()) . ",
			description                                      =" . Model::safeSql($n->getDescription()) . ",
			status                                           =" . Model::safeSql($n->getStatus()) . ",
			item                                             =" . Model::safeSql($n->getItem()) . ",
			date_modified                                    =" . Model::safeSql($n->getDateModified()) . ",
			date_created                                     =" . Model::safeSql($n->getDateCreated()) . " "			
			. $sql_end ."	
		";					
 		Model::runSql($sql);
		return mysql_insert_id();
	}	
		
	public static function delete(G_Notifications $n){
		if(G_Notifications_Helper::isIdExist($n) > 0){
			$sql = "
				DELETE FROM ". G_NOTIFICATIONS ."
				WHERE id =" . Model::safeSql($n->getId());
			Model::runSql($sql);
		}	
	}
}
?>