<?php
class G_Sync_Data_Finder {

     public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findAll($order_by = '', $limit = '') {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 			
			" . $order_by . "
			" . $limit . "		
		";
		return self::getRecords($sql);
	}


	// --------------- LOCAL QUERIES --------------- //
	public static function findByIdLocal($id,$action) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE id =". Model::safeSql($id) ." 
			AND action =".Model::safeSql($action)."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findByPkIdLocalAndTableNameAndActionFromLocal($pk_id_local,$table_name,$action) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE pk_id_local =". Model::safeSql($pk_id_local) ." 
			AND table_name = ".Model::safeSql($table_name)." 
			AND action =".Model::safeSql($action)." 
			AND is_sync =".Model::safeSql(Sync_Data::NO)."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	public static function findParentDataByPkIdLocalFromLocal($pk_id_local,$table_name) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE pk_id_local =". Model::safeSql($pk_id_local) ." 
			AND table_name = ".Model::safeSql($table_name)." 
			AND action =".Model::safeSql('insert')." 
			AND is_sync = ".Model::safeSql(Sync_Data::YES)."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	

	// --------------- LIVE QUERIES --------------- //
	public static function findByIdLive($id,$action) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE id =". Model::safeSql($id) ." 
			AND action =".Model::safeSql($action)."
			LIMIT 1
		";		
		return 
		self::getRecordLive($sql);
	}

	public static function findByPkIdLiveAndTableNameAndActionFromLive($pk_id_live,$table_name,$action) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE pk_id_live =". Model::safeSql($pk_id_live) ." 
			AND table_name = ".Model::safeSql($table_name)." 
			AND action =".Model::safeSql($action)." 
			AND is_sync =".Model::safeSql(Sync_Data::NO)." 
			LIMIT 1
		";		
		return 
		self::getRecordLive($sql);
	}	

	public static function findParentDataByPkIdLiveFromLive($pk_id_live,$table_name) {
		$sql = "
			SELECT * 
			FROM " . SYNC_DATA ." 
			WHERE pk_id_live =". Model::safeSql($pk_id_live) ." 
			AND table_name = ".Model::safeSql($table_name)." 
			AND action =".Model::safeSql('insert')." 
			AND is_sync = ".Model::safeSql(Sync_Data::YES)."
			LIMIT 1
		";		
		return 
		self::getRecord($sql);
	}	
		
	

	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}

	private static function getRecordLive($sql) {

		$c = new Mysqli_Connect();
		$result = $c->query($sql);
		$row = $c->fetchAssoc($result);

		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}

    private static function newObject($row) {
               
        $gai = new G_Sync_Data();
        $gai->setId($row['id']);
        $gai->setTableName($row['table_name']);
        $gai->setPkIdLocal($row['pk_id_local']); 
        $gai->setPkIdLive($row['pk_id_live']); 
        $gai->setAction($row['action']);
        $gai->setIsSync($row['is_sync']);        
        $gai->setDateModified($row['date_modified']);   
        $gai->setDateCreated($row['date_created']);              
        return $gai;
    }

}
?>