<?php
class G_Company_Structure_Finder {

	public static function findById($id) {
	
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";				
		return self::getRecord($sql);
	}


	public static function findParentId($parent_id) {
	
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE id =". Model::safeSql($parent_id) ."
			LIMIT 1

		";				
		return self::getRecord($sql);
	}
	
	//get all department by branch
	public static function findParentChildByBranchId($cbid) {
		$sql = "SELECT m.id,m.company_branch_id,m.title,m.description,m.type,m.parent_id
				FROM
					g_company_structure AS m
					INNER JOIN
					g_company_structure AS r
						ON r.id=m.id
					INNER JOIN
					g_company_structure AS n
						ON r.parent_id=n.id
				WHERE m.company_branch_id=".Model::safeSql($cbid)."
				ORDER BY m.id
				";
				
		return self::getRecords($sql);
	}

	//get all department by branch and is not archive
	public static function findParentChildByBranchIdAndIsNotArchive($cbid) {
		$sql = "SELECT m.id,m.company_branch_id,m.title,m.description,m.type,m.parent_id
				FROM
					g_company_structure AS m					
				WHERE m.company_branch_id=".Model::safeSql($cbid)."
					 AND m.is_archive='No'
				ORDER BY m.id
				";
				
		return self::getRecords($sql);
	}
	
	// load all group/team under of particular department
	// input g_company_structure.id
	public static function findGroupByDepartmentId($csid) {
		$sql = "SELECT m.id,m.company_branch_id,m.title,m.description,m.type,m.parent_id
				FROM
					g_company_structure AS m
					INNER JOIN
					g_company_structure AS r
						ON r.id=m.id
					INNER JOIN
					g_company_structure AS n
						ON r.parent_id=n.id and n.id=".Model::safeSql($csid)."
				
				ORDER BY m.id
				";
			//	echo $sql;
				
				
		return self::getRecords($sql);
	}
	
	
	// load all departments of that company regardless of branch
	// input g_company_structure.id
	public static function findParentChildByCompanyStructureId($csid) {
		$sql = "SELECT m.id,m.company_branch_id,m.title,m.description,m.type,m.parent_id
				FROM
					g_company_structure AS m
					INNER JOIN
					g_company_structure AS r
						ON r.id=m.id
					INNER JOIN
					g_company_structure AS n
						ON r.parent_id=n.id and n.id=".Model::safeSql($csid)."
				ORDER BY m.id
				";
		
		return self::getRecords($sql);
	}
	
	public static function searchByTitle($query, $csid) {
		$sql = "SELECT cs.id, cs.title, cs.parent_id
				FROM g_company_structure cs 
				WHERE (cs.title LIKE '%{$query}%' AND cs.parent_id =" . Model::safeSql($csid) . ")
				";
		return self::getRecords($sql);
	}

	public static function searchAllDepartmentByTitleAndIsNotArchive($title) {
		$sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE title LIKE '%{$title}%'
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "
				AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
		";
		
		return self::getRecords($sql);
	}

	public static function searchAllDepartmentTypeByTitleAndIsNotArchive($title) {
		$sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE title LIKE '%{$title}%'
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "
		";
		
		return self::getRecords($sql);
	}
	
	// FIND ALL DEPARTMENT UNDER OF BRANCH
	public static function findByCompanyBranchId($cbid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE company_branch_id =". Model::safeSql($cbid) ."			
		";

		return self::getRecords($sql);
	}
	
	public static function findByTitle($title) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE title =". Model::safeSql($title) ."			
		";

