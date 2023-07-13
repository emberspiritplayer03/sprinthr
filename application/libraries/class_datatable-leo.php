<?php
/*Usage
$dt = new Datatable();
$dt->setPagination(2);
$dt->setStart(0);
$dt->setStartIndex($_SESSION['expenses']['selection_year'] . '-01-31');
$dt->setDbTable(EXPENSES);
$dt->setColumns('"",expenses_nature_id,date_spent, amount, payment_manner');
$dt->setOrder('ASC');
$dt->setStartIndex(0);
$dt->setSort(0);
$dt->setCustomColumn("<a href=\'javascript:void(0)\'>test</a>");
echo $dt->constructDataTable();*/

class Datatable {
	public $pagination;
	public $dt_sort;
	public $order;
	public $db_table;
	public $custom_condition;
	public $join_table;
	public $search;
	public $cols;
	public $condition;
	public $custom_column;
	public $start;
	public $num_custom_column;
	public $predefine_search;
	public $join_fields;
	protected $custom_fields;	
	
	public function __construct($email) {
		
	}
	
	//coma separated
	public function setColumns($arr) {
		$this->cols = explode(",", $arr);
	}
	
	public function setPreDefineSearch($arr = '') {
		$this->predefine_search = $arr;
	}
	
	public function setCustomCondition($condition) {
		$this->custom_condition = $condition;
	}
	
	public function setJoinFields($value) {
		$this->join_fields = $value;
	}
	
	public function setJoinTable($value) {
		$this->join_table = $value;
	}
	
	public function setCondition($value) {		
		$this->condition = $value;		
	}
	
	public function setStart($value = 0) {
		$this->start = $value;
	}
	
	
	public function setPagination($value) {
		$this->pagination = $value;
	}
	
	public function setSort($value = 0) {
		$this->dt_sort = $value;
	}
	
	public function setDbTable($value) {
		$this->db_table = $value;
	}
	
	public function setSearch($sql = '') {
		$this->search = $sql;
	}
	
	public function setStartIndex($value = 0) {
		$this->start = $value;
	}
	
	public function setNumCustomColumn($value = 0) {
		$this->num_custom_column = $value;
	}
	
	public function setCustomColumn($arr = '') {
		$this->custom_column = $arr;		
		//print_r($arr);	
	}
	
	//either ASC or DESC
	public function setOrder($value = 'ASC') {
		$this->order = $value;
	}
	
	//sql CONCAT
	public function setCustomField($arr = '') {
		$this->custom_field = $arr;	
		//print_r($arr);	
	}
	
