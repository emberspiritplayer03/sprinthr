<?php
class G_Applicant_Training_Helper {
		
	public static function isIdExist(G_Applicant_Training $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_TRAINING ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>