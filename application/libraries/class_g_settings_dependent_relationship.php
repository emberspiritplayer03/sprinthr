<?php
/**
 * @Software			: HR & Payroll Web
 * @Company 			: Gleent Innovative Technologies
 * @Developement Team	: Marvin Dungog, Marlito Dungog, Bryan Bio, Jeniel Mangahis, Bryann Revina
 * @Design Team			: Jayson Alipala
 * @Author				: Bryann Revina
 */

/**
Usage:
	ADD OR UPDATE DATA:
	$c = new G_Settings_Dependent_Relationship(1);
	$c->setCompanyStructureId(5);
	$c->setRelationship('wife');
	$c->save();
	
	PRINT DATA:
	$data = G_Settings_Dependent_Relationship_Finder::findAll();
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	
	DELETE:
	$c = new G_Settings_Dependent_Relationship(1);
	$c->delete();

*/

class G_Settings_Dependent_Relationship {
	public $id;
	public $company_structure_id;
	public $relationship;
		
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setCompanyStructureId($value) {
		$this->company_structure_id = $value;	
	}
	
	public function setRelationship($value) {
		$this->relationship = $value;	
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getCompanyStructureId() {
		return $this->company_structure_id;
	}
	
	public function getRelationship() {
		return $this->relationship;
	}
					
	public function save() {
		return G_Settings_Dependent_Relationship_Manager::save($this);
	}
	
	public function delete() {
		G_Settings_Dependent_Relationship_Manager::delete($this);
	}
	
}
?>