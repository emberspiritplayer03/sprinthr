<?php
class G_Applicant_Work_Experience_Helper {
		
	public static function isIdExist(G_Applicant_Work_Experience $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_WORK_EXPERIENCE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>