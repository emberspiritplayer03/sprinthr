<?php

class G_Employee_Project_Site_History_Manager {

	public $table = "";


	public static function getEmployeeProjectSite(G_Employee_Project_Site_History $e){

		try {
			
			$sql = "SELECT 
				g_employee_project_site_history.id as psh_id ,
				g_employee_project_site_history.employee_id ,
				g_employee_project_site_history.project_id ,
				g_employee_project_site_history.start_date ,
				g_employee_project_site_history.end_date ,
				g_employee_project_site_history.employee_status,
				g_employee_project_site_history.status_date ,
				g_project_sites.name as project_name
			 FROM g_employee_project_site_history  INNER JOIN g_project_sites WHERE g_project_sites.id = g_employee_project_site_history.project_id AND   g_employee_project_site_history.employee_id = ".  Model::safeSql($e->getEmployeeId()) . " ORDER BY g_employee_project_site_history.start_date DESC" ;
			
			$result = Model::runSql($sql,true);
			return $result;

		} catch (Exception $e) {
			echo "Error getEmployeeProjectSite: " . $e->getMessage();
		}
	}

	public static function setProjectHistory(G_Employee_Project_Site_History $e)
	{
		try {
			//
			$upd = "UPDATE g_employee_project_site_history SET ";
			
			$upd .= " end_date = " . Model::safeSql($e->getStartDate());
			
			$upd .= " WHERE employee_id = " . Model::safeSql($e->getEmployeeId());
			
			$upd .=" AND end_date = '' ";
			

			if($e->getEndDate() == ""){
				Model::runSql($upd);
			}
		

			
			
			$sql = "INSERT INTO g_employee_project_site_history (employee_id , project_id , start_date , end_date,employee_status,status_date) VALUES ("
		. 	Model::safeSql($e->getEmployeeId()). " ,"
		. 	Model::safeSql($e->getProjectId()). " ,"
		. 	Model::safeSql($e->getStartDate()). " ,"
		. 	Model::safeSql($e->getEndDate()) . " ,"
		. 	Model::safeSql($e->getEmployeeStatus()). ","
		. 	Model::safeSql($e->getStatusDate()). ")";
			
		$result = Model::runSql($sql);
		return $result;
		
		} catch (Exception $e) {
			echo "Error setProjectHistory: " . $e->getMessage();
		}
	}

	//new -alex
	public function saveNewEmployeeProjectSite(G_Employee_Project_Site_History $e){


		$sql = "INSERT INTO g_employee_project_site_history (employee_id , project_id , start_date , end_date,employee_status,status_date) VALUES ("
		. 	Model::safeSql($e->getEmployeeId()). " ,"
		. 	Model::safeSql($e->getProjectId()). " ,"
		. 	Model::safeSql($e->getStartDate()). " ,"
		. 	Model::safeSql($e->getEndDate()) . " ,"
		. 	Model::safeSql($e->getEmployeeStatus()). ","
		. 	Model::safeSql($e->getStatusDate()). ")";
			
		$result = Model::runSql($sql);
		return $result;
	}


	public function getCurrentProject(G_Employee_Project_Site_History $e)
	{
		try {
			
			$sql = "SELECT 
				g_employee_project_site_history.id as psh_id ,
				g_employee_project_site_history.employee_id ,
				g_employee_project_site_history.project_id ,
				g_employee_project_site_history.start_date ,
				g_employee_project_site_history.end_date ,
				g_employee_project_site_history.employee_status ,
				g_employee_project_site_history.status_date ,
				g_project_sites.name as project_name
			 FROM g_employee_project_site_history  INNER JOIN g_project_sites WHERE g_project_sites.id = g_employee_project_site_history.project_id AND  g_employee_project_site_history.end_date = '' AND g_employee_project_site_history.employee_id = ". Model::safeSql($e->getEmployeeId()) . " ORDER BY g_employee_project_site_history.start_date DESC";
			
			$result = Model::runSql($sql,true);
			return $result;

		} catch (Exception $e) {
			echo "Error getCurrentProject: " . $e->getMessage();
		}
	}

