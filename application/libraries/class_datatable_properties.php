<?php
class Datatable_Properties {
	
	protected $pagination;
	protected $dt_sort;
	protected $order;
	protected $db_table;
	protected $custom_condition;
	protected $cast_column;	
	protected $reassinged_sort_column;
	protected $do_not_sort_fields;
	protected $join_table;
	protected $search;
	protected $cols;
	protected $condition;
	protected $custom_column;
	protected $start;
	protected $sql;
	protected $count_sql;
	protected $num_custom_column;
	protected $group_by;
	protected $predefine_search;
	protected $join_fields;
	protected $custom_fields;	
	protected $custom_column_link;
	
	public function __construct($email) {
		
	}
	
	//coma separated
	public function setColumns($arr = array()) {		
		$this->cols = explode(",", $arr);
	}
	
	public function setCustomColumnLink($config = array()){
		$this->custom_column_link = $config;
	}
	
	public function setSQL($value) {
		$this->sql = $value;
	}
	
	public function setCountSQL($value) {
		$this->count_sql = $value;
	}
	
	public function setSQLGroupBy($value) {
		$this->group_by = $value;
	}
	
	public function setPreDefineSearch($arr = '') {
		$this->predefine_search = $arr;
	}
	
	public function setCustomCondition($condition) {
		$this->custom_condition = $condition;
	}
	
	public function setReassignedSortColumn($arr = ''){
		$this->reassinged_sort_column = $arr;
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
	
	//set cast column
	public function setCastColumn($value) {
		$this->cast_column = $value;
	}
	
	//set do not sort fields
	public function setDoNotSortFields($value) {
		$this->do_not_sort_fields = $value;
	}
	
	//sql CONCAT
	public function setCustomField($arr = '') {
		$this->custom_field = $arr;	
		//print_r($arr);	
	}
	
	
}
?>