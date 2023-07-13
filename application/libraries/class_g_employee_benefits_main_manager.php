<?php
class G_Employee_Benefits_Main_Manager {

        public static function save(G_Employee_Benefits_Main $gebm) {
        if (G_Employee_Benefits_Main_Helper::isIdExist($gebm) > 0) {
            $sql_start = "UPDATE " . G_EMPLOYEE_BENEFITS_MAIN . " ";
            $sql_end   = "WHERE id = " . Model::safeSql($gebm->getId());
            $is_insert = false;
        } else {
            $sql_start = "INSERT INTO " . G_EMPLOYEE_BENEFITS_MAIN . " ";
            $sql_end   = " ";
            $is_insert = true;
        }

        $sql = $sql_start . "

			SET
				company_structure_id   =" . Model::safeSql($gebm->getCompanyStructureId()) . ",
				employee_department_id =" . Model::safeSql($gebm->getEmployeeDepartmentId()) . ",			
                benefit_id             =" . Model::safeSql($gebm->getBenefitId()) . ",      
                description            =" . Model::safeSql($gebm->getDescription()) . ",  
                criteria               =" . Model::safeSql($gebm->getCriteria()) . ",    
                custom_criteria        =" . Model::safeSql($gebm->getCustomCriteria()) . ",    
                applied_to             =" . Model::safeSql($gebm->getAppliedTo()) . " "
                . $sql_end . "	
		";
        
        Model::runSql($sql);

        if( $is_insert ){
            $return = mysql_insert_id();
        }else{
            $return = $gebm->getId();
        }

        return $return;
    }

    public static function bulkInsertData( $a_bulk_insert = array(), $fields = array() ) {
        $return = false;       
        if( !empty( $a_bulk_insert ) ){
            $sql_values = implode(",", $a_bulk_insert);

            if( !empty($fields) ){
                $sql_fields = implode(",", $fields);
            }else{
                $sql_fields = "company_structure_id,employee_department_id,benefit_id,description,applied_to,criteria,custom_criteria, excluded_emplooyee_id";
            }

            $sql        = "
                INSERT INTO " . G_EMPLOYEE_BENEFITS_MAIN . "({$sql_fields})
                VALUES{$sql_values}
            ";       
            Model::runSql($sql);
            $return = true;
        }
        return $return;
    }

    public static function bulkEnrollToBenefit(G_Employee_Benefits_Main $gebm, $employee_ids = array()) {
        $return = false;

        if( !empty($gebm) && !empty($employee_ids) ){

            foreach($employee_ids as $id){
                $values[] = "(" . Model::safeSql($gebm->getCompanyStructureId()) . "," . Model::safeSql($id) . "," . Model::safeSql($gebm->getBenefitId()) . "," . Model::safeSql($gebm->getAppliedTo()) . ")";
            }
            $sql_values = implode(",", $values);
            $sql        = "
                INSERT INTO " . G_EMPLOYEE_BENEFITS_MAIN . "(company_structure_id,employee_department_id,benefit_id,applied_to)
                VALUES{$sql_values}
            ";

            Model::runSql($sql);
            $return = true;
        }

        return $return;
    }

    public static function deleteAllEnrolledEmployeesByBenefitId( $benefit_id = 0 ){
        $sql = "
            DELETE FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
            WHERE benefit_id =" . Model::safeSql($benefit_id);
        Model::runSql($sql);
    }

    public static function delete(G_Employee_Benefits_Main $gebm) {
        if (G_Employee_Benefits_Main_Helper::isIdExist($gebm) > 0) {
            $sql = "
				DELETE FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
				WHERE id =" . Model::safeSql($gebm->getId());
            Model::runSql($sql);
        }
    }

    public static function deleteAllByIds( $ids = '' ) {
        if ( !empty($ids) ) {
            $sql = "
                DELETE FROM " . G_EMPLOYEE_BENEFITS_MAIN . "
                WHERE id IN({$ids})";            
            Model::runSql($sql);
        }
    }
}
?>