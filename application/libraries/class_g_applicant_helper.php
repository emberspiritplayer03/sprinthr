<?php
class G_Applicant_Helper {
	public static function isIdExist(G_Applicant $gcb) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT ."
			WHERE id = ". Model::safeSql($gcb->id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isEmailExists($email) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT ."
			WHERE email_address = ". Model::safeSql($email) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isEmailAndJobIdExists($email,$job_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT ."
			WHERE email_address = ". Model::safeSql($email) ." AND job_id = " . Model::safeSql($job_id) . "
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findHashByApplicantId($id) {
		$sql = "
			SELECT e.hash 
			FROM g_applicant e
			WHERE e.id = ". $id ."	
			LIMIT 1		
		";
		
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function countTotalApplicantPendingApplicationByEmailAddress($email_address) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT ."
			WHERE (email_address = ". Model::safeSql($email_address) .") 
				OR (application_status_id =" . Model::safeSql(APPLICATION_SUBMITTED) . " 
				OR application_status_id =" . Model::safeSql(INTERVIEW) . "
				OR application_status_id =" . Model::safeSql(JOB_OFFERED) . ") 
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyStructureId(G_Company_Structure $gcs) {

		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT ."
			WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsBySearch($company_id,$search='') {
		$sql = "
			SELECT
			a.id
			FROM
			`g_applicant` AS `a`
			Left Join `g_company_structure` AS `c` ON `a`.`company_structure_id` = `c`.`id`
			Left Join `g_job` AS `j` ON `a`.`job_id` = `j`.`id`
			Left Join `g_job_vacancy` AS `v` ON `a`.`job_vacancy_id` = `v`.`id`
			Left Join `g_employee` AS `e` ON `e`.`id` = `v`.`hiring_manager_id`
			Left Join `g_applicant_requirements` AS requirements ON requirements.`applicant_id` = a.id
			WHERE a.company_structure_id=".$company_id."
			".$search."
			GROUP BY
			`a`.`id`
		";
		//echo $sql;
		$result = Model::runSql($sql,true);
		
		return $result;
	
	}
	
	public static function findApplicationDetails($aid) {
		$sql = "
			SELECT a.firstname, a.lastname, a.application_status_id, a.email_address, a.applied_date_time, jv.job_title,
			jv.job_description, jv.hiring_manager_name, jv.publication_date, jv.advertisement_end 
			FROM ". APPLICANT . " a, " . G_JOB_VACANCY . " jv
			WHERE a.id=". Model::safeSql($aid)." 
			AND jv.job_id = a.job_id 
			LIMIT 1
		";
		return Model::runSql($sql,true);		
	}

	public static function findByCompanyStructureId($company_id,$order_by,$limit,$search='') {
	
		$sql = "
			SELECT
			`a`.`id`,
			`a`.`employee_id`,
			a.hash,
			a.photo,
			`a`.`company_structure_id`,
			`a`.`job_vacancy_id`,
			`a`.`job_id`,
			`a`.`application_status_id`,
			CONCAT(`a`.`lastname`,', ', a.firstname , ' ',substring(a.middlename,1,1) ,'. ',a.extension_name) as applicant_name,
			`a`.`lastname`,
			`a`.`firstname`,
			`a`.`middlename`,
			`a`.`extension_name`,
			`a`.`gender`,
			`a`.`birthdate`,
			`a`.`marital_status`,
			`a`.`birth_place`,
			`a`.`address`,
			`a`.`city`,
			`a`.`province`,
			`a`.`zip_code`,
			`a`.`country`,
			`a`.`home_telephone`,
			`a`.`mobile`,
			`a`.`email_address`,
			`a`.`qualification`,
			`a`.`sss_number`,
			`a`.`tin_number`,
			`a`.`pagibig_number`,
			a.philhealth_number,
			`a`.`applied_date_time`,
			 a.hired_date,
			`a`.`resume_name`,
			`a`.`resume_path`,
			`c`.`title` AS `company_name`,
			`j`.`title` AS `job_name`,
			`v`.`hiring_manager_id`,
			CONCAT(`e`.`lastname`,', ', e.firstname) as hiring_manager
			FROM
			`g_applicant` AS `a`
			Left Join `g_company_structure` AS `c` ON `a`.`company_structure_id` = `c`.`id`
			Left Join `g_job` AS `j` ON `a`.`job_id` = `j`.`id`
			Left Join `g_job_vacancy` AS `v` ON `a`.`job_vacancy_id` = `v`.`id`
			Left Join `g_employee` AS `e` ON `e`.`id` = `v`.`hiring_manager_id`
			Left Join `g_job_application_event` AS `event` ON `event`.`applicant_id` = a.id
			Left Join `g_applicant_requirements` AS requirements ON requirements.`applicant_id` = a.id
			WHERE a.company_structure_id=".$company_id."
			".$search."
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function findAllRecentlyImportedByCompanyStructureId($company_id,$recently_imported, $order_by,$limit,$search='') {
	
		$sql = "
			SELECT
			`a`.`id`,
			`a`.`employee_id`,
			a.hash,
			a.photo,
			`a`.`company_structure_id`,
			`a`.`job_vacancy_id`,
			`a`.`job_id`,
			`a`.`application_status_id`,
			CONCAT(`a`.`lastname`,', ', a.firstname , ' ',substring(a.middlename,1,1) ,'. ',a.extension_name) as applicant_name,
			`a`.`lastname`,
			`a`.`firstname`,
			`a`.`middlename`,
			`a`.`extension_name`,
			`a`.`gender`,
			`a`.`birthdate`,
			`a`.`marital_status`,
			`a`.`birth_place`,
			`a`.`address`,
			`a`.`city`,
			`a`.`province`,
			`a`.`zip_code`,
			`a`.`country`,
			`a`.`home_telephone`,
			`a`.`mobile`,
			`a`.`email_address`,
			`a`.`qualification`,
			`a`.`sss_number`,
			`a`.`tin_number`,
			`a`.`pagibig_number`,
			a.philhealth_number,
			`a`.`applied_date_time`,
			 a.hired_date,
			`a`.`resume_name`,
			`a`.`resume_path`,
			`c`.`title` AS `company_name`,
			`j`.`title` AS `job_name`,
			`v`.`hiring_manager_id`,
			CONCAT(`e`.`lastname`,', ', e.firstname) as hiring_manager
			FROM
			(SELECT * FROM g_applicant ORDER BY ID DESC LIMIT " . Model::safeSql($recently_imported) .") as a
			Left Join `g_company_structure` AS `c` ON `a`.`company_structure_id` = `c`.`id`
			Left Join `g_job` AS `j` ON `a`.`job_id` = `j`.`id`
			Left Join `g_job_vacancy` AS `v` ON `a`.`job_vacancy_id` = `v`.`id`
			Left Join `g_employee` AS `e` ON `e`.`id` = `v`.`hiring_manager_id`
			Left Join `g_job_application_event` AS `event` ON `event`.`applicant_id` = a.id
			Left Join `g_applicant_requirements` AS requirements ON requirements.`applicant_id` = a.id
			WHERE a.company_structure_id=".$company_id."
			".$search."
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function getTotalApplicantByMonth($year,$month=0) {
		$year = ($year=='')? date("Y")  : $year ;
		$search = ($month==0)? '' : 'AND MONTH( date_time_event )='.$month ;
		$sql = "SELECT YEAR(date_time_event) as year, MONTH( date_time_event ) as month , 
				SUM( IF( event_type =0, 1, 0 ) ) AS application_submitted, 
				SUM( IF( event_type =5, 1, 0 ) ) AS hired, 
				SUM( IF( event_type =4 or event_type=3, 1, 0 ) ) AS  declined
				FROM g_job_application_event
				WHERE YEAR( date_time_event ) =  ".$year."
				".$search."
				GROUP BY year,month
		";				
		$rec = Model::runSql($sql,true);
		return $rec;
	}
	
	public static function findByApplicantId($applicant_id,$order_by,$limit) {
		$sql = "
			SELECT
			`a`.`id`,
			`a`.`employee_id`,
			a.hash,
			a.photo,
			`a`.`company_structure_id`,
			`a`.`job_vacancy_id`,
			`a`.`job_id`,
			`a`.`application_status_id`,
			CONCAT(`a`.`lastname`,', ', a.firstname , ' ', a.middlename,' ',a.extension_name) as applicant_name,
			`a`.`firstname`,
			a.lastname,
			`a`.`middlename`,
			`a`.`extension_name`,
			`a`.`gender`,
			`a`.`birthdate`,
			`a`.`marital_status`,
			`a`.`birth_place`,
			`a`.`address`,
			`a`.`city`,
			`a`.`province`,
			`a`.`zip_code`,
			`a`.`country`,
			`a`.`home_telephone`,
			`a`.`mobile`,
			`a`.`email_address`,
			`a`.`qualification`,
			`a`.`sss_number`,
			`a`.`tin_number`,
			`a`.`pagibig_number`,
			a.philhealth_number,
			`a`.`applied_date_time`,
			 a.hired_date,
			`a`.`resume_name`,
			`a`.`resume_path`,
			`c`.`title` AS `company_name`,
			`j`.`title` AS `job_name`,
			`v`.`hiring_manager_id`,
			CONCAT(`e`.`lastname`,', ', e.firstname) as hiring_manager
			FROM
			`g_applicant` AS `a`
			Left Join `g_company_structure` AS `c` ON `a`.`company_structure_id` = `c`.`id`
			Left Join `g_job` AS `j` ON `a`.`job_id` = `j`.`id`
			Left Join `g_job_vacancy` AS `v` ON `a`.`job_vacancy_id` = `v`.`id`
			Left Join `g_employee` AS `e` ON `e`.`id` = `v`.`hiring_manager_id`
			WHERE a.id=".$applicant_id."
			
			GROUP BY
			`a`.`id`
			
			".$order_by."
			".$limit."
		";
		//echo $sql;
		//$result = Model::runSql($sql,true);
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		
		return $row;
	}
	
	public static function getNextHash($applicant_id)
	{
		$sql = "
			SELECT
			a.hash
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id>".Model::safeSql($applicant_id)."
			ORDER BY a.id ASC		
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['hash'];
	}
	
		public static function getPreviousHash($applicant_id)
	{
		$sql = "
			SELECT
			a.hash
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id<".Model::safeSql($applicant_id)."
			ORDER BY a.id DESC		
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['hash'];
	}
	
	
	public static function getNextId($applicant_id)
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id>".Model::safeSql($applicant_id)."
			ORDER BY a.id ASC		
			LIMIT 1
			";
		$result = Model::runSql($sql,true);

		return  $result[0]['id'];
	}
	
	public static function getPreviousId($applicant_id)
	{
		$sql = "
			SELECT
			a.id
			FROM
			`g_applicant` AS `a`
			
			WHERE a.id<".Model::safeSql($applicant_id)."
			ORDER BY a.id DESC		
			LIMIT 1
			";

		$result = Model::runSql($sql,true);

		return $result[0]['id'];
	}
	
	public static function getDynamicQueries($queries) {

		$field_list = array('applied position',
							'date applied',
							'lastname',
							'firstname',
							'birthdate',
							'gender',
							'marital status',
							'address',
							'city',
							'province',
							'skills',
							'license',
							'course',
							'attainment',
							'requirements',
							'status');
		
		
				$result = explode(':',$queries);
				$ctr=0;
				$query='';
				
				foreach($result as $key=>$value) {
					
					if(substr_count($value,',')==1) { //with comma
						$r = explode(',',$value);
						foreach($r as $key=>$vl){
							if($ctr==0) {/* add category */
								$ctr=1;
								$str = ($vl=='') ? "" : $vl ;	
								
								$field = Tools::searchInArray($field_list,strtolower($vl));
								$category = strtolower($field[0]);	
								
								$category = strtolower($str);
							}else { /* add value*/
								$ctr=0;$str = ($vl=='') ? "" : $vl ;
								$or = (strlen($query[$category])>0) ? ' /OR/ ': '' ; 
								$query[$category].= $or. strtolower($str);
							}	
						}
					}else { // no comma

						if($ctr==0) {/* add category*/
							$ctr=1;
							$field = Tools::searchInArray($field_list,strtolower($value));
							$y=0;
							foreach($field as $key=>$f) {
								if($y==0) {
									$field = $f;	
								}
								$y++;	
							}
							$category = strtolower($f);		
						}else { /* add value*/
							$ctr=0;	
							$or = (strlen($query[$category])>0) ? ' /OR/ ': '' ; 
							$query[$category].= $or. strtolower($value);
						}
					}
				}			
			$field_list = array(
							'applied position'=>'j.title',
							'date applied'=>'a.applied_date_time',
							'lastname'=>'a.lastname',
							'firstname'=>'a.firstname',
							'birthdate'=>'a.birthdate',
							'gender'=>'a.gender',
							'marital status'=>'a.marital_status',
							'address'=>'a.address',
							'city'=>'a.city',
							'province'=>'a.province',
							'skills'=>'s.skill',
							'license'=>'license.type',
							'course'=>'education.course',
							'attainment'=>'education.attainment',
							'requirements'=>'requirements.is_complete',
							'status'=>'a.application_status_id');
				$x=1;
				$total_query = count($query);
				$has_basic=0;
				$has_more_queries=0;
				$is_first_time=1;
				
				foreach($query as $key=>$value) {
				
					if($value!='') {
						if($field_list[$key]!="") {
							$q[$field_list[$key]].=$value;
			
							if(substr_count($value, '/OR/')>0) {
								
								$has_more_queries=1;
									
								$v = explode("/OR/",$value);
								$r = count($v);
								$ctr=1;
								$xx='';
								foreach($v as $k=>$str) {
									//this is for OR LIKE
									if($field_list[$key]=='a.application_status_id') {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$str = trim($str);
										if($str=='pending') {
											$str=APPLICATION_SUBMITTED;	
										}elseif($str=='interview') {
											$str=INTERVIEW;	
										}elseif($str=='hired') {
											$str=HIRED;	
										}elseif($str=='declined') {
											$str=OFFER_DECLINED;	
										}elseif($str=='rejected') {
											$str=REJECTED;	
										}
										$xx.=$field_list[$key]."=".trim($str)."".$comma;
									}else {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$xx.=$field_list[$key]." LIKE '%".trim($str)."%'".$comma;	
									}
									
									//END OF OR LIKE
									$ctr++;
								}		
								$sep.= " AND ". "(". $xx.")";	
							}else {
								
								$has_basic=1;

								if($field_list[$key]=='a.gender') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '". $value ."%' ";
								}elseif($field_list[$key]=='a.application_status_id') {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									if($value=='pending') {
										$value=APPLICATION_SUBMITTED;	
									}elseif($value=='interview') {
										$value=INTERVIEW;	
									}elseif($value=='hired') {
										$value=HIRED;	
									}elseif($value=='declined') {
										$value=OFFER_DECLINED;	
									}elseif($value=='rejected') {
										$value=REJECTED;	
									}
									$search.= $where.$and. " $field_list[$key]=". Model::safeSql($value) ." ";	
								}elseif($field_list[$key]=='requirements.is_complete') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									if($value=='incomplete'){
										$value=0;
									}elseif($value=='complete') {
										$value=1;	
									}
									$search.= $where.$and. " $field_list[$key]=". $value ." ";
								}else {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '%". $value ."%' ";	
								}
								if($is_first_time==1) {	$is_first_time=0;}
							}
						}	
					}
					$x++;
				}

			if($total_query>1) {
				if($has_basic==1) {
				
					$search = "AND (".$search.")";	
				}
			}else {
				if($has_basic==1) {
					$search = "AND (".$search.")";	
				}
			}
			
			$search.=$sep;			
			return $search;
	}

}
?>