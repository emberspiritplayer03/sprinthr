<?php //UPDATED February 3,2011 | Marvin
class Class_name { // class name

	protected static $table_name = "table"; //please input your table
	public $id;
	public $name;
	public $address;
	public $phone_number;
	public $fax_number;
	public $logo;
	
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
	
	public static function findAll($sql='',$fieldname,$search,$limit='',$sort,$dir) {

		$search = ($fieldname  !='') ? "WHERE  ". $fieldname." like '". $search ."%'": '';

		$limit = ($limit!='')? ' LIMIT '.$limit : '';
		$order_by = ($sort != '') ? $sort . ' ' . $dir  :  'id';
		if($sql=='') {
			$sql = "SELECT id FROM ".self::$table_name." ".$search." ORDER BY ". $order_by ." " .$limit;	
		}
		
		$raw = mysql_query($sql)or die(mysql_error());;
		
		while($record =	mysql_fetch_assoc($raw)) {
			$class_name = new self($record['id']);
			$rec[] = $class_name->getDetails();
		}
		
		return $rec;
	}
	
	public static function findBySql($sql='') {

		$record_set = Model::runSql($sql,true);
		return $record_set;
	}
	
	public static function getTotalRecords() {
		$sql = "select count(id) as num_rows FROM ". self::$table_name ;
		$data2 = Model::runSql($sql,true);
		return $data2[0]['num_rows'];
	}
	
	//HERE ARE LIST OF PRIVATE METHODS
	public function getObjectVariable() {
		$sql = "SELECT * FROM ". self::$table_name . " WHERE id='".Model::safeSql($this->id)."' ";
		$records = Model::runSql($sql,true);
		
		self::instantiate($records[0]);		
	}
	
	public function instantiate($record) {
		// Could check that $record exists and is an array
		
		// Simple, long-form approach:
		// $this->id 				= $record['id'];
		// $this->username 	= $record['username'];
		// $this->password 	= $record['password'];
		// $this->first_name = $record['first_name'];
		// $this->last_name 	= $record['last_name'];
		
		// More dynamic, short-form approach:
		
		foreach($record as $attribute=>$value) {
			
		  if(self::has_attribute($attribute)) {
			   $this->$attribute = htmlentities($value);
		  }
		}
		return $object;
	}
	
	public function has_attribute($attribute) {

	  // get_object_vars returns an associative array with all attributes 
	  // (incl. private ones!) as the keys and their current values as the value
	 $object_vars = get_object_vars($this);
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $object_vars);
	  
	}
		
}
?>