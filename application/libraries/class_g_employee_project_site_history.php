<?php
class G_Employee_Project_Site_History {

	//id ng table 
	public $id;

	//id ng emp
	public $employee_id;

	//id ng project
	public $project_id;

	public $site_id;

	//name  ng project - wala naman cgurong gamit
	public $name;


	public $start_date;

	public $end_date;

	public $employee_status;

	public $status_date;



	function __construct($id = '') {
		$this->id = $id;
	}

	public function setId($value) {
		$this->id = $value;
	}

	public function getId() {
		return $this->id;
	}

	public function setEmployeeId($value) {
		$this->employee_id = $value;
	}

	public function getEmployeeId() {
		return $this->employee_id;
	}

	

	public function setSiteId($value) {
		$this->site_id = $value;
	}

	public function getSiteId() {
		return $this->project_id;
	}

	public function setProjectId($value) {
		$this->project_id = $value;
	}

	public function getProjectId() {
		return $this->project_id;
	}

	public function setName($value) {
		$this->name = $value;
	}

	public function getName() {
		return $this->name;
	}


	public function setStartDate($value) {
		$this->start_date = $value;
	}

	public function getStartDate() {
		return $this->start_date;
	}

	public function setEndDate($value) {
		$this->end_date = $value;
	}

	public function getEndDate() {
		return $this->end_date;
	}

	public function setEmployeeStatus($value) {
		$this->employee_status = $value;
	}

	public function getEmployeeStatus() {
		return $this->employee_status;
	}

	public function setStatusDate($value) {
		$this->status_date = $value;
	}

	public function getStatusDate() {
		return $this->status_date;
	}

	public function setProject() {
		return G_Employee_Project_Site_History_Manager::setProjectHistory($this);
	}

	public function _load_employee_projects(){
		return G_Employee_Project_Site_History_Manager::getEmployeeProjectSite($this);
	}

	public function getCurrentProject(){
		return G_Employee_Project_Site_History_Manager::getCurrentProject($this);
	}

	public function getCurrentProjectById(){
		return G_Employee_Project_Site_History_Manager::getCurrentProjectById($this);
	}

	public function getProjectSites()
	{
		// code...
		return G_Employee_Project_Site_History_Manager::getProjectSites();
	}

	public function removeCurrentProject(){
		return G_Employee_Project_Site_History_Manager::removeCurrentProject($this);
	}

	public function remove_project()
	{
		return G_Employee_Project_Site_History_Manager::remove_project($this);
	}

	public function updateThisProjectSite()
	{
		return G_Employee_Project_Site_History_Manager::updateThisProjectSite($this);
	}


	public function hasCurrentProject()
	{
		return G_Employee_Project_Site_History_Manager::checkIfHasCurrentProject($this);
	}

	//==========================================

	public function getPresentProjectByEmployeeId($employee_id)
	{
		return G_Employee_Project_Site_History_Manager::getPresentProjectByEmployeeId($employee_id);
	}

	public function getProjectHistoryByEmployeeId($employee_id)
	{
		return G_Employee_Project_Site_History_Manager::getProjectHistoryByEmployeeId($employee_id);
	}

	public function updateProjectHistoryEndDate($history_id,$end_date) {
		return G_Employee_Project_Site_History_Manager::updateProjectHistoryEndDate($this,$history_id,$end_date);
	}


	//================================================

	public function updateHistoryProject($project_id , $his_id)
	{
		return G_Employee_Project_Site_History_Manager::updateHistoryProject($project_id , $his_id);
	}

	public function updateHistoryStartDate($start_date , $his_id)
	{
		return G_Employee_Project_Site_History_Manager::updateHistoryStartDate($start_date , $his_id);

	}

	public function updateHistoryEndDate($end_date , $his_id,$employee_status,$status_date)
	{
		return G_Employee_Project_Site_History_Manager::updateHistoryEndDate($end_date , $his_id,$employee_status,$status_date);

	}


	public function saveEmployeeProjectSite(){
		return G_Employee_Project_Site_History_Manager::saveNewEmployeeProjectSite($this);
	}


}

?>
