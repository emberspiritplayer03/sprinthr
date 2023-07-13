<?php
class G_Request_Approver extends Request_Approver {
	
	protected $approvers;
	protected $requestors;
	protected $employee_id;

	public function __construct() {
		
	}

	public function setEmployeeId( $value ) {
		$this->employee_id = (int) $value;
	}

	public function setApprovers( $values = array() ) {
		$this->approvers = $values;
	}

	public function setRequestors( $value ){
		$this->requestors = $value;
	}

	public function encryptIds( $data = array(), $index_name = '' ) {
		$data_index = "id";
		$new_data   = array();
		if( $index_name != '' ){
			$data_index = strtolower($index_name);
		}

		foreach( $data as $key => $value ){
			foreach($value as $subkey => $subvalue){
				if( strtolower($subkey) == $data_index ){
					$data[$key][$subkey] = Utilities::encrypt($subvalue);
				}
				$new_data[$key] = $data[$key];
			}
		}

		return $new_data;
	}

	/*
		Usage :
		$employee_id = 2;
		$gra = new G_Request_Approver();
		$gra->setEmployeeId($employee_id);
		$approvers = $gra->getEmployeeRequestApprovers();

		Utilities::displayArray($approvers); //Returns approvers array
	*/

	public function getEmployeeRequestApprovers(){
		$data = array();

		if( $this->employee_id > 0 ){
			$approvers = G_Request_Approver_Requestor_Helper::getRequestorApproversByRequestorIdAndType($this->employee_id, G_Request_Approver_Requestor::PREFIX_EMPLOYEE); //Check if employee has set approvers

			//If no approvers found by employee check 2nd priority, by department
			if( empty($approvers) ){				
				$fields        = array('department_company_structure_id');
				$employee_data = G_Employee_Helper::sqlEmployeeDetailsByEmployeeId($this->employee_id, $fields);
				if( !empty( $employee_data ) ){
					$department_id = $employee_data['department_company_structure_id'];
					$approvers     = G_Request_Approver_Requestor_Helper::getRequestorApproversByRequestorIdAndType($department_id, G_Request_Approver_Requestor::PREFIX_DEPARTMENT);	
				}
			}
			
			//If no approvers found by department check 3rd priority, by group
			/*if( empty($approvers) ){

			}*/

			$approvers = array_unique($approvers,SORT_REGULAR); //Remove duplicates

			//Construct approvers array structure						
			foreach( $approvers as $key => $value ){
				
				if( $level != $value['level'] ){
					$key_index = 0;
				}

				$level = $value['level'];				
				$data[$level][$key_index]['employee_id']   = $value['employee_id'];
				$data[$level][$key_index]['employee_name'] = $value['employee_name'];

				$key_index++;
			}
			
		}

		return $data;
	}

	public function bulkInsertRequestors( $requestors_data = array(), $request_approvers_id = 0 ){
		$return['is_success']      = false;
		$return['message']         = 'Cannot save record';
		$return['requestors_name'] = '';

		if( !empty($requestors_data) && $request_approvers_id > 0 ){
			//Create bulk insert data for requestors
			$requestors = explode(",", $requestors_data);
			foreach( $requestors as $value ){
				$requestor = explode(":", $value);
				$prefix = strtolower($requestor[1]);
				$pkid   = Utilities::decrypt($requestor[0]);

				switch ($prefix) {
					case G_Request_Approver_Requestor::PREFIX_EMPLOYEE :						
						$sql_fields     = array("id,CONCAT(lastname, ', ', firstname, ' ', middlename)AS title");
						$requestor_data = G_Employee_Helper::sqlEmployeeDetailsById($pkid, $sql_fields);
						break;
					case G_Request_Approver_Requestor::PREFIX_DEPARTMENT :						
						$sql_fields     = array("id,title");
						$requestor_data = G_Company_Structure_Helper::sqlDepartmentDetailsById($pkid, $sql_fields);
						break;
					case G_Request_Approver_Requestor::PREFIX_GROUP :						
						$sql_fields     = array("id,title");
						$requestor_data = G_Company_Structure_Helper::sqlGroupDetailsById($pkid, $sql_fields);
						break;
					default:																			
						break;
				}

				if( !empty($requestor_data) ){
					$requestors_names_array[]    = $requestor_data['title'];
					$bulk_data_requestor[] = "(" . Model::safeSql($request_approvers_id) . "," . Model::safeSql($requestor_data['id']) . "," . Model::safeSql($prefix)  . "," . Model::safeSql($requestor_data['title']) .  ")";
				}
			}

			//Bulk insert requestors data
			if( !empty($bulk_data_requestor) ){
				$fields = array("request_approvers_id,employee_department_group_id,employee_department_group,description");
				$gr     = new G_Request_Approver_Requestor();
				$is_success = $gr->bulkInsert($bulk_data_requestor, $fields);
				if( $is_success ){
					$return['requestors_name'] = implode(" / ", $requestors_names_array);
					$return['is_success'] = true;
					$return['message']    = 'Record Saved';
				}
			}
		}

		return $return;
	}

