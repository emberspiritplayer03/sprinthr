<?php
class G_Applicant_Education_Helper {
		
	public static function isIdExist(G_Applicant_Education $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_EDUCATION ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>