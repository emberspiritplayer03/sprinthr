<?php
	class G_Project_Site {

		protected $id;
		protected $name;
		protected $location;
		protected $startDate;
		protected $endDate;
		protected $description;
		protected $createdAt;

		private $manager;

		public function __construct()
		{
			$this->manager = new G_Project_Site_Manager($this);
		}

	// Setter
	// 
	public function setId($id)
	{
		$this->id = $id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setLocation($location)
	{
		$this->location = $location;
	}

	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
	}

	public function setEndDate($endDate)
	{
		$this->endDate = $endDate;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	}	

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}



		// CRUD
	// 
	// 
	public function init()
	{
		return new G_Project_Site();
	}

	public function create($attributes)
	{	
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->create($attributes);
	}

	public function update($id, $attributes)
	{
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->update($id, $attributes);
	}

	public function delete($id)
	{
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->delete($id);
	}

	public function find($id)
	{	
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->find($id);
	}


	public function findByName($name)
	{	
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->findByName($name);
	}


		public function manager()
		{
			return $this->manager;
		}

		
		public function all()
		{
		if(!$this->manager)
		{
			$this->manager = (new G_Project_Site())->manager(); 
		}
		return $this->manager->all();
		}


		public static function findProjectSites() {
			
			$sql = "
			SELECT id , name , created_at, description
			FROM ".G_PROJECT_SITES."
			ORDER BY name ASC
			"; 
			//echo $sql;
			return self::getRecords($sql);
		}
		
		private static function getRecords($sql) {
			$result = Model::runSql($sql);
			$total = mysql_num_rows($result);
			if ($total == 0) {
				return false;	
			}
			while ($row = Model::fetchAssoc($result)) {
				//$records[$row['id']] = self::newObject($row);
				$records[] = $row;
			}
			return $records;
		}
		
		private static function newObject($row) {
			$g = new G_Job($row['id']);
			$g->setId($row['id']);
			$g->setCompanyStructureId($row['company_structure_id']);
			$g->setJobSpecificationId($row['job_specification_id']);	
			$g->setTitle($row['title']);		
			$g->setIsActive($row['is_active']);

			return $g;
		}

		public static function insert_project_site(G_Project_Site_Extends $e){
			$timestamp = date("Y-m-d H:i:s");
			try {
					$sql = "INSERT INTO g_project_sites (name , location,start_date , end_date, description,device_id,created_at) VALUES ("
				. 	Model::safeSql($e->getprojectname()). " ,"
				. 	Model::safeSql($e->getlocation()). " ,"
				. 	Model::safeSql($e->getStart_date()) . ","
				. 	Model::safeSql($e->getEnd_date()) . ","
				. 	Model::safeSql($e->getProjectDescription()) . ","
				. 	Model::safeSql($e->getDeviceId()) . ","
				. 	Model::safeSql($timestamp) . ")";

				$result = Model::runSql($sql);
				return $result;

			} catch (Exception $e) {
					echo "Error setProjectHistory: " . $e->getMessage();
				}

		}

		public static function findAllProjectSite($csid='', $order_by = '', $limit = '') {
		
				$order_by = ($order_by != '') ? 'ORDER BY ' . $order_by : '';
				$limit = ($limit!='')? 'LIMIT ' . $limit : '';
				$where = ($csid != '') ? 'WHERE id = ' . Model::safeSql($csid) : '';
				
				$sql = "
					SELECT id,name,location,description, device_id
					FROM " .G_PROJECT_SITES." 
					".$where."	
					".$order_by."
					".$limit."		
				";		

				return self::getRecords($sql);
	    }

	    public static function updateProjectSite(G_Project_Site_Extends $e){
	    	    //count if exist not done
	    	    $sql_start = "UPDATE " .G_PROJECT_SITES. " ";
                $sql_end   = " WHERE id = " . Model::safeSql($e->getId());
	    	    $sql = $sql_start . "
					SET
						name        =". Model::safeSql($e->getprojectname()).", 
						location    =". Model::safeSql($e->getlocation()).",
						device_id    =". Model::safeSql($e->getDeviceId()).",
						description =". Model::safeSql($e->getProjectDescription()). 
						$sql_end;
		        Model::runSql($sql);
		        return mysql_insert_id();
	    }
  

		
	
	
}