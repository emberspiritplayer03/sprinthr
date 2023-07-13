<?php
class G_Payslip_Template extends Payslip_Template {

    const IS_DEFAULT_YES = 'Yes';
    const IS_DEFAULT_NO  = 'No';
	
	public function __construct() {}

	public function save() {		
		return G_Payslip_Template_Manager::save($this);
	}
	
	public function delete() {
		return G_Payslip_Template_Manager::delete($this);
	}	

	public function clearDefaultTemplate() {
		G_Payslip_Template_Manager::clearDefaultTemplate($this);
		return $this;
	}
}

?>