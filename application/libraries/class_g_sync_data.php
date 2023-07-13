<?php

class G_Sync_Data extends Sync_Data {
	
	public function __construct() {
		
	}

	public function check_connection() {
		$mysqli = new Mysqli_Connect();
		if(!$mysqli->is_connected()){
			return false;
		}
		return true;
	}

	public function sync() {
		$has_sync_local	= false;
		$has_sync_live 	= false;
		
		if($this->countSyncDataFromLive() > 0) {
			$sync_data_live = $this->getSyncDataFromLive();
			$has_sync_live 	= true;
		}

		if($this->countSyncDataFromLocal() > 0) {
			$sync_data_local 	= $this->getSyncDataFromLocal();
			$has_sync_local 	= true;
		}

		if($has_sync_local) {
			// Data to be synchronize from LOCAL
			foreach($sync_data_local as $key => $value) {
				if($value['pk_id_local'] > 0) {
					// Get the data from their respective table using primary key id
					$q = G_Sync_Data_Helper::localDataQuery($value['table_name'],$value['pk_id_local']);

					// Create data array that will be group by table name > action > sync_data_id(primary key of g_sync_data)
					if($value['action'] == "delete") {
						$q[]['id'] = $value['pk_id_local'];
						$data_local[$value['table_name']][$value['action']][$value['id']] = $q[0]; 
					}else{
						$data_local[$value['table_name']][$value['action']][$value['id']] = $q[0]; 
					}
				}
				
			}
			//SYNC data from LOCAL to LIVE
			$this->synchronizer($data_local, "live");
		}

		if($has_sync_live) {
			// Data to be synchronize from LIVE
			//Utilities::displayArray($sync_data_live);
			foreach($sync_data_live as $key => $value) {
				if($value['pk_id_live'] > 0) {
					// Get the data from their respective table using primary key id
					$q = G_Sync_Data_Helper::liveDataQuery($value['table_name'],$value['pk_id_live']);

					// Create data array that will be group by table name > action > sync_data_id(primary key of g_sync_data)
					if($value['action'] == "delete") {
						$q[]['id'] = $value['pk_id_live'];
						$data_live[$value['table_name']][$value['action']][$value['id']] = $q[0]; 
					}else{
						$data_live[$value['table_name']][$value['action']][$value['id']] = $q[0]; 
					}
				}
			}
			//SYNC data from LIVE to LOCAL
			$this->synchronizer($data_live, "local");
		}

		if(!$has_sync_live && !$has_sync_local) {
			return false;
		}else{
			return true;
		}

	}

