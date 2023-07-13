<?php
      class G_Employee_Project_Site_Model extends Model{

            public function findProjectSitesIfExist($project_id){
           	      $sql    = "SELECT COUNT(*) as total FROM G_EMPLOYEE_PROJECT_SITE_HISTORY WHERE project_id=$project_id";
                  $result = Model::runSql($sql);
                  $row    = Model::fetchAssoc($result);
                  return $row['total'];
            }

            public function deleteProjectSite($id){
                 $sql    = "DELETE FROM G_PROJECT_SITES WHERE id=".Model::safeSql($id);                  
                 $result = Model::runSql($sql);
                 return $result;
            }

            public function countEmployeeProjectSiteHistory($employee_id){
                  $sql    = "SELECT COUNT(*) as total FROM G_EMPLOYEE_PROJECT_SITE_HISTORY WHERE employee_id=$employee_id";
                  $result = Model::runSql($sql);
                  $row    = Model::fetchAssoc($result);
                  return $row['total'];
            }

            public function getMaxCurrentDateProject($employee_id){
                  $sql    = "SELECT G.name,GP.start_date, GP.end_date, max(GP.end_date) as max_date FROM G_Project_Sites G INNER JOIN G_EMPLOYEE_PROJECT_SITE_HISTORY GP ON G.ID = GP.PROJECT_ID WHERE employee_id=$employee_id";
                  $result = Model::runSql($sql,true);
                  return $result;
            }

      }


?>