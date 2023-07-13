<?php
class G_Exam_Choices_Manager {
	public static function save(G_Exam_Choices $gsl) {
		if (G_Exam_Choices_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_EXAM_CHOICES . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EXAM_CHOICES . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			exam_question_id	 = " . Model::safeSql($gsl->getExamQuestionId()) . ",
			choices		         = " . Model::safeSql($gsl->getChoices()) . ",
			order_by          	 = " . Model::safeSql($gsl->getOrderBy()) . " "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Exam_Choices $gsl){
		if(G_Exam_Choices_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_EXAM_CHOICES ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>