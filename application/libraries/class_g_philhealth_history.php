<?php
class G_Philhealth_History {
	protected $id;
	protected $company_structure_id;
	protected $salary_from;
	protected $salary_to;
	protected $multiplier_employee;
    protected $multiplier_employer;
	protected $is_fixed;
    protected $date_end;
    
	const YES = 'Yes';
	const NO  = 'No';
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}

	public function getId() {
		return $this->id;	
	}	

    public function setCompanyStructureId($value) {
        $this->company_structure_id = $value;
    }

    public function getCompanyStructureId() {
        return $this->company_structure_id;
    }

    public function setSalaryFrom($value) {
        $this->salary_from = $value;
    }

    public function getSalaryFrom() {
        return $this->salary_from;
    }

    public function setSalaryTo($value) {
        $this->salary_to = $value;
    }

    public function getSalaryTo() {
        return $this->salary_to;
    }

    public function setMultiplierEmployee($value) {
        $this->multiplier_employee = $value;
    }

    public function getMultiplierEmployee() {
        return $this->multiplier_employee;
    }

    public function setMultiplierEmployer($value) {
        $this->multiplier_employer = $value;
    }

    public function getMultiplierEmployer() {
        return $this->multiplier_employer;
    }

    public function setIsFixed($value) { //
        $this->is_fixed = $value;
    }

    public function getIsFixed() {
        return $this->is_fixed;
    }


    public function setDateEnd($value) { //
        $this->date_end = $value;
    }

    public function getDateEnd() {
        return $this->date_end;
    }


	
	public function save() {		
		return G_Philhealth_History_Manager::save($this);
	}

    /* public function update() {
        return G_Philhealth_Table_Manager::update($this);
    }    
	
    public function delete() {
        return G_Philhealth_Table_Manager::delete($this);
    }*/
}
?>