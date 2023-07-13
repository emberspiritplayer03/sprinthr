<?php
class G_Group_Restday_Helper {

    public static function isIdExist(G_Group_Restday $grd) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . GROUP_RESTDAY ."
			WHERE id = ". Model::safeSql($grd->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlIsDateAndGroupIdExists($date = '', $group_id = 0) {
		$sql_date = date("Y-m-d",strtotime($date));

		$sql = "
			SELECT COUNT(id) as total
			FROM " . GROUP_RESTDAY ."
			WHERE group_id = ". Model::safeSql($group_id) ."
				AND date = " . Model::safeSql($date) . "
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);

		if( $row['total'] > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public static function sqlGetRestDayByGroupId( $group_id = '', $fields = array() ) {
		$sql_fields = " * ";
		
		if( !empty($fields) ){
			$sql_fields = implode(",", $fields);
		}

		$sql = "
			SELECT {$sql_fields}	
			FROM " . GROUP_RESTDAY . "  				
			WHERE group_id = ".Model::safeSql($group_id)."				
			ORDER BY date ASC
		";		
		$result = Model::runSql($sql,true);
		return $result;
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . GROUP_RESTDAY			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>