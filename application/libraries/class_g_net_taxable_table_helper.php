<?php
class G_Net_Taxable_Table_Helper {

    public static function isIdExist(G_Request $gr) {
		$sql = "
			SELECT COUNT(id) as total
			FROM " . NET_TAXABLE_TABLE ."
			WHERE id = ". Model::safeSql($gr->getId()) ."
		";
		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
	
	public static function sqlTaxableCompensationDataByNetCompensation($net_compensation = 0) {
		$sql = "
			SELECT over, not_over, amount, rate_percentage, excess_over
			FROM " . NET_TAXABLE_TABLE . "
			WHERE over <= " . Model::safeSql($net_compensation) . "
				AND not_over >= " . Model::safeSql($net_compensation) . "

		";				
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;
	}
}
?>