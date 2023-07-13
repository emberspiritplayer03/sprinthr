<?php
class Tree_View{	
	
	public static function asynCompanyStructure($root)
	{
		if($_POST['root'] != 'source'){
			$arParameter = explode(",",$_POST['root']);			
		}
		$start_tag = '[';
		if($_POST['root'] == 'source'){
			$c_structure = G_Company_Structure_Finder::findById($_SESSION['sprint_hr']['company_structure_id']);
			$data 		 = G_Company_Structure_Finder::findByParentID($c_structure->getId());
			$count 		 = G_Company_Structure_Helper::countTotalRecordsByParentId($c_structure->getId());		
				if($c_structure){
					$tree_body .= '{';
						$buttons = self::constructTools($c_structure->getId(),1);					
						$tree_body .= '"text": "' . $buttons . $c_structure->getTitle() . '"';						
						$tree_body .= self::asyncGetBranch($c_structure->getId());
					$tree_body .= '}';
				}
		}elseif($arParameter[0] == 'branch'){
			sleep(1);
			$tree_body = self::asyncGetDepartments(Utilities::decrypt($arParameter[1]),Utilities::decrypt($arParameter[2]));
			//$tree_body = self::asyncGetAssignedEmployees($_GET['root']);
		}else{
			sleep(1);
			$tree_body = self::asynGetSiblings(Utilities::decrypt($arParameter[1]));
		}
		
		$end_tag = ']';
		
		return $start_tag . $tree_body . $end_tag;
	}
	
	private function constructTools($id, $set)
	{		
		if($set == 1){
		//Add Branch		
			$buttons ='<div class=\"tree-buttons\"><a style=\"display:inline-block;\" href=\"javascript:void(0);\" onclick=\"javascript:load_add_branch(' . $id . ');\"><label title=\"Add New\" id=\"add\" class=\"ui-icon ui-icon-plusthick\"></label></a></div>';
		}else{			
			$buttons ='<div class=\"tree-buttons\"><a style=\"display:inline-block;\" href=\"javascript:void(0);\" onclick=\"javascript:load_add_structure(' . $id . ');\"><label title=\"Add New\" id=\"add\" class=\"ui-icon ui-icon-plusthick\"></label></a><a style=\"display:inline-block; margin-right:10px;\" href=\"javascript:void(0);\" onclick=\"javascript:load_delete_structure(' . $id . ');\"><label title=\"Delete\" id=\"add\" class=\"ui-icon ui-icon-trash\"></label></a></div>';
		}		
		
		return $buttons;
	}
	
	private function asyncGetDepartments($parent,$branch)
	{			
		$siblings = G_Company_Structure_Finder::findByParentIDAndCompanyBranchId($parent,$branch);
		$count    = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchId($parent,$branch);	
			
		if($siblings){
			$c = 1;
				foreach($siblings as $s){
					$body .= '{';
					$buttons = self::constructTools($s->getId(),2);					
					$body .= '"text": "' . $buttons . $s->getTitle() . '"';					
					
					$ccount = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchId($s->getId(),$s->getCompanyBranchId());
					if($ccount > 0){
						$body .= ',"id": "siblings,' . Utilities::encrypt($s->getId()) . ',' . Utilities::encrypt($s->getId()) . '",';											
						$body .= '"hasChildren": true';																
					}
					
					if($count == $c){
						$body .= '}';
					}else{
						$body .= '},';
					}
					$c++;
				}			
			$children_constructor = $concut . $start_tag . $body . $end_tag;
		}
		
		return $children_constructor;		
	}
	
