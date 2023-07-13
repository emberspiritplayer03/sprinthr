<?php
class G_Break_Time_Schedule_Header_Helper {

    public static function isIdExist(G_Break_Time_Schedule $gbts) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE_HEADER ."
			WHERE id = ". Model::safeSql($gbts->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlTotalActiveRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE_HEADER . "			
		";	

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	

	public static function sqlGetDataById( $id = 0, $fields = array() ) {
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE_HEADER . "			
			WHERE id =" . Model::safeSql($id) . "
		";	

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}	

	public static function sqlGetAllActiveRecords($order_by = "", $limit = "", $fields = array()){		
		if(!empty($fields)){
			$sql_fields = implode(",", $fields);	
		}else{
			$sql_fields = "*";
		}

		if( !empty($order_by) ){
			$order_by = "ORDER BY {$order_by}";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE_HEADER ."			
			{$order_by}
			{$limit}
		";				
		$record = Model::runSql($sql,true);
		return $record;
	}

	public static function sqlGetAllBreaktimeByScheduleInAndOut( $schedule_in, $schedule_out, $fields = array() ){		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}else{
			$sql_fields = " * ";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . BREAK_TIME_SCHEDULE_HEADER . "			
			WHERE schedule_in =" . Model::safeSql($schedule_in) . " AND schedule_out =" . Model::safeSql($schedule_out) . "
			ORDER BY id ASC 
		";	

		$record = Model::runSql($sql,true);
		return $record;
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . BREAK_TIME_SCHEDULE_HEADER			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>