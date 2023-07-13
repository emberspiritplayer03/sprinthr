<?php
class Holiday {
	const LEGAL = 1;
	const SPECIAL = 2;
	
	protected $id;
	protected $public_id;
	protected $title;
	protected $month; 	// 1 = jan, 2 = feb, etc..
	protected $day; 	// 1 to 31
	protected $type; 	// G_Holiday::LEGAL, G_Holiday::SPECIAL
	protected $type_name; // 'Legal', 'Special'
    protected $year;
		
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}

    public function setYear($value) {
        $this->year = $value;
    }

    public function getYear() {
        return $this->year;
    }
	
	public function setPublicId($value) {
		$this->public_id = $value;	
	}
	
	public function getPublicId() {
		return $this->public_id;	
	}	
	
	public function setTitle($value) {
		$this->title = $value;
	}
	
	public function getTitle() {
		return $this->title;	
	}
	
	public function setMonth($value) {
		$this->month = $value;	
	}
	
	public function getMonth() {
		return $this->month;	
	}
	
	public function setDay($value) {
		$this->day = $value;	
	}
	
	public function getDay() {
		return $this->day;	
	}

    public function isLegal() {
        if ($this->type == self::LEGAL) {
            return true;
        } else {
           return false;
        }
    }

    public function isSpecial() {
        if ($this->type == self::SPECIAL) {
            return true;
        } else {
            return false;
        }
    }
	
	public function setType($value) {
		$this->type = $value;
		if ($this->type == self::LEGAL) {
			$this->setTypeName('Legal');
		} else if ($this->type == self::SPECIAL) {
			$this->setTypeName('Special');
		}
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setTypeName($value) {
		$this->type_name = $value;	
	}
	
	public function getTypeName() {
		return $this->type_name;	
	}
	
	/*
		$h = new G_Holiday();
		$h->setTitle('Rizal Day');
		$h->setMonth(12);
		$h->setDay(31);
		$h->setType(G_Holiday::SPECIAL);
		$h->save();	
	*/
	public function save() {
		return G_Holiday_Manager::save($this);
	}
	
	/*
		$h = G_Holiday_Finder::findById(3);
		$h->delete();	
	*/
	public function delete() {
		return G_Holiday_Manager::delete($this);	
	}
}
?>