	public function getCurrentProjectById(G_Employee_Project_Site_History $e)
	{
		try {
			
			$sql = "SELECT 
				id  ,
				employee_id ,
				project_id ,
				start_date ,
				end_date,
				employee_status,
				status_date
			 FROM g_employee_project_site_history  
			 WHERE 
			   id = ".Model::safeSql($e->getProjectId());
			
			$result = Model::runSql($sql,true);
			return $result;

		} catch (Exception $e) {
			return "Error getCurrentProjectById: " . $e->getMessage();
		}
	}


	public function getProjectSites()
	{
		$sql = "
			SELECT id , name , created_at
			FROM ".G_PROJECT_SITES."
			ORDER BY name ASC
			"; 
		$result = Model::runSql($sql,true);
			return $result;
	}


	 //get previous history
  public static function FindPreviousProjectSiteHistory($e, $date){

     $sql = "
        SELECT
        *
        FROM g_employee_project_site_history e
        WHERE e.employee_id = ". Model::safeSql($e->getId()) ."
        AND e.start_date <= ". Model::safeSql($date) ."
        AND e.end_date >= ". Model::safeSql($date) ."
        ORDER BY e.id DESC LIMIT 1
      ";
      //echo $sql;
     $result = Model::runSql($sql,true);
	 return $result;
    

  }



	public function removeCurrentProject(G_Employee_Project_Site_History $e)
	{
		try {
				$sql ="
					UPDATE g_employee_project_site_history
					SET end_date = ".Model::safeSql($e->getEndDate()).",
					employee_status = ".Model::safeSql($e->getEmployeeStatus()).",
					status_date = ".Model::safeSql($e->getStatusDate())."
					WHERE employee_id = " .Model::safeSql($e->getEmployeeId())."
					AND end_date = '' ";
				 $result = Model::runSql($sql);
				return $result;
		} catch (Exception $e) {
			return "Error removeCurrentProject: ".$e->getMessage();			
		}
	}

	public static function remove_project(G_Employee_Project_Site_History $e){
			try{
				$sql = "
					DELETE FROM g_employee_project_site_history
					WHERE id =" . Model::safeSql($e->getProjectId());
				return Model::runSql($sql);
			}
			catch(Exception $e){
				return "Error remove_project: ".$e->getMessage();		
			}
	
	}

	public function updateThisProjectSite(G_Employee_Project_Site_History $e)
	{
		try {//// project_id = ".Model::safeSql($e->getProjectId())." 
		        
				$sql ="
					UPDATE g_employee_project_site_history
					SET 
					end_date = ".Model::safeSql($e->getEndDate())." ,
					start_date = ".Model::safeSql($e->getStartDate()).",
					employee_status = ".Model::safeSql($e->getEmployeeStatus()).",
					status_date = ".Model::safeSql($e->getStatusDate())."
					
					WHERE id = " .Model::safeSql($e->getProjectId());

				 $result = Model::runSql($sql);
				return $result;
		} catch (Exception $e) {
			return "Error updateThisProjectSite: ".$e->getMessage();			
		}
	}
	//=========================================================================


	public function updateHistoryProject($project_id , $his_id)
	{
		$sql = "UPDATE g_employee_project_site_history 
			SET project_id = ".Model::safeSql($project_id)." WHERE id = " .Model::safeSql($his_id) ;
		Model::runSql($sql);
	}

	public function updateHistoryStartDate($start_date , $his_id)
	{
		$sql = "UPDATE g_employee_project_site_history 
			SET start_date = ".Model::safeSql($start_date)." WHERE id = " .Model::safeSql($his_id) ;
		Model::runSql($sql);
	}

	public function updateHistoryEndDate($end_date , $his_id,$employee_status,$status_date)
	{


		$sql = "UPDATE g_employee_project_site_history 
			SET 
			end_date = ".Model::safeSql($end_date).", 
			employee_status = ".Model::safeSql($employee_status).", 
			status_date = ".Model::safeSql($status_date)." 

			WHERE id = " .Model::safeSql($his_id) ;
		Model::runSql($sql);
	}


