<?php
class G_Company_Info_Manager {
	public static function save(G_Company_Info $gci, G_Company_Structure $gcs) {
		if (G_Company_Info_Helper::isCompanyStructureIdExists($gcs) > 0 ) {
			$sql_start = "UPDATE ". COMPANY_INFO . " ";
			$sql_end   = "WHERE company_structure_id = ". Model::safeSql($gcs->getId());		
		}else{
			$sql_start = "INSERT INTO ". COMPANY_INFO . " ";
			$sql_end  = "";		
		}
		
		$sql = $sql_start ."
			SET
			company_structure_id = " . Model::safeSql($gcs->getId()) . ",
			address              = " . Model::safeSql($gci->getAddress()) . ",
			phone                = " . Model::safeSql($gci->getPhone()) . ",
			fax                  = " . Model::safeSql($gci->getFax()) . ",
			address1             = " . Model::safeSql($gci->getAddress1()) . ",
			city                 = " . Model::safeSql($gci->getCity()) . ",													
			state                = " . Model::safeSql($gci->getState()) . ",				
			zip_code             = " . Model::safeSql($gci->getZipCode()) . ",
			sss_number           = " . Model::safeSql($gci->getSssNumber()) . ",				
			tin_number           = " . Model::safeSql($gci->getTinNumber()) . ",
			philhealth_number    = " . Model::safeSql($gci->getPhilhealthNumber()) . ",
			pagibig_number	     = " . Model::safeSql($gci->getPagibigNumber()) . ",			
			remarks              = " . Model::safeSql($gci->getRemarks()) . " "			
			. $sql_end ."	
		
		";		
		Model::runSql($sql);
		return mysql_insert_id();		
	}
		
	public static function delete(G_Company_Structure $gci){
		if(G_Company_Info_Helper::isIdExist($gci) > 0){
			$sql = "
				DELETE FROM ". COMPANY_INFO ."
				WHERE id =" . Model::safeSql($gci->getId());
			Model::runSql($sql);
		}	
	}
}
?>