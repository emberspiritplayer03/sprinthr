<?php
class G_Employee_Dynamic_Field {
	
	public $id;	
	public $employee_id;
	public $title;
	public $value;
	public $screen;


	
	function __construct($id) {
		$this->id = $id;
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;
	}

	/*
		Usage : 
		$data = array(
			2 => array(
				"Employee Category" => "Agency",
				"another category" => "Sample Category 01A"
			),
			3 => array(
				"employee category" => "Direct",
				"Another Category" => "Sample Category 02"
			)
		);

		foreach( $data as $key => $value ){
            foreach( $value as $subKey => $subValue ){                                    
                $obj[$key] = array($subKey => $subValue);                          
                $ed = new G_Employee_Dynamic_Field();
                $return = $ed->setObjectFields($obj); //Returns object
            }                                
        }
	*/
	public function setObjectFields( $data = array() ) {
		foreach( $data as $key => $value ){
			$this->employee_id = $key;
			foreach( $value as $subKey => $subValue ){
				$this->title = $subKey;
				$this->value = $subValue;
			}			
		}
		return $this;
	}

	public function getLabelValidOptions( $label = '' ) {
		$options  = array();

		if( !empty($label) ){
			$fields = array("value");
			$data   = G_Employee_Dynamic_Field_Helper::sqlAllDataByTitle($label, $fields);			
			if( !empty($data) ){
				foreach( $data as $value ){
					$options[] = $value['value'];
				}
			}
		}
		
		return $options;
	} 

	public function sanitizeObjectValue() {
		$obj = $this;
		foreach( $obj as $key => $value ){			
			if( $obj <> "id" ){
				$this->{$key} = trim(ucwords($value));
			}
		}

		return $this;
	}

	public function createDynamicField() {
		$return['is_success'] = false;
		$return['message']    = 'Cannot save record';

		if( !empty($this->employee_id) && !empty($this->title) && !empty($this->value) ){
			//Validate if label and value already exists for employee - do not save if exists
			$is_exists = G_Employee_Dynamic_Field_Helper::sqlIsLabelAndValueExistsByEmployeeId($this->employee_id, $this->title, $this->value);
			if( !$is_exists ){
				self::save();
			}
			
			$return['is_success'] = true;
			$return['message']    = 'Record saved';
		}

		return $return;
	}

	public function bulkInsertDynamicField( $data = array() ) {
		if( !empty($data) ){
			G_Employee_Dynamic_Field_Manager::bulkInsertDynamicField($data);
			
		}
	}

	public function deleteAllByEmployeeId(){		
		if( $this->employee_id > 0 ){
			G_Employee_Dynamic_Field_Manager::deleteAllByEmployeeId($this->employee_id);
		}
		return $this;
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;
	}
	
	public function setTitle($value) {
		$this->title = $value;	
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setValue($value) {
		$this->value = $value;	
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setScreen($value) {
		$this->screen = $value;	
	}
	
	public function getScreen() {
		return $this->screen;
	}
	
	
		
	public function save() {
		return G_Employee_Dynamic_Field_Manager::save($this);
	}
	
	public function delete() {
		return G_Employee_Dynamic_Field_Manager::delete($this);
	}
}

?>