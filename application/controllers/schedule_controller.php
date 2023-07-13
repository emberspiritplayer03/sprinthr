<?php
class Schedule_Controller extends Controller
{
	function __construct()
	{
		
	}

	function index()
	{
		$hr_schedule = hr_url('schedule');			
		header("Location:{$hr_schedule}");
	}
}
?>