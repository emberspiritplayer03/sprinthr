<?php
class G_Activity_Category_Finder {

    public static function findAllCategories($order_by = '', $limit = '') {
		
		$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
		$limit = ($limit!='')? 'LIMIT ' . $limit : '';

        $sql = "
			SELECT ac.id, ac.activity_category_name, ac.activity_category_description
			FROM ". G_ACTIVITY_CATEGORY ." as ac
			".$order_by."
			".$limit."		
		";
        return self::getRecords($sql);
    }

    public static function findById($id) {
        $sql = "
			SELECT ac.id, ac.activity_category_name, ac.activity_category_description
			FROM ". G_ACTIVITY_CATEGORY ." as ac
			WHERE ac.id =". Model::safeSql($id) ."
			LIMIT 1
		";
        return self::getRecord($sql);
    }

    public static function findByName($name, $where_array = array()) {
        $sql = "
			SELECT ac.id, ac.activity_category_name, ac.activity_category_description
			FROM ". G_ACTIVITY_CATEGORY ." as ac
			WHERE UPPER(ac.activity_category_name) = ". strtoupper(Model::safeSql(trim($name))) ."
		";

		if (count($where_array) > 0) {
			for ($i=0; $i < count($where_array); $i++) { 
				$sql = $sql . ' AND ac.' . $where_array[$i]['column'] . ' ' . $where_array[$i]['operator'] . ' ' . Model::safeSql($where_array[$i]['value']);
			}
		}

		$sql = $sql . " LIMIT 1";
		
        return self::getRecord($sql);
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
		$s = new G_Activity_Category;
		$s->setId($row['id']);
		$s->setActivityCategoryName($row['activity_category_name']);
		$s->setActivityCategoryDescription($row['activity_category_description']);
        $s->setDateCreated($row['date_created']);
		return $s;
	}

}
?>