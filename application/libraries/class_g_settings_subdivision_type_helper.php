<?php
class G_Settings_Subdivision_Type_Helper {
	public static function isIdExist(G_Settings_Subdivision_Type $gsst) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SUBDIVISION_TYPE ."
			WHERE id = ". Model::safeSql($gsst->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SUBDIVISION_TYPE ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function subCountTotalRecordsByCompanyStructureId($csid) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SUBDIVISION_TYPE ."
			WHERE company_structure_id = ". Model::safeSql($csid) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . SUBDIVISION_TYPE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function objectToArray($data)
	{
	  if(is_array($data) || is_object($data))
	  {
			$result = array(); 
			foreach($data as $key => $value)
			{ 
			  $result[$key] = $value;
			}
			return $result;
	  }
	  return $data;
	}
}
?>