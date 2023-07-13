<?php
class G_Applicant_Requirements_Helper {
	public static function isIdExist(G_Applicant_Requirements $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_APPLICANT_REQUIREMENTS ."
			WHERE id = ". Model::safeSql($gcb->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function loadDefaultApplicantRequirements($aid,$xml_file) {	
		if(Tools::isFileExist($xml_file)==true) {
			$requirements = Requirements::getDefaultRequirements();	
		}else {
			$GLOBALS['hr']['requirements'] = array(
				'Required 2x2 Picture'	=> '',
				'Medical'				=> '',
				'SSS'					=> '',
				'Tin'					=> ''
			);
			foreach($GLOBALS['hr']['requirements'] as $key =>$value) {
				$requirements[Tools::friendlyFormName($key)] = '';
			}	
		}
		
		$r = new G_Applicant_Requirements;
		$r->setApplicantId($aid);
		$r->setRequirements(serialize($requirements));
		$r->setIsComplete(0);
		$r->setDateUpdated(date("Y-m-d"));
		$id = $r->save();
		return $id;	
	}
	
	public static function loadDefaultRequirements(G_Applicant_Requirements $gar) {		
		$requirements = G_Settings_Requirement_Finder::findAllIsNotArchiveByCompanyStructureId($_SESSION['sprint_hr']['company_structure_id']);
		if($requirements){
			foreach($requirements as $r){							
				$rAr[Tools::friendlyFormName($r->getName())] = '';
			}
			$r = new G_Applicant_Requirements;
			if($gar->getId()){
				$r->getId($gar->getId());
			}
			$r->setApplicantId($gar->getApplicantId());
			$r->setRequirements(serialize($rAr));
			$r->setIsComplete(0);
			$r->setDateUpdated(date("Y-m-d"));
			$id = $r->save();
		}
		
		return $id;	
	}
	
	public static function findByApplicantId($applicant_id,$order_by,$limit) {
		$sql = "
			*
			FROM ". G_APPLICANT_REQUIREMENTS ."
			WHERE a.applicant_id=".$applicant_id."
			
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}

}
?>