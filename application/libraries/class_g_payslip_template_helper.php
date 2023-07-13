<?php
class G_Payslip_Template_Helper {
		
	public static function isIdExist(G_Leave $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_PAYSLIP_TEMPLATE ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function defaultTemplate()
	{
		$sql = "
			SELECT id, template_name
			FROM " . G_PAYSLIP_TEMPLATE ."
			WHERE is_default = ". Model::safeSql(G_Payslip_Template::IS_DEFAULT_YES) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row;		
	}


}
?>