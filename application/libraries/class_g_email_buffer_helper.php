<?php

class G_Email_Buffer_Helper {
	public static function isIdExist(Gl_Email_Buffer $pt) {
		$sql = "
			SELECT COUNT(*) as total
			FROM  " . EMAIL_BUFFER . "
			WHERE id = ". Model::safeSql($pt->getId()) ."
		";

		$result = Model::runSql($sql);
		$row = Model::fetchAssoc($result);
		return $row['total'];
	}
}

?>