<?php

/**
 * WGFramework Model Class
 *
 * Use database table and get, update, add, delete records
 *
 * @package		WGFramework
 * @author		Webgroundz::zyklone
 * @category	System
 */

class Model
{
	/**
	 * The table to be used
	 *
	 * @var string
	 */
	protected $table_name;

	/**
	 * Class suffix ex: user_model
	 *
	 * @var string
	 */
	protected $class_suffix = "_model";
	
	/**
	 * Controller class Object
	 *
	 */
	protected $controller_object;

	/**
	 * Constructor
	 *
	 * @param object $controller_object Class Controller object
	 */
	function __construct($controller_object = '')
	{
		$this->controller_object = $controller_object;
	}

	/**
	 * Include a model file and initialize. Model file is in /models/my_model.php
	 *
	 * @param string $model_name
	 * @param string $object_name
	 * @return void
	 */
	public function open($model_name, $object_name = null)
	{
		if (is_array($model_name))
		{
			$model_name = implode(',', $model_name);
		}
		$model_name_complete = strtolower("{$model_name}" . $this->class_suffix);
		$model_file = APP_PATH . "models/$model_name_complete" . EXT;
		$class_name = 'Model';
		if (file_exists($model_file))
		{
			include_once $model_file;
			if (class_exists($model_name_complete))
			{
				$class_name = $model_name_complete;
			}
			else
			{
				die("Class {$model_name_complete} does not exist");
			}
		}		
		$instance = $model_name;

		// if $object_name is defined use it as object
		if ($object_name != null)
		{
			$instance = $object_name;
		}

		$this->controller_object->$instance = new $class_name();
		$this->controller_object->$instance->table_name = $model_name;
		$this->table_name = $model_name;
	}

	
	/**
	 * Get the records from the table
	 *
	 * Sample Usage:
	 * 
	 * 	$config = array
	 *	(
	 *		'condition' => "username='tolits'",
	 *		'fields'	=> "username, password",
	 *		'group_by'  => "date_published",
	 *		'order_by'	=> "id DESC",
	 *		'limit' 	=> 5,
	 * 		'fetch'		=> 'object'
	 *	);
	 *	foreach($this->Login->getData($config) as $data)
	 *	{
	 *		echo $data['username'];
	 * 	 	echo $data['password'];
	 *	}
	 *
	 * @param array $args The SQL commands
	 * @return array
	 */
	public function getData($args = '', $print_sql = false)
	{
		if ($args['limit'] != null) { $limit = " LIMIT " . $args['limit']; }	
		if ($args['condition'] != null) { $condition = " WHERE " . $args['condition']; }

		// fields
		if ($args['fields'] == null)
		{
			$table_fields = $this->getTableFields($this->table_name);
			$fields = implode(', ', $table_fields);
		}
		else if (strstr($args['fields'], '*'))
		{
			$table_fields = $this->getTableFields($this->table_name);
			$fields = implode(', ', $table_fields);
			$fields = str_replace('*', $fields, $args['fields']);
		}
		else
		{
			$fields = $args['fields'];
		}

		if ($args['group_by'] != null) { $group_by = " GROUP BY " . $args['group_by']; }			
		if ($args['order_by'] != null) { $order_by = " ORDER BY " . $args['order_by']; }
		$fetch = 'mysql_fetch_assoc';
		if ($args['fetch'] != null) { $fetch = "mysql_fetch_{$args['fetch']}"; }
		if ($print_sql)
		{
			echo "
				SELECT " . $fields . "
				FROM " . $this->table_name . "
				" . $condition . "
				" . $group_by . "
				" . $order_by . "
				" . $limit . "
			";
		}

		$start = microtime(true);				
		$result = mysql_query
		("
			SELECT " . $fields . "
			FROM " . $this->table_name . "
			" . $condition . "
			" . $group_by . "
			" . $order_by . "
			" . $limit . "
		");

		$ctr = 0;
		while ($row = $fetch($result))
		{
			$data[$this->table_name][] = $row;
			$ctr++;
		}
		//return $data;
  		mysql_free_result($result);
		return $data[$this->table_name];
	}

	public function getResult($args = '')
	{
		if ($args['limit'] != null) { $limit = " LIMIT " . $args['limit']; }	
		if ($args['condition'] != null) { $condition = " WHERE " . $args['condition']; }

		// fields
		if ($args['fields'] == null)
		{
			$table_fields = $this->getTableFields($this->table_name);
			$fields = implode(', ', $table_fields);
		}
		else if (strstr($args['fields'], '*'))
		{
			$table_fields = $this->getTableFields($this->table_name);
			$fields = implode(', ', $table_fields);
			$fields = str_replace('*', $fields, $args['fields']);
		}
		else
		{
			$fields = $args['fields'];
		}

		if ($args['group_by'] != null) { $group_by = " GROUP BY " . $args['group_by']; }			
		if ($args['order_by'] != null) { $order_by = " ORDER BY " . $args['order_by']; }
		$fetch = 'mysql_fetch_assoc';
		if ($args['fetch'] != null) { $fetch = "mysql_fetch_{$args['fetch']}"; }
		$start = microtime(true);		 
		$result = mysql_query
		("
			SELECT " . $fields . "
			FROM " . $this->table_name . "
			" . $condition . "
			" . $group_by . "
			" . $order_by . "
			" . $limit . "
		");
		return $result;	
	}

	/**
	 * Add record to database table
	 *
	 * @param array $data_array
	 * @param array $config
	 * @return int New inserted id
	 */
	public function insert($data_array, $config = array('multi_value_glue' => ', '))
	{
		// Remove $_POST['Submit'] or $_GET['Submit'] from array
		unset($data_array['Submit']);
		unset($data_array['submit']);
		$total_fields = count($data_array);
		$counter = 1;
		if (!is_array($data_array)) // is the data not array?
		{
			return false;
		}

		foreach($data_array as $field_name => $value)
		{
			if (is_array($value)) // is value array? (from checkbox)
			{
				// combine the array
				$value = implode($config['multi_value_glue'], $value);
			}

			$value = $value;
			$fields .= "$field_name";
			$values .= Model::safeSql($value);
			if ($counter != $total_fields) // is not end of the array?
			{
				// Put comma at the end
				$fields .= ", ";
				$values .= ", ";
			}
			$counter++;
		}

		@mysql_query
		("
			INSERT INTO ". $this->table_name ."
			(
				" . $fields . "
			)
			VALUES
			(
				" . $values . "
			)
		") or die(mysql_error());
		
		return mysql_insert_id();
	}

	/**
	 * Update record in database table
	 *
	 * @param array $data_array
	 * @param string $condition
	 * @param array $config
	 */
	public function update($data_array, $condition = '', $config = array('multi_value_glue' => ', '))
	{
		// Remove $_POST['Submit'] or $_GET['Submit'] from array
		unset($data_array['Submit']);
		unset($data_array['submit']);
		$total_fields = count($data_array);
		$counter = 1;
		$values = '';

		foreach($data_array AS $field_name => $value)
		{
			if (is_array($value)) // is value array? (from checkbox)
			{
				// combine the array
				$value = implode($config['multi_value_glue'], $value);
			}
			$value = $value;

			if($counter != $total_fields)
			{
				$values .= "$field_name = " . Model::safeSql($value) .", ";
			}
			else
			{
				$values .= "$field_name = " . Model::safeSql($value);
			}
			$counter++;
		}

		@mysql_query
		("
			UPDATE " . $this->table_name . "
			SET " . $values . "
			WHERE " . $condition . "
		");

		$affected = mysql_affected_rows();
		return ($affected <= 0) ? false : true ;
	}
	
	public function delete($condition = "")
	{
		@mysql_query
		("
			DELETE FROM " . $this->table_name . "
			WHERE " . $condition . "
		") or die(mysql_error());
	}

	/**
	 * Get the table's fields
	 *
	 * @param string $table_name
	 * @return array
	 */
	private function getTableFields($table_name)
	{
		$tables = explode(',', $table_name);
		foreach ($tables as $table)
		{	
			$result = mysql_query("SHOW COLUMNS FROM $table") or die(mysql_error());
			if (mysql_num_rows($result) > 0) 
			{
	    		while ($row = mysql_fetch_assoc($result)) 
	    		{
	        		$fields[] = "{$table}.{$row['Field']}";
	    		}
			}
		}
		return $fields;
	}

	/**
	 * Get the class name of the child
	 *
	 * @return string
	 */
	private function getClassName()
	{
		return get_class($this);
	}

	/**
	 * Counts the total record in the table
	 *
	 * @param string $condition
	 * @return int
	 */
	public function count($condition = null, $show_sql = false)
	{
		if ($condition != null)
		{
			$condition = " WHERE $condition";
		}

		if ($show_sql) {
			echo "
			SELECT COUNT(*) AS total
			FROM " . $this->table_name . "
			" . $condition;
		}	
		$result = mysql_query
		("
			SELECT COUNT(*) AS total
			FROM " . $this->table_name . "
			" . $condition . "
		");
		$row = @mysql_fetch_array($result) or die(mysql_error());
		mysql_free_result($result);
		return (int) $row['total'];
	}

	public function __call($method, $args)
	{
		//$this->login = new login_model();
		//echo $method;
		//$args[0] = 'login';
		//return call_user_func_array( array($this, "open" ), $args);
	}

	/**
	 * Filters/validates the value against sql injection
	 *
	 * @param mixed $value From outside world input
	 * @param bool $detect_numeric If false add single quote to the value
	 * @param bool $allow_wildcards Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
	 * @return mixed Safer value
	 */
	static function safeSql($value, $detect_numeric = true, $allow_wildcards = true)
	{
		// Reverse magic_quotes_gpc/magic_quotes_sybase effects on those vars if ON.
		if (get_magic_quotes_gpc()) {
			if(ini_get('magic_quotes_sybase')) {
			  $value = str_replace("''", "'", $value);      
			} else {
			  $value = stripslashes($value);
			}
		}
		
		//Escape wildcards for SQL injection protection on LIKE, GRANT, and REVOKE commands.
		if (!$allow_wildcards) {
			$value = str_replace('%','\%',$value);
			$value = str_replace('_','\_',$value);
		}
		
		// Quote if $value is a string and detection enabled.
		if ($detect_numeric) {
			if (!is_numeric($value)) {
			  return "'" . mysql_real_escape_string($value) . "'";
			}
		}
		return mysql_real_escape_string($value);
	}
	
	/**
	 * Runs as mysql_query
	 *
	 * @param string $sql Sql command
	 * @param bool $return_data If true returns the data
	 * @return mixed
	 */	
	static function runSql($sql, $return_data = false) {
		if ($return_data) {
			$result = mysql_query($sql);
			
			while ($row = mysql_fetch_assoc($result)) {
				$data[] = $row;
			}
			mysql_free_result($result);
			return $data;
		} else {
			return mysql_query($sql);
		}
	}
	
	static function fetchAssoc($result) {
		return mysql_fetch_assoc($result);
	}
	
	static function freeResult($result) {
		mysql_free_result($result);
	}

    static function insert_id() {
        return mysql_insert_id();
    }
}

?>