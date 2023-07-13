<?php
class G_Holiday extends Holiday {
	
	public function __construct() {
		
	}
	
	public function save() {
		return G_Holiday_Manager::save($this);
	}

	/*
		$h = G_Holiday_Finder::findById(4);
		$b = G_Company_Branch_Finder::findById(31);
		$h->addCompanyBranch($b);	
	*/
	public function addCompanyBranch(G_Company_Branch $b) {
		return G_Holiday_Manager::addCompanyBranch($b, $this);
	}
	
	public function removeCompanyBranch(G_Company_Branch $b) {
		return G_Holiday_Manager::removeCompanyBranch($b, $this);
	}	

	public function delete(){
		if( !empty($this->id) ){
			return G_Holiday_Manager::delete($this);
		}
	}

	public function isDateHoliday( $date ) {
		$is_holiday = false;
		$month = date("n",strtotime($date));
		$day   = date("j",strtotime($date));
		$year  = date("Y",strtotime($date));
		$is_holiday = G_Holiday_Helper::isDateHoliday( $month, $day, $year );
		return $is_holiday;
	}
}
?>