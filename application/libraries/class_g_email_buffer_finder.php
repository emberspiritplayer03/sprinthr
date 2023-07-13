<?php
class G_Email_Buffer_Finder {
	public static function findById($id) {
		$sql = "
			SELECT * 
			FROM " . EMAIL_BUFFER . "
			WHERE id =". Model::safeSql($id) ."
			LIMIT 1
		";
		
		return self::getRecord($sql);
	}

	public static function findAll($sort="",$limit="") {
		$sql = "
			SELECT *
			FROM " . EMAIL_BUFFER . "
			" . $sort  . "
			" . $limit . "
		";
		return self::getRecords($sql);
	}
	
	public static function findAllEmailsNotSent($sort="",$limit="") {
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';
		
		$sql = "
			SELECT e.id, e.sent_from, e.email_address, e.sent_name, e.subject, e.message, e.attachment
			FROM " . EMAIL_BUFFER . " e
			WHERE is_sent = " . Model::safeSql(G_Email_Buffer::NO) . " AND
			is_archive = " . Model::safeSql(G_Email_Buffer::NO) . "
			".$order_by."
			".$limit."
		";
		return self::getRecords($sql);
	}
	
	private static function getRecord($sql) {

		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
		if ($total == 0) {
			return false;	
		}		
	
		$row = Model::fetchAssoc($result);
		$records = self::newObject($row);	
		return $records;
	}
	
	private static function getRecords($sql) {

		$result = Model::runSql($sql);
		$total = mysql_num_rows($result);
	
		if ($total == 0) {
			
			return false;	

		}
		while ($row = Model::fetchAssoc($result)) {

			$records[$row['id']] = self::newObject($row);
		}
		return $records;
	}

	private static function newObject($row) {
		$pt = new G_Email_Buffer($row['id']);
		$pt->setId($row['id']);
		$pt->setFrom($row['sent_from']);
		$pt->setEmailAddress($row['email_address']);
		$pt->setName($row['sent_name']);
		$pt->setSubject($row['subject']);
		$pt->setMessage($row['message']);
		$pt->setAttachment($row['attachment']);
		$pt->setIsSent($row['is_sent']);
		$pt->setIsArchive($row['is_archive']);
		$pt->setErrorMessage($row['error_message']);
		$pt->setDateAdded($row['date_added']);
		return $pt;
	}
}
?>