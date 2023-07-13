<?php
class G_Pagibig_Table_Helper {
	public static function isIdExist(G_Pagibig_Table $gpt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PAGIBIG ."
			WHERE id = ". Model::safeSql($gpt->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
}
?>