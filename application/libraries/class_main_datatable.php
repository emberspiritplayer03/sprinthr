<?php

class Main_Datatable extends Datatable_Properties {

	public function __construct($email) {
		
	}
	
	private function verifyColumnExistsInCustomFields($field) {		
		$custom_fields = $this->custom_field;
		$is_exists	   = 0;		
		foreach($custom_fields as $key => $value){		
			if($key == $field){
				$is_exists++;
			}
		}
		return $is_exists;
	}
	
	private function verifyColumnExistsInPredefinedSearch($field) {
		$predefine_search = $this->predefine_search;
		$is_exists = 0;
		foreach($predefine_search as $key => $value){		
			if($key == $field){
				$return_sql = "(" . $value . ")";
				$is_exists++;
				
			}
		}
		
		return $return_sql;
		
	}
	
	private function verifyColumnExistsInCastField($field) {
		if($this->cast_column){
			$cc = explode(",",$this->cast_column);
			if (in_array($field, $cc)) {
				$is_cast = true;
			}else{
				$is_cast = false;
			}
		}else{
			$is_cast = false;
		}
		return $is_cast;
	}
	
	private function reassignedColumnSort($field) {		
		$columns = $this->reassinged_sort_column;		
		foreach($columns as $key => $value){		
			if($key == $field){				
				$reassigned_column = $value;
			}
		}				
		return $reassigned_column;
	}
	
	private function parseColumnCustomLink($index, $data = array()) {			
		$index  = trim($index);
		$config = $this->custom_column_link;
		if(isset($config)){						
			if (array_key_exists($index, $config)) {					
					if($config[$index]['url'] != "" || ($config[$index]['param'] != "" OR $config[$index]['param'] != "none")){
						$url      = $config[$index]['url'];
						$haystack = explode("&",$config[$index]['param']);
						foreach($haystack as $h){
							switch($h){
								case array_key_exists($index, $haystack):
									$param[] = $index . "=" . $data[$index];
									break;
								case $h == "eid" && $data['id'] != "" :
								    $eid = Utilities::encrypt($data['id']);		
									$param[] = "eid=" . $eid;
									break;
								case $h == "hash" && $data['id'] != "" :
									$hash = Utilities::createHash($data['id']);
									$param[] = "hash=" . $hash;
									break;
							}
						}
						
						$param_string = implode("&",$param);
						
						if($param_string != ""){						
							$url 		 .= "?" . $param_string;
						}
						
						$custom_link  = '<a href=\"' . $url . '\"><span>' . $data[$index] . '</span></a>';										
						return $custom_link;
					}else{						
						return "<span>" . $data[$index] . "</span>";
					}
			}else{
				return "<span>" . $data[$index] . "</span>";
			}
		}else{
			return "<span>" . $data[$index] . "</span>";
		}
	}
	
	private function verifyColumnExistsInDoNotSortFields($field) {		
		if($this->do_not_sort_fields){
			$cc = explode(",",$this->do_not_sort_fields);			
			if (in_array($field, $cc)) {
				$is_exists = true;
			}else{
				$is_exists = false;
			}
		}else{
			$is_exists = false;
		}		
		return $is_exists;
	}
	
	public function constructSQLSearch() {
	
	}
	
	public function constructSQLGroupBy() {
		return $this->group_by;
	}
	
	public function constructSQLSort() {		
		$column_index = $_REQUEST['iSortCol_0'] - 1;				
		$cols 		  = $this->cols;		
		$do_not_sort  = self::verifyColumnExistsInDoNotSortFields($cols[$column_index]);
		if($do_not_sort){
			$column_index += 1;
		}
			
		$cast = self::verifyColumnExistsInCastField($cols[$column_index]);					
		if($cast){
			$sort_column  = "CAST(REPLACE(" . $cols[$column_index] . ",',','') as UNSIGNED)";
		}else{
			$sort_column  = $cols[$column_index];
		}
		
		$column_reassigned = self::reassignedColumnSort($cols[$column_index]);	
		if($column_reassigned){
			$sort_column  = $column_reassigned;
		}
		
		if(isset($_REQUEST['sSortDir_0'])){ 
			$ordery_by = strtoupper($_REQUEST['sSortDir_0']);
		}else{
			$order_by = $this->order; 
		}
		
		if($sort_column){
			$sort_sql = "ORDER BY " . $sort_column . " " . $ordery_by;
		}else{
			$sort_sql = "";
		}
		
		return $sort_sql;
	}
		
