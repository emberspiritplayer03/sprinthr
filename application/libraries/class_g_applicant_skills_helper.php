<?php
class G_Applicant_Skills_Helper {
		
	public static function isIdExist(G_Applicant_Skills $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_SKILLS ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>