	private function asynGetSiblings($parent)
	{			
		$siblings = G_Company_Structure_Finder::findByParentID($parent);
		$count    = G_Company_Structure_Helper::countTotalRecordsByParentId($parent);
		
		if($siblings){
			$c = 1;
				foreach($siblings as $s){
					$body .= '{';
					$buttons = self::constructTools($s->getId(),2);					
					$body .= '"text": "' . $buttons . $s->getTitle() . '"';
					
					$ccount = G_Company_Structure_Helper::countTotalRecordsByParentId($s->getId());
					if($ccount > 0){
						$body .= ',"id": "siblings,' . Utilities::encrypt($s->getId()) . '",';																	
						$body .= '"hasChildren": true';																
					}
					
					if($count == $c){
						$body .= '}';
					}else{
						$body .= '},';
					}
					$c++;
				}			
			$children_constructor = $concut . $start_tag . $body . $end_tag;
		}		
		return $children_constructor;			
	}
	
	private function asyncGetAssignedEmployees($parent)
	{	
		$employees = G_Employee_Subdivision_History_Finder::findAllCurrentEmployeesByCompanyStructureId($parent);
		$count 	   = G_Employee_Subdivision_History_Helper::countTotalCurrentEmployeeByCompanyStructureId($parent);
		
		if($employees){			
			
			$c = 1;
				foreach($employees as $emp){
					$e = G_Employee_Finder::findById($emp->getEmployeeId());	
					if($e){
						$body .= '{';
						$name = $e->getFirstname() . ' ' . $e->getLastname();						
						$body .= '"text": "' . $name . '"';							
					}
					
					if($count == $c){
						$body .= '}';
					}else{						
						$body .= '},';
					}
					
					$c++;
				}
			
			
			$children_constructor = $concut . $body;
		}
		
		return $children_constructor;		
	}
	
	private function asyncGetBranch($parent) {
		$c_structure = G_Company_Structure_Finder::findById($parent);
		$branches    = G_Company_Branch_Finder::findByCompanyStructureId($parent); 
		$count       = G_Company_Branch_Helper::countTotalRecordsByCompanyStructureId($c_structure);		
		if($branches){
			$concut    = ',';
			$start_tag = '"children":[';
			$c = 1;
				foreach($branches as $b){
					$body .= '{';
					$buttons = self::constructTools($b->getId(),2);
					$body .= '"text": "' . $buttons . $b->getName() . ' branch';
					$ccount = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchId($b->getCompanyStructureId(),$b->getId());
					if($ccount > 0){
						$body .= '","id": "branch,' . Utilities::encrypt($b->getCompanyStructureId()) . ',' . Utilities::encrypt($b->getId()) . '",';		
						$body .= '"hasChildren": true';										
						//$body .= self::asyncGetAssignedEmployees($s->getId());
					}
					
					if($count == $c){
						$body .= '}';
					}else{
						$body .= '},';
					}
					$c++;
				}
			$end_tag   = ']';
			
			$children_constructor = $concut . $start_tag . $body . $end_tag;
		}
		
		return $children_constructor;		
	}	
	
	public static function buildCompanyStructure()
	{
		$c_structure = G_Company_Structure_Finder::findById($_SESSION['sprint_hr']['company_structure_id']);		
		$count 		 = G_Company_Structure_Helper::countTotalRecordsByParentId($c_structure->getId());		
		$count = 1;		
		$buttons =' <div class="tree-buttons">
						<a style="display:inline-block;" href="javascript:void(0);" onclick="javascript:addBranch(' . $c_structure->getId() . ');">
					    <label title="Add New" id="add" class="ui-icon ui-icon-plusthick"></label>
						</a>						
						</div>';
		$tree_var = '<ul class="filetree treeview-famfamfam" id="tree">';	
			if($count == $count){					
					if($count_child == 0){$tree_var .= '<li class ="last"><span class="folder">';}					
					else{$tree_var .= '<li class="expandable lastExpandable"><span class="folder">';}							
			}
			else{					
					if($count_child == 0){$tree_var .= '<li><span class="folder">';}										
					else{$tree_var .= '<li class="expandable"><span class="folder">';}			
			}			
			$title = $c_structure->getTitle();						
			$tree_var .= '<a href="javascript:void(0)"  style="color:#333 !important">'. $buttons . $title .'</a> <span class="treeview_url"></span></span>';
				
				$tree_var .= self::get_branch($c_structure->getId());
				$tree_var .= '</li>';
				$count +=1;		
		$tree_var .= '</ul>';
		return $tree_var;
	}
	