		return self::getRecord($sql);
	}

	public static function findByTitleAndType($title,$type) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE title =". Model::safeSql($title) ."	
				AND type =". Model::safeSql($type) ."			
		";

		return self::getRecord($sql);
	}

	public static function findByParentIdTitleAndType($parent_id,$title,$type) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE title =". Model::safeSql($title) ."	
				AND type =". Model::safeSql($type) ."
				AND parent_id =". Model::safeSql($parent_id) ."			
		";

		return self::getRecord($sql);
	}
	
	public static function findAllDepartmentsIsNotArchiveByBranchIdAndParentId($branchid,$parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id = " . Model::safeSql($parentid) . " 
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "  
				AND company_branch_id =" . Model::safeSql($branchid). " 
				AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
		";
		return self::getRecords($sql);
	}

	public static function findAllDepartmentsIsNotArchiveByBranchIdAndParentIdIncludeArchive($branchid,$parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id = " . Model::safeSql($parentid) . " 
				AND company_branch_id =" . Model::safeSql($branchid). " 
				AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
				ORDER BY title ASC		
		";

		return self::getRecords($sql);
	}	

	public static function findAllSectionsIsNotArchiveByBranchIdAndParentIdIncludeArchive($branchid,$parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE company_branch_id =" . Model::safeSql($branchid). " 
				AND type =" . Model::safeSql(G_Company_Structure::SECTION) . "
				ORDER BY title ASC		
		";

		return self::getRecords($sql);
	}		

	public static function findAllSectionIsNotArchiveByBranchIdAndParentId($branchid,$parentid) {
		$sql = "
			SELECT cs.title
			FROM " . COMPANY_STRUCTURE ." cs
			WHERE cs.parent_id > " . Model::safeSql($parentid) . " 
				AND cs.is_archive = " . Model::safeSql(G_Company_Structure::NO). "  
				AND cs.company_branch_id =" . Model::safeSql($branchid). " 
				AND cs.type =" . Model::safeSql(G_Company_Structure::SECTION) . "
		";
		return self::getRecords($sql);
	}
	
	public static function findAllDepartmentsIsNotArchiveByParentId($parentid) {
		$sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = " . Model::safeSql($parentid) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "
				AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
		";
		return self::getRecords($sql);
	}

	public static function findAllDepartmentsIsNotArchiveByParentIdAndType($parentid,$type) {
		$sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE parent_id = " . Model::safeSql($parentid) . "
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "
				AND type =" . Model::safeSql($type) . "
		";
		return self::getRecords($sql);
	}

	public static function findAllDepartmentsIsNotArchiveByType($type) {
		$sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE is_archive = " . Model::safeSql(G_Company_Structure::NO). "
			AND type =" . Model::safeSql($type) . "
		";
		return self::getRecords($sql);
	}	

    public static function findAllDepartmentsIsNotArchive() {
        $sql = "
			SELECT *
			FROM " . COMPANY_STRUCTURE ."
			WHERE is_archive = " . Model::safeSql(G_Company_Structure::NO). "
				AND type =" . Model::safeSql(G_Company_Structure::DEPARTMENT) . "
		";
        return self::getRecords($sql);
    }
	
	public static function findAllTeamsAndGroupsIsNotArchiveByParentId($parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE (parent_id = " . Model::safeSql($parentid) . " 
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). ")  				
				AND (type =" . Model::safeSql(G_Company_Structure::GROUP) . "
				OR type =" . Model::safeSql(G_Company_Structure::TEAM) . ") 
		";					
		return self::getRecords($sql);
	}

	public static function findAllSectionsIsNotArchiveByParentId($parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE (parent_id = " . Model::safeSql($parentid) . " 
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). ")  				
				AND type =" . Model::safeSql(G_Company_Structure::SECTION) . " 
		";					
		return self::getRecords($sql);
	}
	
	public static function findAllGroupsIsNotArchiveByBranchIdAndParentId($branchid,$parentid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id = " . Model::safeSql($parentid) . " 
				AND is_archive = " . Model::safeSql(G_Company_Structure::NO). "  
				AND company_branch_id =" . Model::safeSql($branchid). " 
				AND type =" . Model::safeSql(G_Company_Structure::GROUP) . "
		";

		return self::getRecords($sql);
	}

	public static function findByParentIDAndCompanyBranchId($pid,$cbid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id =". Model::safeSql($pid) ." AND company_branch_id=". Model::safeSql($cbid)."
		";

		return self::getRecords($sql);
	}
	
	public static function findByParentIDAndCompanyBranchIdAndIsNotArchive($pid,$cbid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id =". Model::safeSql($pid) ." AND company_branch_id=". Model::safeSql($cbid)."
			AND is_archive =" . Model::safeSql(G_Company_Structure::NO) . "
		";

		return self::getRecords($sql);
	}
	
	public static function findByParentID($pid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id =". Model::safeSql($pid) ."			
		";
		
		return self::getRecords($sql);
	}
	
	public static function findByMainParent() {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id =". Model::safeSql(0) ."
			LIMIT 1			
		";
		
		return self::getRecord($sql);		
	}	
	
	public static function findAllByParentIDAndIsNotArchive($pid) {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 
			WHERE parent_id =". Model::safeSql($pid) ." AND is_archive =" . Model::safeSql(G_Company_Structure::NO) . "			
		";
		return self::getRecords($sql);
	}
	
	public static function findAll() {
		$sql = "
			SELECT * 
			FROM " . COMPANY_STRUCTURE ." 			
			ORDER BY title ASC			
		";
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {
		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}
		while ($row = Model::fetchAssoc($result)) {
			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}
	
	private static function newObject($row) {
		$gcs = new G_Company_Structure;
		$gcs->setId($row['id']);
		$gcs->setCompanyBranchId($row['company_branch_id']);
		$gcs->setTitle($row['title']);
		$gcs->setDescription($row['description']);
		$gcs->setType($row['type']);
		$gcs->setParentId($row['parent_id']);	
		$gcs->setIsArchive($row['is_archive']);			
		return $gcs;
	}
}
?>