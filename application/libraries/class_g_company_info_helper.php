<?php
class G_Company_Info_Helper {
    public static function isIdExist(G_Company_Info $gci) {
        $sql = "
            SELECT COUNT(*) as total
            FROM " . COMPANY_INFO ."
            WHERE id = ". Model::safeSql($gci->getId()) ."
        ";
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }

    public static function sqlDataById( $id = 0 ){
        
       $sql = "
           SELECT i.address, i.phone, i.fax, i.state, i.zip_code, i.tin_number, i.sss_number, i.philhealth_number, i.pagibig_number,
               c.title
           FROM " . COMPANY_INFO . " i 
               LEFT JOIN " . COMPANY_STRUCTURE . " c ON i.company_structure_id = c.id
           WHERE c.id = " . Model::safeSql($id) . "                
           ORDER BY i.id DESC
           LIMIT 1
       ";
       
       $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row;
   }

    public static function isCompanyStructureIdExists(G_Company_Structure $gcs) {
        $sql = "
            SELECT COUNT(*) as total
            FROM " . COMPANY_INFO ."
            WHERE company_structure_id = ". Model::safeSql($gcs->getId()) ."
        ";        
        $result = Model::runSql($sql);
        $row    = Model::fetchAssoc($result);
        return $row['total'];
    }
}
?>