	public function checkIfHasCurrentProject(G_Employee_Project_Site_History $e)
	{
		$sql = "SELECT 
		COUNT(id) as total 
		FROM g_employee_project_site_history 
		 WHERE 
		 employee_id = ".Model::safeSql($e->getEmployeeId()) ."
		 AND end_date = '' ";


		 $result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	//================================================================================

	public function getPresentProjectByEmployeeId($employee_id)
	{
			$sql = "SELECT 
					id  ,
					employee_id ,
					project_id ,
					start_date ,
					end_date,
					employee_status,
					status_date
				 FROM g_employee_project_site_history  
				 WHERE employee_id = ".Model::safeSql($employee_id) . " ORDER BY start_date DESC LIMIT 1" ;
			
			$result = Model::runSql($sql,true);
			return $result[0];
	}

	public function getProjectHistoryByEmployeeId($employee_id)
	{
		$sql = "SELECT 
				g_employee_project_site_history.id ,
				g_employee_project_site_history.employee_id ,
				g_employee_project_site_history.project_id ,
				g_employee_project_site_history.start_date ,
				g_employee_project_site_history.end_date ,
				g_employee_project_site_history.employee_status ,
				g_employee_project_site_history.status_date ,
				g_project_sites.name as project_name
			 FROM g_employee_project_site_history  INNER JOIN g_project_sites WHERE g_project_sites.id = g_employee_project_site_history.project_id AND   g_employee_project_site_history.employee_id = ".  Model::safeSql($employee_id) . " ORDER BY start_date DESC";

			$result = Model::runSql($sql,true);
			return $result;
	}



	public static function updateProjectHistoryEndDate(G_Employee_Project_Site_History $e,$id,$end_date){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($end_date). "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId())  ." AND id = " .$id;
		Model::runSql($sql);


	}



	public function changeCurrentProject(){
		

			$sql = "SELECT * FROM g_employee_project_site_history";
			$sql .=" WHERE id = ".Model::safeSql($e->getProjectId()) ;

			$result = Model::runSql($sql,true);


		
	}

	public static function save(G_Employee_Project_Site_History $e) {
		if (G_Employee_Job_History_Helper::isIdExist($e) > 0 ) {
			$sql_start = "UPDATE ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "WHERE id = ". Model::safeSql($e->getId());		
		}else{
			$sql_start = "INSERT INTO ". G_EMPLOYEE_JOB_HISTORY . "";
			$sql_end   = "";		
		}
		
		$sql = $sql_start ."
			SET
			employee_id			= " . Model::safeSql($e->getEmployeeId()) .",
			job_id				= " . Model::safeSql($e->getJobId()) .",
			name				= " . Model::safeSql($e->getName()) .",
			employee_status	= " . Model::safeSql($e->getEmploymentStatus()) .",
			start_date			= " . Model::safeSql($e->getStartDate()) .",
			end_date			= " . Model::safeSql($e->getEndDate()) ."
			 "
		
			. $sql_end ."	
		
		";	
		//echo $sql;
		Model::runSql($sql);
		return mysql_insert_id();		
	}


	
	public static function resetEmployeeDefaultJob(G_Employee_Project_Site_History $e){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId());
		Model::runSql($sql);
	}

		public static function resetEmployeeByJobHistoryId(G_Employee_Project_Site_History $e,$id){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($e->getEndDate()) . "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId())  ." AND id = " .$id;
		Model::runSql($sql);


	}

	public static function updateJobHistoryEndDate(G_Employee_Project_Site_History $e,$id,$end_date){
		$sql = "
			UPDATE ". G_EMPLOYEE_JOB_HISTORY ."
			SET end_date =" . Model::safeSql($end_date). "
			WHERE employee_id =" . Model::safeSql($e->getEmployeeId())  ." AND id = " .$id;
		Model::runSql($sql);


	}
		
	public static function delete(G_Employee_Project_Site_History $e){
		if(G_Employee_Job_History_Helper::isIdExist($e) > 0){
			$sql = "
				DELETE FROM ". G_EMPLOYEE_JOB_HISTORY ."
				WHERE id =" . Model::safeSql($e->getId());
			Model::runSql($sql);
		}
	
	}
}
?>