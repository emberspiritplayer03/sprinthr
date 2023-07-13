<?php
class G_Exam_Question_Helper {
	public static function isIdExist(G_Exam_Question $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM_QUESTION ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByExamId($exam_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM_QUESTION."
			WHERE 
			exam_id=".Model::safeSql($exam_id)."
			"			
		;

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>