	private function get_branch($parent) {
		$tree_data   = G_Company_Branch_Finder::findByCompanyStructureId($parent); 
		$c_structure = G_Company_Structure_Finder::findById($_SESSION['sprint_hr']['company_structure_id']);
		$data        = G_Company_Branch_Helper::countTotalRecordsByCompanyStructureId($c_structure);
		
		if($data> 0){
		$child = '<ul style="display: none;">';
		$count = 1;
			foreach($tree_data as $row1){	
								
			$count_child = G_Company_Structure_Helper::countTotalRecordsByParentId($row1->getId());
			if($count == $data){
				if($count_child == 0 ){$child .= '<li class ="last"><span class="folder">';}			
				else{$child .= '<li class="expandable lastExpandable"><span class="folder">';}									
			}
			else{
				if($count_child == 0){$child .= '<li class ="last"><span class="folder">';}											
				else{$child .= '<li class="expandable"><span class="folder">';}		
				}
						
			$title_children = $row1->getName() . " Branch";
			
			$buttons =' <div class="tree-buttons">
						<a style="display:inline-block;" href="javascript:void(0);" onclick="javascript:addNewCompanyStructure(' . $row1->getCompanyStructureId() . ',' . $row1->getId() . ');">
					    <label title="Add New" id="add" class="ui-icon ui-icon-plusthick"></label>
						</a>
						<a style="display:inline-block; margin-right:10px;" href="javascript:void(0);" onclick="javascript:deleteBranch(' . $row1->getId() . ');">
					    <label title="Delete" id="add" class="ui-icon ui-icon-trash"></label>
						</a>
						</div>';
			
			$child .= '<a href="javascript:void(0)" style="color:#333 !important">' . $buttons . $title_children . '</a><span class="treeview_url"></span></span>';
			$child .= self::get_children($row1->getCompanyStructureId(),$row1->getId(), $level+1);
			$child .= '</li>';
			$count += 1;
			}
		$child .= '</ul>';
		}
		
		return $child;
		
		
	}
	
	private function get_branch_children($parent,$branch, $level = 1)
	{	
		
		$tree_data = G_Company_Structure_Finder::findByParentIDAndCompanyBranchId($parent,$branch);
		$data      = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchId($parent,$branch);		
		
		if($data > 0){
		$child = '<ul style="display: none;">';
		$count = 1;
			foreach($tree_data as $row1){			
			$count_child = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchId($parent,$branch);
			if($count == $data){
				if($count_child == 0 ){$child .= '<li class ="last"><span class="folder">';}			
				else{$child .= '<li class="expandable lastExpandable"><span class="folder">';}									
			}
			else{
				if($count_child == 0){$child .= '<li class ="last"><span class="folder">';}											
				else{$child .= '<li class="expandable"><span class="folder">';}		
			}
						
			$title_children = $row1->getTitle();
			$buttons =' <div class="tree-buttons">
						<ul class="tree_view_tools">
						<li class="last_icon">
							<a style="display:inline-block;" href="'.url('settings/employee_group?company_structure='.$row1->getId()).'">
					    		<label title="Add Employee(s)" id="employee" class="ui-icon ui-icon-person"></label>
							</a>
						</li>
						</ul>
						</div>';
			
			$child .= '
				<a href="javascript:void(0)" style="color:#333 !important">' . $buttons . $title_children . '</a><span class="treeview_url"></span></span>';
			$child .= self::get_assigned_employees($row1->getId());
			$child .= self::get_sub_group_children($row1->getId(), $row1->getParentId(), $level+1);
			$child .= '</li>';
			$count += 1;
			}
		$child .= '</ul>';
		}
		
		return $child;
	}
	
