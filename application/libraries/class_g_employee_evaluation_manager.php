<?php
class G_Employee_Evaluation_Manager {

	
	public static function save(G_Employee_Evaluation $gcp) {
		if (G_Employee_Evaluation_Helper::isIdExist($gcp) > 0 ) {

			$sql_start = "UPDATE ". G_EMPLOYEE_EVALUATION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gcp->getId());		
			$action    = "update";

		}else{
			$action    = "insert";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_EVALUATION . " ";
			$sql_end   = " ";		
		}
		
				$sql = $sql_start ."
					SET
					employee_id	     = " . Model::safeSql($gcp->getEmployeeId()) .",
					evaluation_date	 = " . Model::safeSql($gcp->getEvaluationDate()) .",
					next_evaluation_date		 = " . Model::safeSql($gcp->getNextEvaluationDate()) .",
					score		 = " . Model::safeSql($gcp->getScore()) .",			
					attachments	 = " . Model::safeSql($gcp->gettAttachment()) .",
					date_created	 = " . Model::safeSql($gcp->getDateCreated()) .",
					is_archive			 = " . Model::safeSql($gcp->getIsArchive()) ."
					"	
					. $sql_end ."	
				";	
	
		    Model::runSql($sql);
		
			if( $action == 'update' ){
				$id = $gcp->getId();
			}else{
				$id = mysql_insert_id();		
			}
		
		return $id;
				
	}


	public static function updatedFromNotification($evid){


			$sql_start = "UPDATE ". G_EMPLOYEE_EVALUATION . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($evid);	

			$sql = $sql_start ."
					SET
					is_updated = 'Yes'
					
					"	
					. $sql_end ."	
				";	
	
		    Model::runSql($sql);

		    $id = mysql_insert_id();
		    return $id;


	}



}
?>
