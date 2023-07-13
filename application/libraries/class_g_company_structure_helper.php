<?php
class G_Company_Structure_Helper {
    public static function addEmployeeToSubdivision(G_Company_Structure $gcs, $e, $start_date, $end_date) {
        $is_added = G_Company_Structure_Manager::addEmployeeToSubdivision($gcs, $e, $start_date, $end_date);
        G_Employee_Manager::updateEmployeeDepartmentId($e, $gcs->getId());

        return $is_added;
    }

    public static function generate($title, $branch_id, $parent_company_structure_id) {
        $dept = new G_Company_Structure;
        $dept->setTitle($title);
        $dept->setCompanyBranchId($branch_id);
        $dept->setParentId($parent_company_structure_id);
        $dept->setType(G_Company_Structure::DEPARTMENT);
        return $dept;
    }

    public static function generateByType($title, $branch_id, $parent_company_structure_id, $type) {
        $dept = new G_Company_Structure;
        $dept->setTitle($title);
        $dept->setCompanyBranchId($branch_id);
        $dept->setParentId($parent_company_structure_id);
        $dept->setType($type);
        return $dept;
    }

	public static function isIdExist(G_Company_Structure $gcs) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE id = ". Model::safeSql($gcs->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlCompanyName() {
		$sql = "
			SELECT title
			FROM " . COMPANY_STRUCTURE ."
			WHERE id = ". Model::safeSql(G_Company_Structure::PARENT_ID) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['title'];
	}

	public static function sqlCompanyInfo() {
		$sql = "
			SELECT *
			FROM g_company_info
			WHERE company_structure_id = ". Model::safeSql(G_Company_Structure::PARENT_ID) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}

	public static function sqlIsIdExist( $id = 0 ) {
		$return = false;

		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE id = ". Model::safeSql($id) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		
		if( $row['total'] > 0 ){
			$return = true;
		}
		
		return $return;
	}

	public static function sqlCompanyStructureDataByTitle($title = '') {
		if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}

    	$sql = "
            SELECT {$sql_fields} 
            FROM " . COMPANY_STRUCTURE . " 
            WHERE title = " . Model::safeSql($title) . "            	
            ORDER BY id DESC
            LIMIT 1
        ";
       
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
	
	public static function countTotalRecordsByParentId($parent_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ."
		";		

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByParentIdAndIsNotArchive($parent_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." AND is_archive =" . Model::safeSql(G_Company_Structure::NO) . "
		";		

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByParentIdAndCompanyBranchId($parent_id,$branch_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." AND company_branch_id=".Model::safeSql($branch_id)."
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function sqlDepartmentDetailsById( $id = 0, $fields = array() ){
    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}
    	    	
    	$sql = "
            SELECT {$sql_fields} 
            FROM " . COMPANY_STRUCTURE . " 
            WHERE id = " . Model::safeSql($id) . "
            	AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . " 
            ORDER BY id DESC
            LIMIT 1
        ";       
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
    }

    public static function sqlDataById( $id = 0, $fields = array() ){
    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}

    	$sql = "
            SELECT {$sql_fields} 
            FROM " . COMPANY_STRUCTURE . " 
            WHERE id = " . Model::safeSql($id) . "            	
            ORDER BY id DESC
            LIMIT 1
        ";
        
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
    }

    public function sqlGetAllSections( $fields = array(), $order_by = '' ) {
    	$sql_fields   = " * ";
    	$sql_order_by = " ORDER BY id DESC ";

    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by;
    	}

    	$sql = "
			SELECT {$sql_fields}
			FROM " . COMPANY_STRUCTURE ."  			
			WHERE type = " . Model::safeSql(G_Company_Structure::SECTION) . "
			{$sql_order_by}
		";
	
		$rows = Model::runSql($sql,true);
		return $rows;
    }

    public function sqlGetAllDepartmentSections( $department_id = 0, $fields = array(), $order_by = '' ) {
    	$sql_fields   = " * ";
    	$sql_order_by = " ORDER BY id DESC ";

    	if( !empty($fields) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by;
    	}
    	
    	$sql = "
			SELECT {$sql_fields}
			FROM " . COMPANY_STRUCTURE ."  			
			WHERE type = " . Model::safeSql(G_Company_Structure::SECTION) . "
				AND parent_id =" . Model::safeSql($department_id) . "
			{$sql_order_by}
		";
	
		$rows = Model::runSql($sql,true);
		return $rows;
    }

    public static function sqlAllIsNotArchiveDepartments($fields = array(), $order_by = '') {
    	$sql_fields   = " * ";
    	$sql_order_by = "";

    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by; 
    	}


		$sql = "
			SELECT {$sql_fields}
			FROM " . COMPANY_STRUCTURE ."  			
			WHERE type = " . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
			{$sql_order_by}
		";
	
		$rows = Model::runSql($sql,true);
		return $rows;
	}

	public static function sqlAllIsNotArchiveDepartmentSections($parent_id = 0, $fields = array(), $order_by = '') {
    	$sql_fields   = " * ";
    	$sql_order_by = "";

    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}

    	if( !empty($order_by) ){
    		$sql_order_by = $order_by; 
    	}


		$sql = "
			SELECT {$sql_fields}
			FROM " . COMPANY_STRUCTURE ."  			
			WHERE parent_id = " . Model::safeSql($parent_id) . "
				AND type = " . Model::safeSql(G_Company_Structure::GROUP) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
			{$sql_order_by}
		";
	
		$rows = Model::runSql($sql,true);
		return $rows;
	}

    public static function sqlGroupDetailsById( $id = 0, $fields = array() ){
    	if( !empty( $fields ) ){
    		$sql_fields = implode(",", $fields);
    	}else{
    		$sql_fields =  " * ";
    	}

    	$sql = "
            SELECT {$sql_fields} 
            FROM " . COMPANY_STRUCTURE . " 
            WHERE id = " . Model::safeSql($id) . "
            	AND type =" . Model::safeSql(G_Company_Structure::GROUP) . " 
            ORDER BY id DESC
            LIMIT 1
        ";
        
        $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
    }
	
	public static function countTotalBranchesIsNotArchiveByParentIdAndCompanyBranchId($parent_id,$branch_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." 
				AND company_branch_id = ". Model::safeSql($branch_id) ." 
				AND type = " . Model::safeSql(G_Company_Structure::BRANCH) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalDepartmentsIsNotArchiveByParentIdAndCompanyBranchId($parent_id,$branch_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." 
				AND company_branch_id = ". Model::safeSql($branch_id) ." 
				AND type = " . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalGroupsIsNotArchiveByParentId($parent_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." 				
				AND type = " . Model::safeSql(G_Company_Structure::GROUP) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function countTotalSectionsIsNotArchiveByParentId($parent_id) {
		$sql = "
			SELECT COALESCE(COUNT(*),0) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." 				
				AND type = " . Model::safeSql(G_Company_Structure::SECTION) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalTeamsIsNotArchiveByParentId($parent_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." 				
				AND type = " . Model::safeSql(G_Company_Structure::TEAM) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
		
	public static function countTotalRecordsByParentIdAndCompanyBranchIdAndIsNotArchive($parent_id,$branch_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = ". Model::safeSql($parent_id) ." AND company_branch_id=".Model::safeSql($branch_id)." AND is_archive =" . Model::safeSql(G_Company_Structure::NO) . "
		";		
			
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function countTotalRecordsByCompanyBranchId($branch_id) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . COMPANY_STRUCTURE ."
			WHERE company_branch_id = ". Model::safeSql($branch_id) ."
		";
	
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function isEmployeeSubdivisionHistoryExist(G_Employee $e,G_Company_Structure $c, $start_date, $end_date) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_SUBDIVISION_HISTORY ."
			WHERE employee_id = ". Model::safeSql($e->getId()) ." 
			AND company_structure_id  = ".Model::safeSql($c->getId())."  AND start_date = ". Model::safeSql($start_date) ." AND end_date = ".Model::safeSql($end_date)."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];	
	}
	
	public static function findCompanyBranch($company_structure_id) {
		$sql = "
			SELECT cs.id,br.name as name 
			FROM " . COMPANY_STRUCTURE ." cs 
			LEFT JOIN " . COMPANY_BRANCH . " br 
			ON cs.company_branch_id = br.idate(format)
			WHERE cs.id = " . Model::safeSql($company_structure_id) . "
		";
	
		$rows = Model::runSql($sql,true);
		return $rows[0];
	}

	public static function sqlGetDepartmentDataByTitle($department_titles = "", $fields = array()){
		if( !empty($fields) ){
			$sql_fields = implode(",",$fields);
		}else{
			$sql_fields = "*";
		}

		$sql = "
			SELECT {$sql_fields}
			FROM " . COMPANY_STRUCTURE . " 
			WHERE title IN({$department_titles})
			ORDER BY title ASC
		";
		$result = Model::runSql($sql,true);
		return $result;
	}

	
	public static function sqlAllSectionIsNotArchiveByBranchIdAndParentId($branchid,$parentid){
		$sql = "
			SELECT cs.* , CONCAT((SELECT title FROM g_company_structure cs2 WHERE id = cs.parent_id), ' - ', cs.title) AS dept_section
			FROM " . COMPANY_STRUCTURE ." cs
			WHERE cs.parent_id > " . Model::safeSql($parentid) . " 
				AND cs.is_archive = " . Model::safeSql(G_Company_Structure::NO). "  
				AND cs.company_branch_id =" . Model::safeSql($branchid). " 
				AND cs.type =" . Model::safeSql(G_Company_Structure::SECTION) . "
		";
		$result = Model::runSql($sql,true);
		return $result;
	}
}
?>