<?php
class Query_Builder {
	protected $qry_options = array();
	protected $logical_operator = 'OR';
	protected $table_prefix;
	public function __construct() {
		
	}

	public function setQueryOptions( $options = array() ){
		$this->qry_options = $options;
		return $this;
	}

	public function usePrefix( $prefix = '' ) {
		$this->table_prefix = $prefix;
		return $this;
	}

	public function setLogicalOperator( $operator = '' ) {
		$this->logical_operator = $operator;
		return $this;
	}

	public function buildSQLQuery() {
		$query_string = '';
		if( !empty($this->qry_options) ){
			$qry_fields  = $this->qry_options['qry_fields'];
			$qry_options = $this->qry_options['qry_options'];
			$qry_values  = $this->qry_options['qry_values'];
			
			foreach( $qry_fields as $key => $value ){
				if( !empty($this->table_prefix) ){
					$sql_field = trim($this->table_prefix) . "." . trim($value);
				}else{
					$sql_field  = trim($value);	
				}
				$sql_value  = trim($qry_values[$key]);
				$sql_option = trim($qry_options[$key]);
				
				if( $sql_value != "" && $sql_field != "" && $sql_option != "" ){ 
					switch ($sql_option) {
						case '=':	
							$sql_value      = Model::safeSql(trim($qry_values[$key]));			
							$a_sql_values[] = "{$sql_field} {$sql_option} {$sql_value}";		
							break;
						case 'LIKE':
							$sql_value      = trim($qry_values[$key]);			
							$a_sql_values[] = "{$sql_field} {$sql_option} '%{$sql_value}%'";		
							break;
						default:
							$sql_value      = Model::safeSql(trim($qry_values[$key]));			
							$a_sql_values[] = "{$sql_field} {$sql_option} {$sql_value}";		
							break;
					}
				}
			}

			if( count($a_sql_values) > 0 ){
				$operator = $this->logical_operator;
				$query_string = implode(" {$operator} ", $a_sql_values);
			}
		}

		return $query_string;
	}
	
	public function payrollRegisterQueryStructure() {
		$a_payroll_register = array(
			G_EMPLOYEE_PAYSLIP => array(
				'basic_pay' => 'Basic Pay',
				'gross_pay' => 'Gross Pay',
				'total_earnings' => 'Earnings',
				'total_deductions' => 'Deductions',
				'net_pay' => 'Net Pay',
				'month_13th' => '13th Month',
				'sss' => 'SSS',
				'pagibig' => 'Pagibig',
				'philhealth' => 'Philhealth',
				'tardiness_amount' => 'Tardiness Amount'
			)
		);

		return $a_payroll_register;
	}
	
	public function queryOptions() {
		$a_qry_options = array('=','>','>=','<','<=','LIKE');
		return $a_qry_options;
	}
}
?>