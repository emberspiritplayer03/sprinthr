<?php
class G_Sync_Data_Helper {

    public static function isIdExist(G_Sync_Data $gra) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SYNC_DATA ."
			WHERE id = ". Model::safeSql($gra->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SYNC_DATA			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}


	// --------------- LOCAL QUERIES --------------- //
	public static function sqlGetSyncDataFromLocal() {
		$sql = "
			SELECT *
			FROM " . SYNC_DATA . " 
			WHERE is_sync = " . Model::safeSql(Sync_Data::NO)			
		;

		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function sqlCountSyncDataFromLocal() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SYNC_DATA . " 
			WHERE is_sync = " . Model::safeSql(Sync_Data::NO)			
		;

		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function localDataQuery($table_name,$pk_id) {
		$sql = "
			SELECT *
			FROM " . $table_name . "
			WHERE id=" . Model::safeSql($pk_id) 
		;

		$result = Model::runSql($sql,true);
		return $result;
	}

	public static function getConflictFromLocal($values) {

		$sql = "
			SELECT *
			FROM " . SYNC_DATA . " 
			WHERE table_name 		= " . Model::safeSql($values['table_name'] ) . " 
				AND pk_id 			= " . Model::safeSql($values['pk_id'] ) . " 
				AND action 			= " . Model::safeSql($values['action'] ) . " 
				AND date_created 	> " . Model::safeSql($values['date_created'] ) . " 
				AND date_modified 	> " . Model::safeSql($values['date_modified'] ) . " 
			ORDER by id DESC LIMIT 1
			"
		;

		$result = Model::runSql($sql,true);
		return $result;
	}


	// --------------- LIVE QUERIES --------------- //
	public static function sqlGetSyncDataFromLive() {
		$sql = "
			SELECT *
			FROM " . SYNC_DATA . " 
			WHERE is_sync = " . Model::safeSql(Sync_Data::NO)			
		;

		$c = new Mysqli_Connect();
		$result = $c->query($sql,true);
		return $result;
	}

	public static function sqlCountSyncDataFromLive() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SYNC_DATA . " 
			WHERE is_sync = " . Model::safeSql(Sync_Data::NO)		
		;

		$c = new Mysqli_Connect();
		$result = $c->query($sql);
		$row = $c->fetchAssoc($result);
		return $row['total'];
	}

	public static function liveDataQuery($table_name,$pk_id) {
		$sql = "
			SELECT *
			FROM " . $table_name . "
			WHERE id=" . Model::safeSql($pk_id) 
		;

		$c = new Mysqli_Connect();
		$result = $c->query($sql,true);
		return $result;
	}

	public static function getConflictFromLive($values) {

		$sql = "
			SELECT *
			FROM " . SYNC_DATA . " 
			WHERE table_name 		= " . Model::safeSql($values['table_name'] ) . " 
				AND pk_id 			= " . Model::safeSql($values['pk_id'] ) . " 
				AND action 			= " . Model::safeSql($values['action'] ) . " 
				AND date_created 	> " . Model::safeSql($values['date_created'] ) . " 
				AND date_modified 	> " . Model::safeSql($values['date_modified'] ) . " 
			ORDER by id DESC LIMIT 1
			"
		;

		$c = new Mysqli_Connect();
		$result = $c->query($sql,true);
		return $result;
	}
	
}
?>