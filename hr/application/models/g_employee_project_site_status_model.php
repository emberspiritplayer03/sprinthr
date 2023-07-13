<?php
    class G_Employee_Project_Site_Status_Model extends Model{

    	  public function update_employee_project_site_history($employee_id, $projectId){
              
                try {
			
			        $upd  = "UPDATE G_EMPLOYEE SET ";
					
					$upd .= " project_site_id = " . Model::safeSql($projectId);
					
					$upd .= " WHERE id = " . Model::safeSql($employee_id);
										
					Model::runSql($upd);
				
				} catch (Exception $e) {
					echo "Error setProjectHistory: " . $e->getMessage();
				}
               	
    	  }
    }


?>