<?php
class G_Applicant_Language_Helper {
		
	public static function isIdExist(G_Applicant_Language $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_LANGUAGE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>