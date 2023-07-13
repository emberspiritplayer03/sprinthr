<?php 
	/*Author: Marvin Dungog
	**Updated: Aug 4,2010
	*this is for server validation
	*/
/*SAMPLE
	* @var array
		$form['firstname'] = 'marvindungog';
		$form['lastname'] = '200,00';
		$form['birthdate'] = '03-08-20100';
		$form['email'] = "m@y.com";
		
		$validate = new Form_validation($form);
		
		 $validate->requiredFields(array('firstname','lastname'));
		 $validate->noSpacesFormat(array('firstname') );
		 $validate->integerFormat(array('lastname'));
		 $validate->floatFormat(array('lastname'));
		 echo $validate->result(); //1=error 0=no error
		 echo $validate->viewError(); // if 1
	*/

class Form_validation extends Validation {
	public $fields = array();
	public $error_message = array();
	public $value = array();
	public $error = 0;
	
	public function __construct($fields=array()) {
		self::getFields($fields);
		self::getValue($fields);
	}
	
	/*
	@noSpacesFormat
	@input: array();
	@output: 
	*/
	public function noSpacesFormat($fields) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				$result = ereg("^[A-Za-z0-9]+$", $this->value[$val]);
			
				if ($result){
				
				
				}else{
					$this->error_message[] = ucfirst($val) . " is no spaces allowed";
					$this->error = 1;	
				}
			}
		}
	}
	
	
	/*
	@integerFormat
	@input: array();
	@output: 
	*/
	public function integerFormat($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				if(is_integer($this->value[$val]))
				{
				}else{
					$this->error_message[] = ucfirst($val) . " is invalid integer";
					$this->error = 1;	
				}
			}
		}
	}
	
	/*
	@numberFormat
	@input: array();
	@output: 
	*/
	public function numberFormat($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				if(is_numeric($this->value[$val]))
				{
				}else{
					$this->error_message[] = ucfirst($val) . " is invalid number";
					$this->error = 1;	
				}
			}
		}
	}
	
	/*
	@numberFormat
	@input: array();
	@output: 
	*/
	public function floatFormat($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				
				if(filter_var($this->value[$val], FILTER_VALIDATE_FLOAT) === false)
				{
					$this->error_message[] = ucfirst($val) . " is invalid number";
					$this->error = 1;	
				}
			}
		}
	}
	
	
	/*
	@dateFormat
	@input: array();
	@output: 
	*/
	public function dateFormat($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				if(strtotime($this->value[$val]) === -1 || $this->value[$val] == '' || strtotime($this->value[$val]) == '' )
				{
					$this->error_message[] = ucfirst($val) . " is invalid Date Format";
					$this->error = 1;	
				}
			}
		}
	}
	
	/*
	@emailFormat
	@input: array();
	@output: 
	*/
	public function emailFormat($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				$x = preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/",$this->value[$val]);
				if($x==0)
				{
					$this->error_message[] = ucfirst($val) . " is invalid Email Format";
					$this->error = 1;	
				}
			}
		}
					
		
	}
	
	/*
	@requiredFields
	@input: array();
	@output: 
	*/
	public function requiredFields($fields=array()) {
		self::checkIfFieldExist($fields);
		if(count($fields))
		{
			foreach($fields as $val){
				if($this->value[$val]=='')
				{
					$this->error_message[] = ucfirst($val) . " is required Field";
					$this->error = 1;					
				}
			}
		}
	}
	
	public function viewError() {
		return implode('<br>',$this->error_message);
	}
	
	public function result() {
		return $this->error;
	}
	
	public function getValue($fields){
		foreach($fields as $key =>$val) {
			$this->value[$key] = trim($val); // to prevent white spaces
		}
	}
	
	/*
	* Returns number of days. difference of two dates
	*
	* @param string $from 2009-12-25
	* @param string $to 2009-12-28	
	* @return int
	*/		 
	 public static function getDayDifference($from, $to) {
	 	list($from_year, $from_month, $from_day) = explode('-', $from);
	 	list($to_year, $to_month, $to_day) = explode('-', $to);
		$from = mktime(0, 0, 0, $from_month, $from_day, $from_year);
		$to = mktime(0, 0, 0, $to_month, $to_day, $to_year);
		$date_diff = $to - $from;
		return floor($date_diff/(60*60*24));
	 } 
	
	private function checkIfFieldExist($fields) {
	
		if(count($fields))
		{
			foreach($fields as $value) {
				if (in_array($value, $this->fields, true)) {		
				}else {
					echo ucfirst($value). " is unknown field ";
					exit;
				}			
			}
		}
		
	}
	
	private function getFields($fields) {
		self::instantiate($fields);
	}
	
	private function instantiate($fields) {
		foreach($fields as $key=>$value) {	
   		    $this->fields[] = $key;
		}
	}
	
	
	
	
}
?>