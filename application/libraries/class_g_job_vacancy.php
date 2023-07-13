<?php
class G_Job_Vacancy {
	public $id;
	public $job_id;
	public $job_description;
	public $hiring_manager_id;	
	public $job_title;
	public $hiring_manager_name;
	public $publication_date;
	public $advertisement_end;
	public $is_active;
	
	const IS_ACTIVE    = 1;
	const ISNOT_ACTIVE = 0;
	const xmlFILENAME  = "job_vacancy.xml";
	const xmlPATH      = "../files/xml/job_vacancy/";

	//objects
	protected $gcs;
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setJobId($value) {
		$this->job_id = $value;
	}
	
	public function getJobId() {
		return $this->job_id;
	}

	public function setJobDescription($value) {
		$this->job_description = $value;
	}	

	public function getJobDescription() {
		return $this->job_description;
	}	
	
	public function setHiringManagerId($value) {
		$this->hiring_manager_id = $value;
	}
	
	public function getHiringManagerId() {
		return $this->hiring_manager_id;
	}
	
	public function setHiringManagerName($value) {
		$this->hiring_manager_name = $value;
	}
	
	public function getHiringManagerName() {
		return $this->hiring_manager_name;
	}
	
	public function setIsActive($value) {
		$this->is_active = $value;
	}
	
	public function getIsActive() {
		return $this->is_active;
	}
	
	public function setJobTitle($value) {
		$this->job_title = $value;
	}
	
	public function getJobTitle() {
		return $this->job_title;
	}
	
	public function setPublicationDate($value) {
		$this->publication_date = $value;
	}
	
	public function getPublicationDate() {
		return $this->publication_date;
	}
	
	public function setAdvertisementEnd($value) {
		$this->advertisement_end = $value;
	}
	
	public function getAdvertisementEnd() {
		return $this->advertisement_end;
	}
	
	public function createActiveJobVacancyXMLFile($path,$filename){
		$result = G_Job_Vacancy_Helper::createJobVacancyXMLFile($path,$filename);		
	}	
	
	public function readActiveJobVacancyXMLFile($xmlUrl,$filename){
		$data = Tools::convertXmlToArray($xmlUrl,$filename);
		return $data;
	}
	
	public function searchXMLJob($search_string,$data,$index_search) {				
		$results = $data;		
		foreach($results as $key => $value) {			
			foreach($value as $sub_key => $sValue){
		  		if(strpos(strtolower($sValue[$index_search]),strtolower($search_string)) !== false) {
		    		$new_results['job_vacant'][] = $sValue;
		  		}
		  	}
		}		
		return $new_results;
	}	
			
	public function save (G_Job_Vacancy $gcs) {
		return G_Job_Vacancy_Manager::save($this);
	}
	
	public function delete() {
		return G_Job_Vacancy_Manager::delete($this);
	}
	
	public function open() {
		return G_Job_Vacancy_Manager::open_job_vacancy($this);
	}
	
	public function close() {
		return G_Job_Vacancy_Manager::close_job_vacancy($this);
	}
}
?>