<?php
class G_Group_Restday extends Group_Restday {	
	public $a_rest_day = array();
	protected $b_is_valid = false;

	public function __construct() {
		
	}

	public function getAllDefaultRestDay() {
		$fields   = array("date");
		$default_group_id = G_Company_Structure::PARENT_ID;
		$data     		  = G_Group_Restday_Helper::sqlGetRestDayByGroupId($default_group_id, $fields);
		$this->a_rest_day = $data;

		return $this;
	}

	public function saveRestDaysToDepartment() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save data!';

		if( !empty($this->a_rest_day) && $this->group_id > 0 ){			
			$is_group_department_exists = G_Company_Structure_Helper::sqlIsIdExist($this->group_id);
			if( $is_group_department_exists ){
				$save_data = array();
				$employees = G_Employee_Finder::findAllEmployeesByDepartmentSectionId($this->group_id);
				
				foreach( $this->a_rest_day as $value ){		
					$s_date = date("Y-m-d",strtotime($value['date']));

					$is_department_date_exists = G_Group_Restday_Helper::sqlIsDateAndGroupIdExists($s_date, $this->group_id);		
					if( !$is_department_date_exists ){
						$save_data[] = "(" . Model::safeSql($this->group_id) . "," . Model::safeSql($s_date)  . ")";

						//Save to employee restday
						foreach( $employees as $e ){
							$rd = new G_Restday();
							$rd->setEmployeeId($e->getId())->getAllDefaultRestDay()->convertArrayToObject()->saveDefaultRestDays();
						}
					}

				}

				if( !empty( $save_data ) ){
					G_Group_Restday_Manager::saveMultiple($save_data);		
					$return['is_success'] = true;
					$return['message']    = 'Default restday was successfully copied to this schedule';
				}else{
					$return['message']    = 'Restday from default already exists in this schedule';
				}			
			}else{
				$return['message']    = 'Group / Department does not exists!';
			}
		}

		return $return;
	}
							
	public function save() {
		return G_Group_Restday_Manager::save($this);
	}

	public function delete() {
		return G_Group_Restday_Manager::delete($this);
	}
}
?>