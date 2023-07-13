<?php
class G_Exam_Question_Manager {
	public static function save(G_Exam_Question $gsl) {
		if (G_Exam_Question_Helper::isIdExist($gsl) > 0 ) {
			$sql_start = "UPDATE ". G_EXAM_QUESTION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gsl->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EXAM_QUESTION . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			exam_id				 = " . Model::safeSql($gsl->getExamId()) . ",
			question	         = " . Model::safeSql($gsl->getQuestion()) . ",
			answer		         = " . Model::safeSql($gsl->getAnswer()) . ",
			order_by	         = " . Model::safeSql($gsl->getOrderBy()) . ",
			type		         = " . Model::safeSql($gsl->getType()) . "
			 "
			. $sql_end ."	
		
		";
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Exam_Question $gsl){
		if(G_Exam_Question_Helper::isIdExist($gsl) > 0){
			$sql = "
				DELETE FROM ". G_EXAM_QUESTION ."
				WHERE id =" . Model::safeSql($gsl->getId());
			Model::runSql($sql);
		}	
	}
}
?>