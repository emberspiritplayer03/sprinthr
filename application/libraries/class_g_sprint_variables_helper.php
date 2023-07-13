<?php
class G_Sprint_Variables_Helper {

    public static function isIdExist(G_Sprint_Variables $sv) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SPRINT_VARIABLES ."
			WHERE id = ". Model::safeSql($sv->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlVariableValue( $variable_name = '' ) {
		$sql = "
			SELECT value 
			FROM " . SPRINT_VARIABLES ."
			WHERE variable_name = ". Model::safeSql($variable_name) ."
			ORDER BY id DESC
			LIMIT 1
		";		
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['value'];
	}

	public static function sqlVariableCustomValueA( $variable_name = '' ) {
		$sql = "
			SELECT custom_value_a 
			FROM " . SPRINT_VARIABLES ."
			WHERE variable_name = ". Model::safeSql($variable_name) ."
			ORDER BY id DESC
			LIMIT 1
		";		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['custom_value_a'];
	}

	public static function sqlIsVariableExists( $variable_name = '' ) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SPRINT_VARIABLES . "			
			WHERE variable_name =" . Model::safeSql($variable_name) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		if( $row['total'] > 0 ){
			return true;
		}else{
			return false;
		}
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . SPRINT_VARIABLES			
		;
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}
?>