	public function bulkInsertApprovers( $approvers_data = array(), $request_approvers_id = 0 ){		
		$return['is_success']     = false;
		$return['message']        = 'Cannot save record';
		$return['approvers_name'] = '';

		if( !empty($approvers_data) && $request_approvers_id > 0 ){
			//Create bulk insert data for approvers
			foreach( $approvers_data as $key => $value ){
				$level = $key;
				$approvers_employee_ids = explode(",", $value);
				$sql_emp_fields = array("id,CONCAT(lastname, ', ', firstname, ' ', middlename)AS emp_name");
				foreach( $approvers_employee_ids as $eid ){										 
					$id = Utilities::decrypt($eid);
					$employee_data = G_Employee_Helper::sqlEmployeeDetailsById($id, $sql_emp_fields);
					if( !empty($employee_data) ){
						$approvers_names_array[]     = $employee_data['emp_name'];
						$bulk_data_approvers_level[] = "(" . Model::safeSql($request_approvers_id) . "," . Model::safeSql($employee_data['id']) . "," . Model::safeSql($employee_data['emp_name'])  . "," . Model::safeSql($level) .  ")";
					}
				}
			}

			//Bulk insert approvers data
			if( !empty($bulk_data_approvers_level) ){
				$fields = array("request_approvers_id,employee_id,employee_name,level");
				$gl     = new G_Request_Approver_Level();
				$is_success = $gl->bulkInsert($bulk_data_approvers_level, $fields);
				if( $is_success ){
					$return['approvers_name'] = implode(" / ",$approvers_names_array);
					$return['is_success']     = true;
					$return['message']        = 'Record Saved';
				}
			}
		}

		return $return;
	}

	/*
	Usage : 
		$title      = "Admin Requests";
		$approvers  = array(
			0 => "E23423safae234dsf,DSFDea32342q3123", //Encrypted ids
			1 => "31321sadfsaf,asd234asdf,safa234sa354sa"
		); 
		$requestors = "Ntmled332342342da:E,Dfedhbf43451gsdfg:D"; //Encrypted ids

		$gr = new G_Request_Approver();
		$gr->setTitle($title);
		$gr->setApprovers($approvers);
		$gr->setRequestors($requestors);
		$gr->setDateCreated($this->c_date);
		$data = $gr->addRequestApprovers(); //Returns array

	*/

	public function addRequestApprovers() {
		$return = array();
		$return['last_id']    = 0;
		$return['is_success'] = false;
		$return['message']    = 'Cannot save record';
		
		if( !empty($this->approvers) && !empty($this->requestors) && !empty($this->title) ){		
			$approvers_data  = $this->approvers;
			$requestors_data = $this->requestors;

			$duplicates = $this->filterDuplicatesApprovers( $this->approvers );
			if( $duplicates['is_with_duplicates'] ){		
				$return['message'] = $duplicates['message'];
			}else{
				$request_approvers_id = self::save(); //Create partial entry to generate header id	
				if( $request_approvers_id > 0 ){
					//Bulk insert approvers and requestors
					$data_approvers  = self::bulkInsertApprovers($approvers_data , $request_approvers_id);
					$data_requestors = self::bulkInsertRequestors($requestors_data, $request_approvers_id); 
					
					//Update request approvers data - add approvers and requestors name
					$this->id = $request_approvers_id;
					$this->approvers_name  = $data_approvers['approvers_name'];
					$this->requestors_name = $data_requestors['requestors_name']; 
					self::save();

					$return['last_id']    = $request_approvers_id;
 					$return['is_success'] = true;
					$return['message']    = 'Record saved';
				}
			}
		}

		return $return;
	}

	/*Returns duplicate approvers*/
	private function filterDuplicatesApprovers($approvers = array()) {		
		$return['is_with_duplicates'] = false;
		$return['message']            = '';
		if( !empty($approvers) ){
			$approvers  = $this->approvers;

			foreach($approvers as $value){						
				$arApprovers = array();
				$arApprovers = explode(",", $value);
				foreach($arApprovers as $approver){
					$newApprovers[] = $approver;	
				}			
			}
			
			$duplicates   = array_diff_key($newApprovers, array_unique(array_map('strtolower', $newApprovers)));
			$filterDuplicates = array_unique($duplicates);
			
			if( !empty($filterDuplicates) ){
				$return['is_with_duplicates'] = true;
				foreach( $filterDuplicates as $duplicate ){
					$id       = Utilities::decrypt($duplicate);
					$fields   = array("CONCAT(firstname, ' ', lastname)AS employee_name");
					$employee = G_Employee_Helper::sqlGetEmployeeDetailsById($id, $fields);
					if( !empty($employee) ){
						$duplicatesArray[] = $employee['employee_name'];
					}
				}

				$duplicatesStr = implode(", ", $duplicatesArray);
				$return['message'] = "Duplicate approvers found : <b>{$duplicatesStr}</b>"; 
			}
		}

		return $return;
	}

