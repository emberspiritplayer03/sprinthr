<?php
class G_Activity_Category_Helper {

	public static function isIdExist(G_Activity_Category $model) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_ACTIVITY_CATEGORY ."
			WHERE id = ". Model::safeSql($model->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

		public static function getEmployeeCategoryNameById($id){
			$sql = "
				SELECT activity_category_name
				FROM " . G_ACTIVITY_CATEGORY ."
				WHERE id = ".$id."
			";
			$result = Model::runSql($sql);
			$row    = Model::fetchAssoc($result);
			return $row;

	}

}
?>