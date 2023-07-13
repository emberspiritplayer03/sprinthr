<?php
class G_Applicant_License_Helper {
		
	public static function isIdExist(G_Applicant_License $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_LICENSE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>