	/*
		Usage : 
		$id = 7;
		$approvers  = array(
			0 => "E23423safae234dsf,DSFDea32342q3123", //Encrypted ids
			1 => "31321sadfsaf,asd234asdf,safa234sa354sa"
		); 
		$title      = "Soft Eng Requests";
		$requestors = "2:E,7:d";

		$gr = new G_Request_Approver();
		$gr->setId($id);
		$gr->setTitle($title);
		$gr->setApprovers($approvers);
		$gr->setRequestors($requestors);
		$gr->setDateCreated($this->c_date);
		$data = $gr->updateRequestApprovers(); //Returns array
	*/

	public function updateRequestApprovers(){
		$return = array();
		$return['is_success'] = false;
		$return['message']    = 'Cannot update record';		
		if( $this->id > 0 && !empty($this->approvers) && !empty($this->requestors) && !empty($this->title) ){	
			$duplicates = $this->filterDuplicatesApprovers( $this->approvers );
			if( $duplicates['is_with_duplicates'] ){		
				$return['message'] = $duplicates['message'];
			}else{
				$is_id_exists = G_Request_Approver_Helper::sqlIsIdExists($this->id); //Check if id exists
				if( $is_id_exists ){
					$approvers_data  = $this->approvers;
					$requestors_data = $this->requestors;

					//Delete requestors and approvers data - will replace with new set			
					$gr = new G_Request_Approver_Requestor();
					$gr->setRequestApproversId($this->id);
					$gr->deleteAllByRequestApproversId();

					$gl = new G_Request_Approver_Level();
					$gl->setRequestApproversId($this->id);
					$gl->deleteAllByRequestApproversId();

					//Bulk insert approvers and requestors
					$data_approvers  = self::bulkInsertApprovers($approvers_data , $this->id);
					$data_requestors = self::bulkInsertRequestors($requestors_data, $this->id); 

					$this->approvers_name  = $data_approvers['approvers_name'];
					$this->requestors_name = $data_requestors['requestors_name']; 
					self::save();

					$return['is_success'] = true;
					$return['message']    = 'Record updated';
				}
			}
		}

		return $return;
	}

	public function getDataById() {
		$data = array();

		if( !empty($this->id) ){
			$fields     = array("id","title");
			$limit      = 1;
			$header     = G_Request_Approver_Helper::sqlRequestApproversById($this->id, $fields, $limit);

			$fields     = array("employee_id","employee_name","level");
			$order_by   = 'ORDER BY level ASC';
			$level      = G_Request_Approver_Level_Helper::sqlApproversLevelByRequestApproversId($this->id, $fields);

			$fields     = array("employee_department_group_id","employee_department_group","description");
			$requestors = G_Request_Approver_Requestor_Helper::sqlApproversLevelByRequestApproversId($this->id);

			$data['header'] = $header;
			$data['level']  = $level;
			$data['requestors'] = $requestors;
		}

		return $data;
	}

	public function getAllRequestApprovers( $fields = array(), $order_by = '', $limit = '' ) {
		$data = G_Request_Approver_Helper::sqlAllRequestApprovers($fields, $order_by, $limit);
		return $data;
	}

	public function getTotalRecords() {
		$total_records = G_Request_Approver_Helper::countTotalRecords();
		return $total_records;
	}
							
	public function save() {
		return G_Request_Approver_Manager::save($this);
	}

	public function deleteRequestApprovers() {		
		$return['is_success'] = false;
		$return['message']    = 'Data does not exists!';		

		if( !empty( $this->id ) ){
			$is_id_exists = G_Request_Approver_Helper::sqlIsIdExists($this->id);
			if( $is_id_exists ){
				$requestor = new G_Request_Approver_Requestor();
				$requestor->setRequestApproversId( $this->id );
				$requestor->deleteAllByRequestApproversId(); //Delete requestors tag to request approvers id

				$level = new G_Request_Approver_Level();
				$level->setRequestApproversId( $this->id );
				$level->deleteAllByRequestApproversId(); //Delete approvers tag to request approvers id

				$this->delete(); //Delete request approvers

				$return['is_success'] = true;
				$return['message']    = 'Record was successfully deleted';
			}
		}

		return $return;
	}



	public function delete() {
		G_Request_Approver_Manager::delete($this);
	}
}
?>