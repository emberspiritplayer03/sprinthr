<?php
class G_Activity_Skills_Helper {

	public static function isIdExist(G_Activity_Skills $model) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_ACTIVITY_SKILLS ."
			WHERE id = ". Model::safeSql($model->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function getEmployeeSkillNameById($id){
			$sql = "
				SELECT activity_skills_name
				FROM " . G_ACTIVITY_SKILLS ."
				WHERE id = ".$id."
			";
			$result = Model::runSql($sql);
			$row    = Model::fetchAssoc($result);
			return $row;

	}

}
?>