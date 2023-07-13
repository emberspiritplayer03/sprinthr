<?php
class G_Tax_Table_Helper {
	public static function isIdExist(G_Tax_Table $gtt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_TAX_TABLE ."
			WHERE id = ". Model::safeSql($gtt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>