	private function get_children($parent,$branch, $level = 1)
	{	
		
		$tree_data = G_Company_Structure_Finder::findByParentIDAndCompanyBranchIdAndIsNotArchive($parent,$branch);
		$data      = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchIdAndIsNotArchive($parent,$branch);
		
		//$tree_data = G_Company_Structure_Finder::findByCompanyBranchId($parent);
		//$data     = G_Company_Structure_Helper::countTotalRecordsByCompanyBranchId($parent);
		if($data > 0){
		$child = '<ul style="display: none;">';
		$count = 1;
			foreach($tree_data as $row1){			
			//$count_child = G_Company_Structure_Helper::countTotalRecordsByParentId($row1->getId());						
			$count_child = G_Company_Structure_Helper::countTotalRecordsByParentIdAndCompanyBranchIdAndIsNotArchive($parent,$branch);
			if($count == $data){
				if($count_child == 0 ){$child .= '<li class ="last"><span class="folder">';}			
				else{$child .= '<li class="expandable lastExpandable"><span class="folder">';}									
			}
			else{
				if($count_child == 0){$child .= '<li class ="last"><span class="folder">';}											
				else{$child .= '<li class="expandable"><span class="folder">';}		
				}
						
			$title_children = $row1->getTitle();
			$buttons =' <div class="tree-buttons">
						<ul class="tree_view_tools">
						<a style="display:inline-block;" href="javascript:void(0);" onclick="javascript:addNewCompanyStructure(' . $row1->getId() . ',0);">
					    <label title="Add New" id="add" class="ui-icon ui-icon-plusthick"></label>
						</a>
						<a style="display:inline-block; margin-right:10px;" href="javascript:void(0);" onclick="javascript:load_delete_structure(' . $row1->getId() . ');">
					    <label title="Delete" id="add" class="ui-icon ui-icon-trash"></label>
						</a>
						</div>';
			
			$child .= '
				<a href="javascript:void(0)" style="color:#333 !important">' . $buttons . $title_children . '</a><span class="treeview_url"></span></span>';
			$child .= self::get_sub_children($row1->getId(), $row1->getParentId(), $level+1);
			$child .= '</li>';
			$count += 1;
			}
		$child .= '</ul>';
		}
		
		return $child;
	}
	
	private function get_sub_children($parent, $level = 1)
	{	
		
		$tree_data = G_Company_Structure_Finder::findAllByParentIDAndIsNotArchive($parent);
		$data      = G_Company_Structure_Helper::countTotalRecordsByParentIdAndIsNotArchive($parent);
		if($data > 0){
		$child = '<ul style="display: none;">';
		$count = 1;
			foreach($tree_data as $row1){			
			$count_child = G_Company_Structure_Helper::countTotalRecordsByParentIdAndIsNotArchive($row1->getId());						
			if($count == $data){
				if($count_child == 0 ){$child .= '<li class ="last"><span class="folder">';}			
				else{$child .= '<li class="expandable lastExpandable"><span class="folder">';}									
			}
			else{
				if($count_child == 0){$child .= '<li class ="last"><span class="folder">';}											
				else{$child .= '<li class="expandable"><span class="folder">';}		
				}
						
			$title_children = $row1->getTitle();
			$buttons =' <div class="tree-buttons">
						<a style="display:inline-block;" href="javascript:void(0);" onclick="javascript:addNewCompanyStructure(' . $row1->getId() . ',0);">
					    <label title="Add New" id="add" class="ui-icon ui-icon-plusthick"></label>
						</a>
						<a style="display:inline-block; margin-right:10px;" href="javascript:void(0);" onclick="javascript:load_delete_structure(' . $row1->getId() . ');">
					    <label title="Delete" id="add" class="ui-icon ui-icon-trash"></label>
						</a>
						</div>';
			
			$child .= '
				<a href="javascript:void(0)" style="color:#333 !important">' . $buttons . $title_children . '</a><span class="treeview_url"></span></span>';
			$child .= self::get_sub_children($row1->getId(), $level+1);
			$child .= '</li>';
			$count += 1;
			}
		$child .= '</ul>';
		}
		
		return $child;
	}
		
}
?>