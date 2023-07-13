<?php
class New_Schedule_Controller extends Controller
{
	function __construct()
	{
		
	}

	function index()
	{
		$hr_schedule = hr_url('new_schedule');			
		header("Location:{$hr_schedule}");
	}
}
?>