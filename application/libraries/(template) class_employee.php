<?php
class Employee extends User { //sample class name

	protected static $table_name = "g_user"; //please input your table
	public $id;
	public $employee_id;
	public $firstname;
	public $lastname;
	public $middlename;
	public $username;
	public $password;
	public $position_id;
	public $date_applied;
	public $date_hired;
	public $date_started;
	public $is_promoted;
	public $month_stayed;
	public $report_type;
	public $status;
	public $quota_lines;
	public $quota_quality;
	public $editor_assigned_account_id;
	public $landline_no;
	public $mobile_no;
	public $email_address;
	public $summit_email_address;
	public $home_address;
	public $sex;
	public $age;
	public $birthdate;
	public $civil_status;
	public $emergency_contact;
	public $emergency_contact_number;
	public $emergency_contact_address;
	public $address;
	public $nickname;
	public $account_no;
	public $sss_no;
	public $tin_no;
	public $hmdf_no;
	public $phic_no;
	public $philhealth_no;
	public $pagibig_no;
	public $s_basic_pay;
	public $s_allowance;
	public $s_incentives;
	public $s_benefits;
	public $s_others;
	public $s_hmo;

	public $fullname;
	public $branch_id;
	public $department_id;
	public $department_name;
	public $company_id;
	public $branch_name;
	public $status_name;
	public $position_name;
	public $photo;
	public $requirements;
	
	
	public function __construct($id) {
		$this->id = $id;
		
		self::getObjectVariable();
	}
	//HERE ARE THE LIST OF PUBLIC METHODS
	public function getDetails() {
		return get_object_vars($this);
	}
	
	public function updateDetails($form) {
		$model = new Model;
		$model->open(self::$table_name);
		$model->update($form,"id=".Model::safeSql($this->id));
	}
	
	public function delete() {
		$sql = "DELETE FROM ". self::$table_name . " WHERE id='".Model::safeSql($this->id)."' ";
		Model::runSql($sql,true);
	}
	
	// note: add field is_archive on the table $table_name
	public function archive() {
		$sql = "UPDATE ". self::$table_name . " SET is_archive=1 WHERE id='".Model::safeSql($this->id)."' ";
		Model::runSql($sql,true);
	}
	// note: add field is_archive on the table $table_name
	public function unarchive()	{
		$sql = "UPDATE ". self::$table_name . " SET is_archive=0 WHERE id='".Model::safeSql($this->id)."' ";
		Model::runSql($sql,true);
	}
	
	public function getFullName() {
		return $this->firstname . ' ' . $this->lastname;
	}
	
	public function getDepartment() {
		$sql = "SELECT * FROM s_department_user WHERE user_id=". Model::safeSql($this->id);
		$record = Model::runSql($sql,true);
		
		if(count($record)) {
			$record = $record;
		}else {
			$record = 0;
		}
		return $record;
	}
	
	public function getYearsOfStay() {
	  return Date::get_day_diff($this->date_hired,date("Y-m-d"));
	}
	
	private function getCompanyId() {
		$sql = "SELECT b.company_id FROM s_branch b WHERE b.id=".Model::safeSql($this->branch_id)."";
		$record = Model::runSql($sql,true);
		if(count($record)) {
			$record = $record[0]['company_id'];
		}else
		{
			$record = 0;
		}
		return $record;
	}
	

	
	//HERE ARE THE LIST OF STATIC METHODS
	
	
	/*SAMPLE
	* @var array
	* 	$config['name'] = "test";
		$config['created_on'] = Tool::getDateTimeNow(); // strftime("%Y-%m-%d %H:%M:%S", time());
		$config['address'] = 'address';
		$config['phone_number'] = 'test';
		$config['fax_number'] = 'test';
		$config['logo'] = 'logo';
 	* Company::insert($config);
	*/
	
	public static function insert($config) {
		$model = new Model;
		$model->open(self::$table_name);
		$model->insert($config);
		return mysql_insert_id();
	}
	
	public static function findById($id=0) {
		$id = (int) $id;
		$result_array = self::findBySql("SELECT * FROM ".self::$table_name." WHERE id={$id} LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function findAll($sql='',$fieldname,$search,$limit='',$sort,$dir, $branch_id='') {

		$search = ($fieldname  !='') ? "WHERE ". $fieldname." like '". $search ."%'  ": '  ';

		$limit = ($limit!='')? $limit : '';
		$order_by = ($sort != '') ? $sort . ' ' . $dir  :  'u.id';
		if($sql=='') {
			$sql = "SELECT u.id as id FROM ".self::$table_name." u ".$search."  ORDER BY ". $order_by ." " .$limit;	
		}
		//echo $sql;
		$raw = mysql_query($sql);
		
		while($record =	mysql_fetch_assoc($raw)) {
			$employee = new Employee($record['id']);
			$rec[] = $employee->getDetails();
		}
		
		return $rec;
	}
	
	public static function findBySql($sql='') {

		$record_set = Model::runSql($sql,true);
		return $record_set;
	}
	
	private function getBranchName($branch_id) {
		$branch = new Branch($branch_id);
		
		return $branch->name;
	}
	
	//HERE ARE LIST OF PRIVATE METHODS
	private function getObjectVariable() {
		$sql = "SELECT * FROM ". self::$table_name . " WHERE id='".Model::safeSql($this->id)."' ";
		$records = Model::runSql($sql,true);
		
		self::instantiate($records[0]);		
	}
	
	
	private function getEmployeeStatus($employee_status='') {

		$status = $GLOBALS['employment_status'][$employee_status];
		if($status=='') {
			$status = 'Pending';	
		}
		return $status;
	}
	
	private function getDepartmentName() {
			$record =  self::findBySql("SELECT department_head_id FROM s_department_user WHERE user_id=".$this->id);
		foreach($record as $key=>$value) {
			
			$department = new Department($value['department_head_id']);
			$rec = $department->department_name;

		}
		return $rec;
	}
	
	private function instantiate($record) {
		
		foreach($record as $attribute=>$value) {
			
		  if(self::has_attribute($attribute)) {
			   $this->$attribute = $value;
		  }
		}
		
			
		return $object;
	}
	
	private function has_attribute($attribute) {

	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	 $object_vars = get_object_vars($this);
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
	  
	}
		
}
?>