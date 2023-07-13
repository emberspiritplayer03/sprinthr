<?php

class BreadCrumbs {	
	public $id;
	public $root_id;
	public $branch_id;
	public $trail_id;
	
	public function __construct($value) {
		$this->root_id = $value;
	}
	
	public function setId($value) {
		$this->id = $value;
	}
	
	public function setRootId($value) {
		$this->root_id = $value;
	}
	
	public function setBranchId($value) {
		$this->branch_id = $value;
	}
	
	public function setTrailId($value){
		$this->trail_id = $value;
	}
	
	public function constructEmployeeGroupBreadCrumbs()
	{
		$breadcrumbs = self::employee_group_recursion($this->id);
		return $breadcrumbs;	
	}
	
	public function constructCompanyStructureBreadCrumbs()
	{		
					
		$trail = self::constructRootTrail();
				
		if($this->branch_id){
			$trail .= self::constructBranchTrail();
		}
		
		if($this->trail_id){
			$trail .= self::constructTrail($this->trail_id);
		}
		
		return $trail;	
	}
	
	private function constructRootTrail()
	{
		$c = G_Company_Structure_Finder::findById($this->root_id);
		if($c){
			$main = '<li><a href="javascript:load_company_structure();">' . $c->getTitle() . '</a> <span class="divider">/</span></li>';
		}else{
			$main = '';
		}
		
		return $main;
	}
	
	private function constructBranchTrail()
	{
		$b = G_Company_Branch_Finder::findById($this->branch_id);
		if($b){
			$branch = '<li><a href="javascript:load_branch_departments(\'' . Utilities::encrypt($b->getId()) . '\');">' . $b->getName() . '</a> <span class="divider">/</span></li>';
		}else{
			$branch = '';
		}
		
		return $branch;
	}
	
	private function constructTrail($trail_id)
	{
		//echo 'Trail Id: ' . $trail_id;			
		$cs = G_Company_Structure_Finder::findById($trail_id);				
		if($cs){				
			$title      = $cs->getTitle();
			$parent_id  = $cs->getParentId();
							
			if($cs->getId() != $this->root_id){
				$text = '<li><a href="javascript:load_department_teams_groups(\'' . Utilities::encrypt($cs->getId()) . '\');">' . $title . '</a> <span class="divider">/</span></li>';					
			}else{
				$text = '';
			}
						
			$trail .= self::constructTrail($parent_id). $text;
			
		}
		return $trail;
	}
	
	private function employee_group_recursion($parent_id)
	{		
		$cs = G_Company_Structure_Finder::findById($parent_id);
        $g = G_Group_Finder::findById($parent_id);

        if ($g->isParent()) {
            return false;
        }

		if($cs){				
			$title      = $cs->getTitle();
			$parent_id  = $cs->getParentId();
										
			if($this->id == $cs->getId()){	
				
				if($cs->getTitle() != 'IM Digital'){
					$text = '<li class="current_page">' . $title . '</li>';
				}else{
					$text = '';
				}
							
				$rec .= self::employee_group_recursion($parent_id). $text;
			}else{
				
				$url = 'href="javascript:void(0);" onclick="javascript:load_child_group_list(\'' . Utilities::encrypt($cs->getId()) . '\')"';				
				
				if($cs->getTitle() != 'IM Digital'){
					$text = '<li><a ' . $url . '>' . $title . '</a> &raquo;</li>';
				}else{
					$text = '';
				}
				
				$rec .= self::employee_group_recursion($parent_id). $text;
			}		
		}
		return $rec;
	}
}
?>