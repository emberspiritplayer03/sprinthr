<?php
class Sprint_Tables_Helper {

    public static function sqlIsTableNameExists($table_name = '') {
    	$is_exists     = false;
    	$sql_value     = strtolower($table_name);
    	$database_name = DB_DATABASE;
		
		$sql = "
			SHOW TABLES FROM `{$database_name}` LIKE '{$sql_value}';
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		if( !empty($row) ){
			$is_exists = true;
		}

		return $is_exists;
	}

	public static function sqlCountTotalRecords($table_name = '') {
		$sql_table = trim($table_name);
		$sql = "
			SELECT COUNT(*) as total
			FROM {$sql_table} 				
		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];

	}

	public static function sqlIsFieldTableExists($field_name = '', $table_name = ''){
		$is_exists = false;		

		if( !empty($field_name) ){
			$sql_field_name = strtolower($field_name);
			$sql = "
				SHOW COLUMNS FROM `{$table_name}` LIKE '{$sql_field_name}';
			";

			$result = Model::runSql($sql);
			$row    = Model::fetchAssoc($result);

			if( !empty($row) ){
				$is_exists = true;
			}
		}

		return $is_exists;

	}
}
?>