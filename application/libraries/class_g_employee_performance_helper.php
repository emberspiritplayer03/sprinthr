<?php
class G_Employee_Performance_Helper {
		
	public static function isIdExist(G_Employee_Performance $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_PERFORMANCE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function findByCompanyStructureId($company_structure_id,$order_by,$limit) {
		$sql = "
			SELECT
			p.id,
			p.company_structure_id,
			p.performance_id,
			p.performance_title,
			p.employee_id,
			p.reviewer_id,
			p.created_by,
			p.position,
			DATE_FORMAT(p.created_date, '%W %D %M %Y') as created_date,
			DATE_FORMAT(p.period_from, '%W %D %M %Y') as period_from,
			DATE_FORMAT(p.period_to, '%W %D %M %Y') as period_to,
			DATE_FORMAT(p.due_date, '%W %D %M %Y') as due_date,
			p.status,
			p.kpi,
			CONCAT(r.lastname,', ', r.firstname) as review_by,
			CONCAT(e.lastname,', ', e.firstname) as employee_name
			FROM ". G_EMPLOYEE_PERFORMANCE ." p  
			Left Join ".EMPLOYEE."  AS e ON e.id=p.employee_id
			Left Join ".EMPLOYEE."  AS r ON r.id=p.reviewer_id
			WHERE e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND e.company_structure_id=".$company_structure_id." 
			".$order_by."
			".$limit."
		";

		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function advanceSearchfindByCompanyStructureId($company_structure_id,$order_by,$limit,$search='') {
		$sql = "
			SELECT
			p.id,
			p.company_structure_id,
			p.performance_id,
			p.performance_title,
			p.employee_id,
			p.reviewer_id,
			p.created_by,
			p.position,
			DATE_FORMAT(p.created_date, '%W %D %M %Y') as created_date,
			DATE_FORMAT(p.period_from, '%W %D %M %Y') as period_from,
			DATE_FORMAT(p.period_to, '%W %D %M %Y') as period_to,
			DATE_FORMAT(p.due_date, '%W %D %M %Y') as due_date,
			p.status,
			p.kpi,
			CONCAT(r.lastname,', ', r.firstname) as review_by,
			CONCAT(e.lastname,', ', e.firstname) as employee_name
			FROM ". G_EMPLOYEE_PERFORMANCE ." p  
				LEFT JOIN " . EMPLOYEE . "  AS e ON e.id = p.employee_id
				LEFT JOIN " . EMPLOYEE . "  AS r ON r.id = p.reviewer_id
				LEFT JOIN `g_employee_job_history` AS `j` ON  (`j`.`employee_id` = `e`.`id` AND `j`.`end_date` = '' ) OR (j.employee_id=e.id AND j.employment_status='Terminated')
				LEFT JOIN " . G_JOB . "  AS job ON job.id = j.job_id
			WHERE (e.e_is_archive =" . Model::safeSql(G_Employee::NO) . " AND e.company_structure_id=". Model::safeSql($company_structure_id) .") 
			".$search."
			".$order_by."
			".$limit."
		";
		
		//echo $sql;
		$result = Model::runSql($sql,true);

		return $result;
	}
	
	public static function getDynamicQueries($queries) {
				$field_list = array(
							'branch',
							'department',
							'position',
							'employment status',
							'employee id',
							'lastname',
							'firstname',
							'birthdate',
							'age','gender',
							'marital status',
							'address',
							'gender',
							'city',
							'home telephone',
							'mobile',
							'work email',
							'hired date',
							'terminated date',
							'end of contract',
							'tags',
							'period from',
							'period',
							'reviewer');
		
		
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
							'branch'=>'company_branch.name',
							'department'=>'d.name',
							'position'=>'j.name',
							'employment status'=>'j.employment_status',
							'employee id'=>'e.employee_code',
							'lastname'=>'e.lastname',
							'firstname'=>'e.firstname',
							'birthdate'=>'e.birthdate',
							'age'=>'age',
							'gender'=>'e.gender',
							'marital status'=>'e.marital_status',
							'address'=>'contact.address',
							'city'=>'contact.city',
							'home telephone'=>'contact.home_telephone',
							'mobile'=>'contact.mobile',
							'work email'=>'contact.work_email',
							'hired date'=>'e.hired_date',
							'terminated date'=>'e.terminated_date',
							'end of contract'=>'contract.end_date',
							'tags'=>'t.tags',							
							'period'=>'period',
							'reviewer'=> 'p.reviewer_id');
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
									if($field_list[$key]=='e.employee_code') {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$xx.=$field_list[$key]."='".trim($str)."'".$comma;
									}elseif($field_list[$key]=='period'){
										$comma = ($r==$ctr)? '': ' OR ' ;
										$periods = explode("to",$str);
										$xx.= " p.period_from >= ". Model::safeSql($periods[0]) ." AND p.period_to <= " . Model::safeSql($periods[1]) .$comma;
									}else {
										$comma = ($r==$ctr)? '': ' OR ' ;
										$xx.=$field_list[$key]." LIKE '%".trim($str)."%'".$comma;
									}
									$ctr++;
								}		
								$sep.= " AND ". "(". $xx.")";	
							}else {
								
								$has_basic=1;

								if($field_list[$key]=='e.employee_code') {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key]='". $value ."' ";
								}elseif($field_list[$key]=='p.reviewer_id') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									//$xx.= " e.lastname LIKE '%".trim($str)."%' OR e.firstname LIKE '%" .trim($str) . "%'" .$comma;
									$employees = G_EmployeE_Helper::findByLastnameFirstname($value);
									foreach($employees as $e){
											$search.= $where.$and. " $field_list[$key]=". $e['id'] ." ";
									}
								}elseif($field_list[$key]=='period') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									//$xx.= " e.lastname LIKE '%".trim($str)."%' OR e.firstname LIKE '%" .trim($str) . "%'" .$comma;
									$periods = explode("to",$value);
									$search.= $where.$and. " p.period_from >= ". Model::safeSql($periods[0]) ." AND p.period_to <=" . Model::safeSql($periods[1]);
								}elseif($field_list[$key]=='e.gender') {
									$and = ($x<=$total_query && $value=='' )? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '". $value ."%' ";
								}else {
									$and = ($x<=$total_query && $value=='')? '': ' AND ' ;
									$and = ($is_first_time==1)? '' : $and ;
									$search.= $where.$and. " $field_list[$key] LIKE '%". $value ."%' ";	
								}
								
								if($is_first_time==1) {
									$is_first_time=0;
								}
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
				//$search = "AND (e.e_is_archive = '" . G_Employee::NO . "') " .  $search;
		return $search;
	}
	
	public static function employeePerformanceResultsSummary($summaryAr){
		$sortedSummary = self::sortPerformanceResultsSummary($summaryAr);
		foreach($sortedSummary as $key => $value){
			$newAr[] = $key . ": " . $value;
		}
		return implode(",",$newAr);
		
	}
	
	public static function sortPerformanceResultsSummary($summaryAr){
		if(!empty($summaryAr[$GLOBALS['hr']['performance_rate'][RATE_5]])){
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_5]] = $summaryAr[$GLOBALS['hr']['performance_rate'][RATE_5]];
		}else{
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_5]] = 0;
		}
		
		if(!empty($summaryAr[$GLOBALS['hr']['performance_rate'][RATE_4]])){
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_4]] = $summaryAr[$GLOBALS['hr']['performance_rate'][RATE_4]];
		}else{
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_4]] = 0;
		}
		
		if(!empty($summaryAr[$GLOBALS['hr']['performance_rate'][RATE_3]])){
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_3]] = $summaryAr[$GLOBALS['hr']['performance_rate'][RATE_3]];
		}else{
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_3]] = 0;
		}
		
		if(!empty($summaryAr[$GLOBALS['hr']['performance_rate'][RATE_2]])){
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_2]] = $summaryAr[$GLOBALS['hr']['performance_rate'][RATE_2]];
		}else{
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_2]] = 0;
		}
		
		if(!empty($summaryAr[$GLOBALS['hr']['performance_rate'][RATE_1]])){
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_1]] = $summaryAr[$GLOBALS['hr']['performance_rate'][RATE_1]];
		}else{
			$newAr[$GLOBALS['hr']['performance_rate'][RATE_1]] = 0;
		}
		
		return $newAr;	
		
	}
	
	public static function computePerformanceAverage($performanceAr,$summary){
		$b = count($performanceAr);
		$a = 5;
		$c = $a * $b;	
		$sAr = explode(",",$summary);
		foreach($sAr as $key => $value){
			$v = explode(":",$value);				
			if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_5]) !== false){
				//5				
				$total += $v[1] * 5;
			}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_4]) !== false){
				//4
				$total += $v[1] * 4;
			}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_3]) !== false){
				//3
				$total += $v[1] * 3;
			}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_2]) !== false){
				//2
				$total += $v[1] * 2;
			}else if(strpos($value,$GLOBALS['hr']['performance_rate'][RATE_1]) !== false){
				//1
				$total += $v[1] * 1;
			}			
		}		
		return $percent = ($total / $c) * 100;
	}


}
?>