<?php
class G_Job_Vacancy_Helper {
	public static function isIdExist(G_Job_Vacancy $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_VACANCY ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecords() {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_JOB_VACANCY ."
			
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function createJobVacancyXMLFile($path,$filename) {
		$gj = G_Job_Vacancy_Finder::findAllActiveJobVacancy();
		if($gj){			
			$xml = new DOMDocument("1.0");
	
			$root = $xml->createElement("data");
			$xml->appendChild($root);
			foreach($gj as $j){
				$id     = $xml->createElement("id");
				$idText = $xml->createTextNode($j->getId());
				$id->appendChild($idText);
				
				$job_id    = $xml->createElement("job_id");
				$jobId = $xml->createTextNode($j->getJobId());
				$job_id->appendChild($jobId);
				
				$job_description = $xml->createElement("job_description");
				$jobDescription  = $xml->createTextNode($j->getJobDescription());
				$job_description->appendChild($jobDescription);
				
				$job_title = $xml->createElement("job_title");
				$jobTitle  = $xml->createTextNode($j->getJobTitle());
				$job_title->appendChild($jobTitle);
				
				$publication_date = $xml->createElement("publication_date");
				$publicationDate  = $xml->createTextNode($j->getPublicationDate());
				$publication_date->appendChild($publicationDate);
				
				$advertisement_end = $xml->createElement("advertisement_end");
				$advertisementEnd  = $xml->createTextNode($j->getAdvertisementEnd());
				$advertisement_end->appendChild($advertisementEnd);
				
				$gxml = $xml->createElement("job_vacant");
				$gxml->appendChild($id);
				$gxml->appendChild($job_id);
				$gxml->appendChild($job_description);
				$gxml->appendChild($job_title);
				$gxml->appendChild($publication_date);
				$gxml->appendChild($advertisement_end);				
				$root->appendChild($gxml);
			}
			
			$xml->formatOutput = true;
			//echo "<xmp>". $xml->saveXML() ."</xmp>";
			//$path2 = $path . $filename;
			//echo $path2;
			$xml->save($path . $filename) or die("error");
		}else{
			$err = 0;
		}
		return $err;
	}
}
?>