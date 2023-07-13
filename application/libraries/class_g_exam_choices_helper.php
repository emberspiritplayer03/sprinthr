<?php
class G_Exam_Choices_Helper {
	public static function isIdExist(G_Exam_Choices $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM_CHOICES ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
	public static function countTotalRecords($exam_question_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM_CHOICES . "
			WHERE exam_question_id=".Model::safeSql($exam_question_id)."
			"	
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	
}
?>