	public function constructDataTable() {
		
		 $amt   = $this->pagination; 
		 $cols  = $this->cols;	
		 $ccols = $this->custom_column;		
		 $custom_fields     = $this->custom_field;
		 $join_fields 	    = $this->join_fields;
		 $num_custom_fields = $this->num_custom_column;
		 $p_search          = $this->predefine_search; 
		 
		 foreach ($cols as $key => $value){			
			if($value != NULL){
			$count_array++;				
			}
		 }	
		 
		 foreach ($ccols as $key => $value){			
			if($value != NULL){
			$count_ccols_array++;				
			}
		 }	
		 
		  foreach ($ccols as $key => $value){			
			if($value != NULL){
			$count_ccols_array++;				
			}
		 }	
		 
		 foreach ($custom_fields as $key => $value){			
			if($value != NULL){
			$count_cf2_array++;				
			}
		 }	
		 
		 //echo $count_cf2_array;
		 
		 
		 //Sql ConCat
		 foreach($custom_fields as $key2 => $value){			
			
			$sqlConcat .= "CONCAT("; 
			$count_cf_array = 0;
			$cf = 0;				
				$cf_arr = explode(",",$value);	
					 foreach ($cf_arr as $key => $value){			
						if($value != NULL){
						$count_cf_array++;				
						}
					 }						 
					 if(!empty($cf_arr)){						
						foreach($cf_arr as $key => $value){
						$sqlConcat .= $value;
							if($cf+1 == $count_cf_array){
							}else{
							$sqlConcat .= ' ,", ",';			
							}	
						$cf++;
						}						
					 }
					 
			$sqlConcat .= ") as " . $key2;
			if($cf2+1 == $count_cf2_array){
				
			}else{
			$sqlConcat .= ',';			
			}	
			
			$cf2++; 
			
			 $sqlConcat;
		 }
		 /////////////////////////////////////
		
		if($sqlConcat){$sqlConcat .= ',';}
		   
		if(isset($_REQUEST['iDisplayLength'])){ 
		  $amt = (int)$_REQUEST['iDisplayLength']; 
		  if($amt > 100 || $amt < 10) $amt; 
		} 		
		 $start = $this->start; 
			if(isset($_REQUEST['iDisplayStart'])){ 
			  $start=(int)$_REQUEST['iDisplayStart']; 
			  if($start<0) $start=0; 
			} 
				
		  //start sorting
		 $scol = $this->dt_sort; 
			if(isset($_REQUEST['iSortCol_0'])){ 
			$scol=(int)$_REQUEST['iSortCol_0'] - $num_custom_fields;
			//echo $scol;									  
			if($scol > 0){				
					$scol = abs($scol - ($count_ccols_array + $count_cf2_array));	
					//echo $scol;			
			}	
				
				if($scol_name == NULL){
					//echo $cols[$scol];					
					$scol_name = $cols[$_REQUEST['iSortCol_0'] - $num_custom_fields - $count_cf2_array];	
					/*foreach ($cols as $key => $value){	
																					
						if($c+2 == $scol){
											
							$scol_name = $cols[$scol + 2];					
						}					
						$c++;							
						//echo $c;	
					}	*/
				}
			
			 if($count_cf2_array > 0){				 
				foreach ($custom_fields as $key => $value){			
			    //echo $cc;
					if($cc == $scol){
						$scol_name = $key;					
					}
					$cc++;
				}	
				}
				
			 if($scol>3 || $scol<=0) $scol=0; 
			}
			
		 $sdir = $this->order; 
			if(isset($_REQUEST['sSortDir_0'])){ 
			  if($_REQUEST['sSortDir_0']=='asc'){
				  $sdir='ASC';
			  }else{$sdir='DESC';}
			} 
		 if($scol_name == NULL){			
		 	$scol_name = $cols[$scol]; 
		 }
		 //end sorting
		  
		 //start count existing records 
		 $rsql   	    = mysql_query('SELECT COUNT(id) as id from ' . $this->db_table); 
		 $r    		    = mysql_fetch_array($rsql);
		 $total_records = $r['id']; 
		 //end counting existing records
		 
		 //start counting records after filtering 
		 $search_sql = $search; 
		 if(isset($_REQUEST['sSearch']) && !empty($_REQUEST['sSearch'])){ 		 
		  $stext = addslashes($_REQUEST['sSearch']); 
		  $search_sql = 'WHERE ('; 
		  if(strlen($stext) > 0) {			  				  			
			  foreach ($cols as $key => $value){
				if($value != NULL){					
					$search_sql .= $value . " LIKE '%" . $stext . "%'";						
					if($i+1 == $count_array){					
					}else{$search_sql .= " 	OR ";}			
					$i++;					
				}
			  }	
			  // if(!empty($custom_fields)){$search_sql .= " 	OR ";}
			   $cfa_size = count($custom_fields);
			   foreach ($custom_fields as $key => $value){				 
				   $cf_e = explode(",",$value);
				   $ce = 0;
				   $ic = 0;
									  
				  
				   // foreach ($cf_e as $key => $value){			
				   //	if($value != NULL){
					//	$ce++;				
						//}
					 //}
					$cfa_size = count($cf_e);	
					//echo $cfa_size . ' ';
					if($cfa_size){
						$search_sql .= " 	OR ";
						foreach($cf_e as $key => $value){
							if($value != NULL){	
								if($ic+1 == $cfa_size){
									$search_sql .= $value . " LIKE '%" . $stext . "%'";	
								}else{
									$search_sql .= $value . " LIKE '%" . $stext . "%' OR ";	
								}							
								$ic++;					
							}
						}
					}
			  }	
			  	
			  if($search){$search_sql .= ' OR ' . $search;}			
			  $search_sql = $search_sql .= ')';
		  }					
		 } else {
			   if($search){
				   $search_sql = 'WHERE ' . $search; 				 
			   }			
		 }
		 				 
		 //Predefine Search
		 foreach($p_search as $key => $value){
			$psearch_string .= $key . " LIKE '%" . $value . "%' ";
		 }
		 
		  if(isset($_REQUEST['sSearch'])){ 		 
			  if(!empty($psearch_string)){
			  $psearch_string = ' ' . $psearch_string;
			    if($search_sql != ''){
					$psearch_string = " AND (" . $psearch_string . ")";
				}
				if($search_sql == ''){
					$search_sql = 'WHERE ';
				}
			  }
			  else{}
		  }		  
		  
		 /////////////////////////////
		 
		 
		  //Condition
		 if($this->condition){			
			if($psearch_string != '' || $search_sql != ''){
				$condition = " AND " . $this->condition;
			}else{				
				$condition = ' WHERE ' . $this->condition;
			}
		 }
		  
		  //Set Left Join
		  if($join_fields && $this->join_table){
				$jsql = $this->join_table . " ON " . $join_fields;			 
		  }else{
			 	$jsql = ""; 
		  }
		  
		  if($ccols != NULL){			
			 $fields 		= implode(",", $cols);
			 
				 if($sqlConcat){

					 $qsql= mysql_query("
						 SELECT  " . $this->db_table . ".id, $sqlConcat " . $fields . " " . $this->custom_condition . "
						 FROM " . $this->db_table . " $jsql $search_sql $psearch_string $condition 
						 ORDER BY $scol_name $sdir LIMIT $start, $amt"
					 );
					 
				 }else{		
				 		 
					$qsql= mysql_query("
						SELECT " . $this->db_table . ".id, " . $fields . " " . $this->custom_condition . "
						FROM " . $this->db_table . " $jsql $search_sql $psearch_string $condition 
						ORDER BY $scol_name $sdir LIMIT $start, $amt"
					);     
				 } 
			
		  }else{			 		  
				  $fields 		= implode(",", $cols);
				  if($sqlConcat){				
					$qsql= mysql_query("
						SELECT " . $this->db_table . ".id, $sqlConcat " . $fields . " " . $this->custom_condition . " 
						FROM " . $this->db_table . " $jsql $search_sql $psearch_string $condition 
						ORDER BY $scol_name $sdir LIMIT $start, $amt"
					); 	  
					
				  }else{
					 
					$qsql= mysql_query("
						SELECT " . $this->db_table . ".id, " . $fields . " " . $this->custom_condition . "
						FROM " . $this->db_table . " $jsql $search_sql $psearch_string $condition 
						ORDER BY $scol_name $sdir LIMIT $start, $amt"
					);
				  }
		  }	
		  
//		echo " SELECT  " . $this->db_table . ".id, $sqlConcat " . $fields . " " . $this->custom_condition . " FROM " . $this->db_table . " $jsql $search_sql $psearch_string $condition ORDER BY $scol_name $sdir LIMIT $start, $amt";
		  
			$total_after_filter = $total_records; 		 
			if($search_sql){ 	
			   $value =	'SELECT count('.$this->db_table.'.id) as c FROM ' . $this->db_table .' '.$jsql. ' ' .$search_sql. ' ' .$psearch_string. ' ' .$condition;  		
			   $rsql = mysql_query($value); 
			 }else{
			   $value = 'SELECT count('.$this->db_table.'.id) as c FROM ' . $this->db_table . ' '.$jsql. ' ' .$search_sql. ' ' .$psearch_string. ' ' .$condition;
			   $rsql = mysql_query($value); 		
			 }		
			 			 			 
			 $r    = mysql_fetch_array($rsql);
			 $total_after_filter = $r['c']; 
			 //end counting total records after filter 
			 
			 //start displaying records 
			  $dt = '{"iTotalRecords":'.$total_records.', "iTotalDisplayRecords":'.$total_after_filter.',"aaData":[';
		  
		
		  while ($q = mysql_fetch_array($qsql)) {
				$qs[] = $q;  
			}				
			
			foreach($qs as $r){ 
			//echo key($r);
			  if($f++) $dt .= ','; 			 
			  $dt .= '[';
			  $i = 0;	
			 
			  		//print_r($ccols);		
				    //replace col string to field value
										
					foreach($ccols as $key1 => $value1){												
						$fv = $value1;																
						if($value1 != ''){	
						      foreach ($cols as $key => $value){								  
							  	//echo $r[$value];
								if($value != ''){																		
									$fv = str_replace('(' . $value . ')','(' . $r[$value] . ')',$fv);
									$fv = str_replace('(\"' . $value . '\")','(\"' . $r[$value] . '\")',$fv);	
									$fv = str_replace('=' . $value,'=' . $r[$value] ,$fv);		
									$fv = str_replace('\"' . $value,'\"' . $r[$value] ,$fv);
									if($value != 'title'){
										$fv = str_replace($value,$r[$value],$fv);																	
									} 
					
								}
							  }	
							  //concat
							   foreach ($custom_fields as $key => $value){	
							   //echo $r[$key];								   
									if($value != NULL){											
									 $r[$key] = str_replace(",","", $r[$key]);
									 $r[$key] = str_replace('"',"", $r[$key]);										
										$fv = str_replace('\"' . $key . '\"','\"' . $r[$key] . '\"' ,$fv);																			
									}
							   }
							
							$hash =  Utilities::createHash($r['id']);
							$id   =  Utilities::encrypt($r['id']);
							
							$fv = str_replace('=id','=' . $id . "&hash=" . $hash ,$fv);										
							$fv = str_replace('value=\"id\"','value=\"' . $r['id'] . '\"',$fv);
							$fv = str_replace('value=\"hid\"','value=\"' . $id . '\"',$fv);
							$fv = str_replace('(id)','(' . $r['id'] . ')',$fv);	
							$fv = str_replace("e_id", $id,$fv);
							$fv = str_replace("employee", Utilities::encrypt($r['employee_id']),$fv);
							$fv = str_replace('(id,','(' . $r['id'] . ',',$fv);	
							$fv = str_replace('id=id,','(' . 'id=' . $r['id'] . ',',$fv);	
							
													
							
							$fv = str_replace("obj_name", $r['name'],$fv);
							$fv = str_replace("sent_name", $r['sent_name'],$fv);
							$fv = str_replace("obj_title", $r['title'],$fv);
							$fv = str_replace("obj_category", $r['category_name'],$fv);
							
							//custom : for g_employee_request_approvers, can be deleted if copied in other file
							$fv = str_replace("gera_id", Utilities::encrypt($r['gera_id']),$fv);		
							
							if($fv == NULL){$fv = 'None';}
							
							$dt .= '"<span style=\"color:#21729E\">' . $fv . '</span>"';	
						if($ia+1 == $count_ccols_array){	
							$dt .= ',';			
						}else{$dt .= ", ";}			
					 $ia++;
						}
						
					}											
				    ///////////////////////////////////////
			   
			   
			    foreach($custom_fields as $key2 => $value){	
					if($value != ''){												
						$r[$value] = str_replace('"',"",$r[$key2]);										
						$dt .= '"<span style=\"color:#21729E\">' . $r[$value] . '</span>"';	
						if($ib+1 == $count_cf2_array){	
							$dt .= ',';			
						}else{$dt .= ", ";}			
						$ib++;
					}
				}
  
			  foreach ($cols as $key => $value){	
				if($value != ''){
					//custom code
					if($value == 'is_approved' || $value == 'status'){
						if($r[$value] == '0'){ $r[$value] = 'Pending'; }
						else if($r[$value] == '1'){ $r[$value] = 'Approved'; }
						else if($r[$value] == '-1'){ $r[$value] = 'Disapproved'; }
					} else if($value == 'leave_comments') { $r[$value] = Tools::limitCharater($r[$value],60," "); }
					else if($value == 'date_start' || $value == 'date_end') {
						$r[$value] = ($r[$value] != "" ? date('M d, Y',strtotime($r[$value])) : '');
					} else if($value == 'time_in' || $value == 'time_out') {
						$r[$value] = Tools::convert24To12Hour($r[$value]);
					}
					$r[$value] = str_replace("\"","",$r[$value]);
					$r[$value] = str_replace('"','-',$r[$value]);
					//$r[$value] = str_replace('.','',$r[$value]);
					$r[$value] = str_replace("(","",$r[$value]);
					$r[$value] = str_replace("(","",$r[$value]);					
					$r[$value] = str_replace(")","",$r[$value]);
					$r[$value] = str_replace("'","",$r[$value]);
					if($value != 'date_start' && $value != 'date_end') {
						$r[$value] = str_replace(","," ",$r[$value]);	
					}
										
					$dt .= '"' . '<span style=\"color:#21729E\">' . trim($r[$value]) . '</span>"';	
					if($i+1 == $count_array){	
						$dt .= ']';			
					}else{$dt .= ", ";}			
					$i++;
				}
			  }					
			 } 
			$dt .= ']}';
			return $dt; 	
		
	}
}
?>