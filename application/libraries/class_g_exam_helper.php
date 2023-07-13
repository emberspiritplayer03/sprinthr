<?php
class G_Exam_Helper {
	public static function isIdExist(G_Settings_License $gsl) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM ."
			WHERE id = ". Model::safeSql($gsl->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isTitleExists($title,$company_structure_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM ."
			WHERE company_structure_id = ". Model::safeSql($company_structure_id) ."
				AND title =" . Model::safeSql($title) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EXAM			
		;
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function examinationTagToJob($job_id,$applicant_id) {
		$exams = G_Exam_Finder::findAllExamByJobIdAndApplyToAllJobs(Utilities::decrypt($job_id));		
		$counter = 1;	
		if($exams){
			foreach($exams as $e){				
				$exam_code = substr(md5(strtotime(date("Y-m-d H:i:s")) + $counter),0,7);				
				$gcb = new G_Applicant_Examination();				
				$gcb->setCompanyStructureId($e->getCompanyStructureId());
				$gcb->setApplicantId($applicant_id);				
				$gcb->setExamCode($exam_code);
				$gcb->setExamId($e->getId());
				$gcb->setTitle($e->getTitle());
				$gcb->setDescription($e->getDescription());
				$gcb->setPassingPercentage($e->getPassingPercentage());
				//$gcb->setScheduleDate($row['schedule_date']);
				$gcb->setStatus('Pending');
				//$gcb->setScheduledBy($row['scheduled_by']);
				$gcb->save();
				
				$exam_details[$e->getId()]['code'] = $exam_code;
				$exam_details[$e->getId()]['title']= $e->getTitle();
				$exam_details[$e->getId()]['passing_percentage'] = $e->getPassingPercentage();
				
				$counter++;
			}
		}
		return $exam_details;
	}
	
	public static function convertApplicableJobToReadableFormat(G_Exam $g) {			
		if($g->getApplyToAllJobs() == G_Exam::NO){
			if($g->getApplicableToJob()){				
				//$jstring = unserialize($g->getApplicableToJob());
				$jstring = $g->getApplicableToJob();
				$jarray  = explode(",",$jstring);
				foreach($jarray as $key => $value){
					$j = G_Job_Finder::findById($value);
					if($j){
						$new_jarray[] = $j->getTitle(); 	
					}
					 
				}
				$jstring = implode(",",$new_jarray);
				return $jstring;
			
			}else{				
				return $jstring = "Not set";			
			}		
			
		}else{			
			return $jstring = "Applied to all jobs"; 
		}
	}
	
	public static function findByCompanyStructureId($company_id,$order_by,$limit) {
		$sql = "
			SELECT
			*
			FROM
			`g_exam`
			WHERE company_structure_id=".$company_id."

			".$order_by."
			".$limit."

		";

		$result = Model::runSql($sql,true);

		return $result;
	}	
}
?>