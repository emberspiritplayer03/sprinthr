<?php
class G_Employee_Official_Business_Request_Manager {
    /*
     * @param array $obs array instance of G_Employee_Official_Business_Request
     */
     public static function saveMultiple($obs) {
        $has_record = false;
        foreach ($obs as $o) {
            $insert_sql_values[] = "
                (". Model::safeSql($o->getId()) .",
                ". Model::safeSql($o->getCompanyStructureId()) .",
                ". Model::safeSql($o->getEmployeeId()) .",
                ". Model::safeSql($o->getDateApplied()) .",
                ". Model::safeSql($o->getDateStart()) .",
                ". Model::safeSql($o->getDateEnd()) .",

                 ". Model::safeSql($o->getWholeDay()) .",
                  ". Model::safeSql($o->getTimeStart()) .",
                   ". Model::safeSql($o->getTimeEnd()) .",

                ". Model::safeSql($o->getComments()) .",
                ". Model::safeSql($o->getIsApproved()) .",
                ". Model::safeSql($o->getCreatedBy()) .",
                ". Model::safeSql($o->getIsArchive()) .")";
            $has_record = true;
        }

        if ($has_record) {
            $insert_sql_value = implode(',', $insert_sql_values);
            $sql_insert = "
                INSERT INTO ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ." (id, company_structure_id, employee_id, date_applied, date_start, date_end,is_whole_day,time_start,time_end,comments, is_approved, created_by, is_archive)
                VALUES ". $insert_sql_value ."
                ON DUPLICATE KEY UPDATE
                    company_structure_id = VALUES(company_structure_id),
                    employee_id = VALUES(employee_id),
                    date_applied = VALUES(date_applied),
                    date_start = VALUES(date_start),
                    date_end = VALUES(date_end),

                    is_whole_day = VALUES(is_whole_day),
                    time_start = VALUES(time_start),
                    time_end = VALUES(time_end),

                    comments = VALUES(comments),
                    is_approved = VALUES(is_approved),
                    created_by = VALUES(created_by),
                    is_archive = VALUES(is_archive)
            ";
            Model::runSql($sql_insert);
        }

        if (mysql_errno() > 0) {
            //echo mysql_error();
            return false;
        } else {
            $insert_id = Sql::getInsertId();
            if ($insert_id > 0) {
                return $insert_id;
            } else {
                return true;
            }
        }
    }

    public static function save($ob) {
        $obs[] = $ob;
        return G_Employee_Official_Business_Request_Manager::saveMultiple($obs);
    }

	public static function saveOLD(G_Employee_Official_Business_Request $gob) {
		if (G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0 ) {
			$action    = "UPDATE";	
			$sql_start = "UPDATE ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " ";
			$sql_end   = "WHERE id = ". Model::safeSql($gob->getId());		
		}else{
			$action    = "INSERT";
			$sql_start = "INSERT INTO ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST . " ";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id  =" . Model::safeSql($gob->getCompanyStructureId()) . ",
			employee_id  		  =" . Model::safeSql($gob->getEmployeeId()) . ",
			date_applied 		  =" . Model::safeSql($gob->getDateApplied()) . ",
			date_start   		  =" . Model::safeSql($gob->getDateStart()) . ",			
			date_end 	 		  =" . Model::safeSql($gob->getDateEnd()) . ",
			comments	 		  =" . Model::safeSql($gob->getComments()) . ",  		
			is_approved	 		  =" . Model::safeSql($gob->getIsApproved()) . ",  		
			created_by	 		  =" . Model::safeSql($gob->getCreatedBy()) . ",  
			is_archive	 		  =" . Model::safeSql($gob->getIsArchive()) . " "				
			. $sql_end ."	
		
		";
		
		Model::runSql($sql);
		
		if($action == "INSERT") {
			return mysql_insert_id();	
		} else {
			return $gob->getId();	
		}
		
	}
		
	public static function delete(G_Employee_Official_Business_Request $gob){
		if(G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
				WHERE id =" . Model::safeSql($gob->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function approve(G_Employee_Official_Business_Request $gob){
		if(G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
				SET is_approved =" . Model::safeSql(G_Employee_Official_Business_Request::STATUS_APPROVED) . "
				WHERE id =" . Model::safeSql($gob->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function disapprove(G_Employee_Official_Business_Request $gob){
		if(G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
				SET is_approved =" . Model::safeSql(G_Employee_Official_Business_Request::STATUS_DISAPPROVED) . "
				WHERE id =" . Model::safeSql($gob->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function archive(G_Employee_Official_Business_Request $gob){
		if(G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
				SET is_archive =" . Model::safeSql(G_Employee_Official_Business_Request::YES) . "
				WHERE id =" . Model::safeSql($gob->getId());
			Model::runSql($sql);
		}	
	}
	
	public static function restore_archived(G_Employee_Official_Business_Request $gob){
		if(G_Employee_Official_Business_Request_Helper::isIdExist($gob) > 0){
			$sql = "
				UPDATE ". G_EMPLOYEE_OFFICIAL_BUSINESS_REQUEST ."
				SET is_archive =" . Model::safeSql(G_Employee_Official_Business_Request::NO) . "
				WHERE id =" . Model::safeSql($gob->getId());
			Model::runSql($sql);
		}	
	}
}
?>