	public function constructDataTableRightTools() {
		
		$amt   = $this->pagination; 
		$cols  = $this->cols;	
		$ccols = $this->custom_column;		
		$custom_fields      = $this->custom_field;
		$join_fields 	    = $this->join_fields;
		$num_custom_fields  = $this->num_custom_column;
		$p_search           = $this->predefine_search; 
		 
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
			if($value != NULL && $key == 1){
			$count_lcols_array++;				
			}else{$count_rcols_array++;}
		 }		 
		 
		 
		 foreach ($custom_fields as $key => $value){			
			if($value != NULL){
			$count_cf2_array++;				
			}
		 }	
		 
		
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
					$is_exists = self::verifyColumnExistsInCustomFields($value);
					if($is_exists == 0){
						$is_predefined_search = self::verifyColumnExistsInPredefinedSearch($value);
						if($is_predefined_search){
							$search_sql .= $is_predefined_search;
						}else{
							$search_sql .= $value . " LIKE '%" . $stext . "%'";	
						}
						
						if($i+1 == $count_array){					
							$search_sql .= " ";
						}else{
							$search_sql .= " 	OR ";
						}
						
					}					
				}
				$i++;
			  }	
			  			  
			   $cfa_size = count($custom_fields);
			   foreach ($custom_fields as $key => $value){				 
				   $cf_e = explode(",",$value);
				   $ce = 0;
				   $ic = 0;
				 
				   $cfa_size = count($cf_e);	
					
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
		 
		  //Condition
		 if($this->condition){			
			if($psearch_string != '' || $search_sql != ''){
				$condition = " AND " . $this->condition;
			}else{				
				$condition = ' WHERE ' . $this->condition;
			}
		 }
		 
		 
		  $sort     = self::constructSQLSort();		
		  $group_by = self::constructSQLGroupBy();		 
		  
		  $sql  = $this->sql . " " . $search_sql . $psearch_string . $condition . " $group_by $sort LIMIT $start, $amt";		
		  $csql = $this->count_sql . " " . $search_sql . $psearch_string . $condition;
		  //$csql = $this->count_sql . " " . $search_sql . $psearch_string . $condition . " $group_by";
		  
		  $result 			  = Model::runSql($sql,true);	
		  $row  		      = Model::runSql($csql);
		  $row                = Model::fetchAssoc($row);
		  $total_after_filter = $row['c'];		  
		  
		  //CUSTOMIZED Condition if Count SQL returns NULL
		  if($total_after_filter == ''){
 				$total_after_filter = 0;
		  }

		  //start displaying records 
		  $dt   = '{"iTotalRecords":'.$total_records.', "iTotalDisplayRecords":' . $total_after_filter . ',"aaData":[';
		  $cols = $this->cols;
		  
			foreach($result as $r){ 
			if($f++) $dt .= ','; 			
			$dt .= '[';
			//echo key($r);			
			  $i = 0;	
			   
			   //print_r($ccols);		
				    //replace col string to field value
										
					foreach($ccols as $key1 => $value1){												
						$fv = $value1;																
						if($value1 != '' && $key1 == 1){	
						      foreach ($cols as $key => $value){
								if (strpos($r[$value],'.') !== false) {
									$f = explode(".",$r[$value]);
									$value = $f[1];
								}								  
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
							
							$fv = str_replace('id_obj,','id=' . $r['id'] . ',',$fv);
							$fv = str_replace('=btnid','=btn' . $id ,$fv);		
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
														
							if($fv == NULL){$fv = 'None';}
							
							$dt .= '"<span>' . $fv . '</span>"';	
						if($ia+1 == $count_ccols_array){	
							$dt .= ',';			
						}else{$dt .= ", ";}			
					 $ia++;
						}
						
					}											
				    ///////////////////////////////////////
			   
			    
				
			  foreach ($cols as $key => $value){				 
				if($value != ''){
					//custom code								
					if (strpos($value,'.') !== false) {
						$f = explode(".",$value);
						$value = $f[1];
					}	
					$r[$value] = preg_replace("/\s+/", " ", $r[$value]);					
					$r[$value] = str_replace("\"","",$r[$value]);
					$r[$value] = str_replace('"','-',$r[$value]);
					//$r[$value] = str_replace('.','',$r[$value]);
					$r[$value] = str_replace("(","",$r[$value]);
					$r[$value] = str_replace("(","",$r[$value]);					
					$r[$value] = str_replace(")","",$r[$value]);
					$r[$value] = str_replace("'","",$r[$value]);	
					
					if(($value == 'transaction_date') || ($value == 'created_on') || ($value == 'audit_date') || ($value == 'date_created')) {					$dt .= '"' . '<span>' . date('F d, Y g:i:s A',strtotime(trim($r[$value]))) . '</span>"';
					}elseif( ($value == "time_in") || ($value == "time_out") || ($value == "time_applied") ){
						$dt .= '"' . '<span>' . date('h:i A',strtotime(trim($r[$value]))) . '</span>"';
					}elseif($value == "incomplete_requirement"){
						if($r[$value] != ""){				
							$requirements = G_Employee_Requirements_Finder::findById($r['requirement_id']);
							if($requirements) {
								$requirements_arr = unserialize($requirements->getRequirements());
						        foreach($requirements_arr as $req_key => $req_val) {
						            if($req_val != "on") {
						            	$req_key = str_replace("_", " ", $req_key);
						            	$req_key = ucfirst($req_key);
						                $new_value_arr[] = $req_key;
						            }
						        }

						        $new_value = implode(", ",$new_value_arr);
						        $dt .= '"' . $new_value . '"';
							}else{
								$dt .= '"<span style=\"color:red\">No requirements set.</span>"';
							}
					        
						}else{
							$dt .= '"<span style=\"color:red\">No requirements set.</span>"';
						}
					}else{
						$new_value = self::parseColumnCustomLink($value,$r); 									
						$dt .= '"' . $new_value . '"';	
					}				
					if($i+1 == $count_array){	
						$dt .= ', ';			
					}else{$dt .= ", ";}			
					$i++;
				}
			  }			
			  
			  //print_r($ccols);		
				    //replace col string to field value
					
					foreach($ccols as $key1 => $value1){																			
						$fv = $value1;																						
						if($value1 != '' && $key1 > 1){	
						      foreach ($cols as $key => $value){								  							  			
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
							
							$hash =  Utilities::createHash($r['id']);
							$id   =  Utilities::encrypt($r['id']);
							
							#Unique obj id
							$fv = str_replace('=btnid','=' . $id ,$fv);		
							$fv = str_replace('=btnmid','=' . Utilities::encrypt($r['member_id']) ,$fv);		
												
							$fv = str_replace('id=\"id_obj\"','id=\"oid' . $r['id'] . '\"',$fv);							
							$fv = str_replace('=id','=' . $id . "&hash=" . $hash ,$fv);										
							$fv = str_replace('value=\"id\"','value=\"' . $r['id'] . '\"',$fv);
							$fv = str_replace('value=\"hid\"','value=\"' . $id . '\"',$fv);
							$fv = str_replace('(id)','(' . $r['id'] . ')',$fv);	
							$fv = str_replace("e_id", $id,$fv);
							$fv = str_replace("employee", Utilities::encrypt($r['employee_id']),$fv);
							$fv = str_replace('(id,','(' . $r['id'] . ',',$fv);	
							$fv = str_replace('id=id,','(' . 'id=' . $r['id'] . ',',$fv);
							$fv = str_replace("obj_name", $r['name'],$fv);							
							$fv = str_replace("obj_title", $r['title'],$fv);
							$fv = str_replace("obj_category", $r['category_name'],$fv);							
							
							if($fv == NULL){$fv = 'None';}
							
							//echo 'data1: ' . $count_rcols_array . ' ';
							//echo 'data2: ' . $ia . ' ';																					
							$dt .= '"<span>' . $fv . '</span>"';			
							if($ia+1 >= $count_rcols_array){	
								$dt .= ']';							
							}else{$dt .= "],[";}			
							
					 	$ia++;
						}else{$ia--;}						
					}									
				    ///////////////////////////////////////
			  		
			 } 
			$dt .= ']}';
			return $dt; 	
		
	}
		
}

?>