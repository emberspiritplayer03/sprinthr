<?php
class G_Performance_Helper {
	public static function isIdExist(G_Perfomance $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isTitleExists($title,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
				AND title =" . Model::safeSql($title) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
				AND is_archive =" . Model::safeSql(G_Performance::NO) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsIsArchiveByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PERFORMANCE ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
				AND is_archive =" . Model::safeSql(G_Performance::YES) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByCompanyStructureId($company_id,$order_by,$limit) {
		$sql = "
			SELECT
			*
			FROM
			`g_performance`
			WHERE company_structure_id=".$company_id."
				AND is_archive =" . Model::safeSql(G_Performance::NO) . "

			".$order_by."
			".$limit."

		";

		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function findAllIsArchiveByCompanyStructureId($company_id,$order_by,$limit) {
		$sql = "
			SELECT
			*
			FROM
			`g_performance`
			WHERE company_structure_id=".$company_id."
				AND is_archive =" . Model::safeSql(G_Performance::YES) . "

			".$order_by."
			".$limit."

		";

		$result = Model::runSql($sql,true);

		return $result;
	}		
}
?>