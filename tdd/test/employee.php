<?php
class TestEmployee extends UnitTestCase {
	
    function testViewEmployeeWithEntries()
	{
		$employee_id = 1;
		$employee = G_Employee_Finder::findById($employee_id);
		
		$this->assertTrue($employee,'should not be empty'); //  	Fail if $employees is false

		
		$this->assertTrue(is_object($employee),'this is not an object');	
		echo "<pre>";
		print_r($employee);
		echo "</pre>";
		
		echo $employee->getFirstname();
	}
	function testAddEmployee()
	{
	

	}
	
	function testUpdateEmployee()
	{
		$employee_id = 1;
		$employee = G_Employee_Finder::findById($employee_id);
		
		$this->assertTrue($employee,'should not be empty'); //  	Fail if $employees is false
		
		//update the employee

	}
}