	public function synchronizer($data = array(), $save_point) {
		if(!empty($data)) {
			//Utilities::displayArray($data);
			foreach($data as $table_name => $values) {
				foreach($values as $action => $value) {
					foreach($value as $sync_data_id => $d) {
						$data_fields = array();
						$data_items = array();
						foreach($d as $fields => $v) {
							$data_fields[] 	= $fields;
							$data_items[] 	= Model::safeSql($v);
						}

						unset($data_fields[0]);	//remove id	
						unset($data_items[0]); //remove id

						if($action == "insert") {	
							foreach($data_items as $k => $v) {
								$insert_values[] = $data_fields[$k]."=".$v;
							}
							
							$sql_insert = "INSERT INTO ".$table_name. " SET ".implode(",",$insert_values);
							//echo "save to ". $save_point . " ".$sql_insert . "<br>";
							if($save_point == "live") {
								$pk_id_live = $this->saveToLive($sql_insert);

								$local_sd = G_Sync_Data_Finder::findByIdLocal($sync_data_id,$action);
								if($local_sd) {
									$pk_id_local = $local_sd->getPkIdLocal();
									$local_sd->setPkIdLive($pk_id_live);
									$local_sd->setIsSync(Sync_Data::YES);
									G_Sync_Data_Manager::updateLocal($local_sd);
								}

								$live_sd = G_Sync_Data_Finder::findByPkIdLiveAndTableNameAndActionFromLive($pk_id_live,$table_name,$action);
								if($live_sd) {
									$live_sd->setPkIdLocal($pk_id_local);
									$live_sd->setIsSync(Sync_Data::YES);
									G_Sync_Data_Manager::updateLive($live_sd);
								}

							}elseif($save_point == "local") {
								$pk_id_local = $this->saveToLocal($sql_insert);

								$live_sd = G_Sync_Data_Finder::findByIdLive($sync_data_id,$action);
								if($live_sd) {
									$pk_id_live = $live_sd->getPkIdLive();
									$live_sd->setPkIdLocal($pk_id_local);
									$live_sd->setIsSync(Sync_Data::YES);
									G_Sync_Data_Manager::updateLive($live_sd);
								}

								$local_sd = G_Sync_Data_Finder::findByPkIdLocalAndTableNameAndActionFromLocal($pk_id_local,$table_name,$action);
								if($local_sd) {

									$local_sd->setPkIdLive($pk_id_live);
									$local_sd->setIsSync(Sync_Data::YES);
									G_Sync_Data_Manager::updateLocal($local_sd);
								}

							}
							$insert_values = array();

						}elseif($action == "update"){							
							foreach($data_items as $k => $v) {
								$update_values[] = $data_fields[$k]."=".$v;
							}
							$sql_update = "UPDATE ".$table_name." SET ".implode(",",$update_values) . " WHERE id=";
							if($save_point == "live") {
								$local_sd = G_Sync_Data_Finder::findByIdLocal($sync_data_id,$action);
								if($local_sd) {
									$parent_sd = G_Sync_Data_Finder::findParentDataByPkIdLocalFromLocal($local_sd->getPkIdLocal(),$table_name);
									if($parent_sd) {
										//echo "save to ". $save_point . " ".$sql_update .$parent_sd->getPkIdLive(). "<br>";
										$this->saveToLive($sql_update.$parent_sd->getPkIdLive());
										$deleteLocalIds[] = $local_sd->getId();

										$live_sd = G_Sync_Data_Finder::findByPkIdLiveAndTableNameAndActionFromLive($parent_sd->getPkIdLive(),$table_name,$action);
										if($live_sd) {
											$deleteLiveIds[] = $live_sd->getId();
										}
									}else{
										$query = G_Sync_Data_Helper::liveDataQuery($table_name,$local_sd->getPkIdLocal());
										if($query) {
											$this->saveToLive($sql_update.$local_sd->getPkIdLocal());
											$deleteLocalIds[] = $local_sd->getId();

											$live_sd = G_Sync_Data_Finder::findByPkIdLiveAndTableNameAndActionFromLive($local_sd->getPkIdLocal(),$table_name,$action);
											if($live_sd) {
												$deleteLiveIds[] = $live_sd->getId();
											}
										}else{
											$sql_insert = "INSERT INTO ".$table_name. " SET ".implode(",",$update_values);
											$pk_id_live = $this->saveToLive($sql_insert);

											$pk_id_local = $local_sd->getPkIdLocal();

											$new_local_sd = new G_Sync_Data();
											$new_local_sd->setTableName($table_name);
											$new_local_sd->setPkIdLocal($pk_id_local);
											$new_local_sd->setPkIdLive($pk_id_live);
											$new_local_sd->setIsSync(Sync_Data::YES);
											$new_local_sd->setAction("insert");
											$new_local_sd->setDateCreated(date("Y-m-d H:i:s"));
											$new_local_sd->setDateModified(date("Y-m-d H:i:s"));
											G_Sync_Data_Manager::insertLocal($new_local_sd);
											
											$live_sd = G_Sync_Data_Finder::findByPkIdLiveAndTableNameAndActionFromLive($pk_id_live,$table_name,"insert");
											if($live_sd) {
												$live_sd->setPkIdLocal($pk_id_local);
												$live_sd->setIsSync(Sync_Data::YES);
												G_Sync_Data_Manager::updateLive($live_sd);
											}
										}
									}
								}
								
							}elseif($save_point == "local") {
								$live_sd = G_Sync_Data_Finder::findByIdLive($sync_data_id,$action);
								if($live_sd) {
									$parent_sd = G_Sync_Data_Finder::findParentDataByPkIdLiveFromLive($live_sd->getPkIdLive(),$table_name);
									if($parent_sd) {
										//echo "save to ". $save_point . " ".$sql_update .$parent_sd->getPkIdLocal(). "<br>";
										$this->saveToLocal($sql_update.$parent_sd->getPkIdLocal());
										$deleteLiveIds[] = $live_sd->getId();

										$local_sd = G_Sync_Data_Finder::findByPkIdLocalAndTableNameAndActionFromLocal($parent_sd->getPkIdLocal(),$table_name,$action);
										if($local_sd) {
											$deleteLocalIds[] = $local_sd->getId();
										}
									}else{
										$query = G_Sync_Data_Helper::localDataQuery($table_name,$live_sd->getPkIdLive());
										if($query) {
											$this->saveToLocal($sql_update.$live_sd->getPkIdLive());
											$deleteLiveIds[] = $live_sd->getId();

											$local_sd = G_Sync_Data_Finder::findByPkIdLocalAndTableNameAndActionFromLocal($live_sd->getPkIdLive(),$table_name,$action);
											if($local_sd) {
												$deleteLocalIds[] = $local_sd->getId();
											}
										}else{
											$sql_insert = "INSERT INTO ".$table_name. " SET ".implode(",",$update_values);
											$pk_id_local = $this->saveToLocal($sql_insert);

											$pk_id_live = $live_sd->getPkIdLive();

											$new_live_sd = new G_Sync_Data();
											$new_live_sd->setTableName($table_name);
											$new_live_sd->setPkIdLocal($pk_id_local);
											$new_live_sd->setPkIdLive($pk_id_live);
											$new_live_sd->setIsSync(Sync_Data::YES);
											$new_live_sd->setAction("insert");
											$new_live_sd->setDateCreated(date("Y-m-d H:i:s"));
											$new_live_sd->setDateModified(date("Y-m-d H:i:s"));
											G_Sync_Data_Manager::insertLive($new_live_sd);
											
											$local_sd = G_Sync_Data_Finder::findByPkIdLocalAndTableNameAndActionFromLocal($pk_id_local,$table_name,"insert");
											if($local_sd) {
												$local_sd->setPkIdLive($pk_id_live);
												$local_sd->setIsSync(Sync_Data::YES);
												G_Sync_Data_Manager::updateLocal($local_sd);
											}
										}
									}
								}
							}
							$update_values = array();

						}elseif($action == "delete"){
							if($save_point == "live") {
								$local_sd = G_Sync_Data_Finder::findByIdLocal($sync_data_id,$action);
								if($local_sd) {
									$parent_sd = G_Sync_Data_Finder::findParentDataByPkIdLocalFromLocal($local_sd->getPkIdLocal(),$table_name);
									if($parent_sd) {
										$sql_delete = "DELETE FROM ".$table_name." WHERE id=".$parent_sd->getPkIdLive();
										//echo "save to ". $save_point . " ".$sql_delete ."<br>";
										$this->saveToLive($sql_delete);
										$deleteLocalIds[] = $local_sd->getId();

										$live_sd = G_Sync_Data_Finder::findByPkIdLiveAndTableNameAndActionFromLive($parent_sd->getPkIdLive(),$table_name,$action);
										if($live_sd) {
											$deleteLiveIds[] = $live_sd->getId();
										}
									}
								}
								
							}elseif($save_point == "local") {
								$live_sd = G_Sync_Data_Finder::findByIdLive($sync_data_id,$action);
								if($live_sd) {
									$parent_sd = G_Sync_Data_Finder::findParentDataByPkIdLiveFromLive($live_sd->getPkIdLive(),$table_name);
									if($parent_sd) {
										$sql_delete = "DELETE FROM ".$table_name." WHERE id=".$parent_sd->getPkIdLocal();
										//echo "save to ". $save_point . " ".$sql_delete ."<br>";
										$this->saveToLocal($sql_delete);
										$deleteLiveIds[] = $live_sd->getId();

										$local_sd = G_Sync_Data_Finder::findByPkIdLocalAndTableNameAndActionFromLocal($parent_sd->getPkIdLocal(),$table_name,$action);
										if($local_sd) {
											$deleteLocalIds[] = $local_sd->getId();
										}
									}
								}
							}

						}
						
					}

					if(!empty($deleteLocalIds)) {
						$ids = implode(",",$deleteLocalIds);
						$sql = "DELETE FROM " . SYNC_DATA . "
            						WHERE (id) IN (".$ids.") ";
            			$this->saveToLocal($sql);
            			$deleteLocalIds = array();
					}

					if(!empty($deleteLiveIds)) {
						$ids = implode(",",$deleteLiveIds);
						$sql = "DELETE FROM " . SYNC_DATA . "
            						WHERE (id) IN (".$ids.") ";
            			$this->saveToLive($sql);
            			$deleteLiveIds = array();
					}

				}
			}
		}
	}

	public function saveToLocal($sql) {
		Model::runSql($sql);
		return mysql_insert_id();
	}

	public function saveToLive($sql) {
		$c = new Mysqli_Connect();
		$c->query($sql);
		return $c->getLastInsertedId();
	}

	public function getSyncDataFromLive() {
		return $data = G_Sync_Data_Helper::sqlGetSyncDataFromLive();
	}

	public function countSyncDataFromLive() {
		return $data = G_Sync_Data_Helper::sqlCountSyncDataFromLive();
	}

	public function getSyncDataFromLocal() {
		return $data = G_Sync_Data_Helper::sqlGetSyncDataFromLocal();
	}

	public function countSyncDataFromLocal() {
		return $data = G_Sync_Data_Helper::sqlCountSyncDataFromLocal();
	}
					
	public function save() {
		return G_Sync_Data_Manager::save($this);
	}
		
	public function delete() {
		G_Sync_Data_Manager::delete($this);
	}
}
?>