<?php


class G_Project_Site_Manager 
{   

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function model()
    {
        return $this->model;
    }

    public function create(array $attributes)
    {
        $object = $this->model->init();

        $object->setName($attributes['name']);
        $object->setLocation($attributes['location']);
        $object->setStartDate($attributes['start_date']);
        $object->setEndDate($attributes['end_date']);
        $object->setDescription($attributes['description']);
        $object->setCreatedAt( (new DateTime())->setTimezone(new DateTimeZone('Asia/Manila'))->format('Y-m-d H:i:s'));

        if(isset($attributes['name']) && !empty($attributes['name']))
        {

            $sql = "
                INSERT INTO ".PROJECT_SITE."
                    (   
                        name, 
                        location, 
                        start_date,
                        end_date,
                        description,
                        created_at
                    )
                VALUES 
                    (
                        ".Model::safeSql($object->getName()).",
                        ".Model::safeSql($object->getLocation()).",
                        ".Model::safeSql($object->getStartDate()).",
                        ".Model::safeSql($object->getEndDate()).",
                        ".Model::safeSql($object->getDescription()).",
                        ".Model::safeSql($object->getCreatedAt())."
                    )

                ON DUPLICATE KEY UPDATE
                    name = VALUES(name),
                    location = VALUES(location),
                    start_date = VALUES(start_date),
                    end_date = VALUES(end_date),
                    description = VALUES(description)
            ";
            
            Model::runSql($sql);

            if (mysql_errno() > 0) 
            {
                return (object)['error' => ['message' => mysql_error(), 'count' => mysql_errno() ]];
            }

            $object->setName(mysql_insert_id());

            return $object;
        }

        return (object)['error' => ['message' => 'Name is required!']];
    }

    public function save()
    {
        
    }   

    public function update($id, $attributes)
    {

    }

    public function delete($id = null)
    {
        if(!$id && $this->model)
        {
            $id = $this->model->getId();
        }

        $sql = "DELETE FROM ".PROJECT_SITE."  WHERE id = $id" ;

        Model::runSql($sql);

        if (mysql_errno() > 0) 
        {
            // var_dump(mysql_errno());
            // var_dump(mysql_error()); exit;
            return false;
        }
        
         return true;
     
    }

    public function find($id)
    {
         $sql = "
            SELECT 
                id,
                name,
                location,
                start_date,
                end_date,
                description,
                created_at
            FROM 
            ". PROJECT_SITE .
            "
            WHERE id = ".Model::safeSql($id)."
            LIMIT 1
            ";

        Model::runSql($sql);

        if (mysql_errno() > 0) 
        {
            return (object)['error' => ['message' => mysql_error(), 'count' => mysql_errno() ]];
        }
        
        $result = Model::runSql($sql);

        $total = mysql_num_rows($result);

        if($total == 0)
        {
            return null;
        }

        $model = (object)Model::fetchAssoc($result);

        $object = $this->model->init();

        $object->setId($model->id);
        $object->setName($model->name);
        $object->setLocation($model->location);
        $object->setStartDate($model->start_date);
        $object->setEndDate($model->end_date);
        $object->setDescription($model->description);
        $object->setCreatedAt($model->created_at);

        return $object;
    }


     public function findByName($name)
    {
         $sql = "
            SELECT 
                id,
                name,
                location,
                start_date,
                end_date,
                description,
                created_at
            FROM 
            ". PROJECT_SITE .
            "
            WHERE name = ".Model::safeSql($name)."
            LIMIT 1
            ";

        Model::runSql($sql);

        if (mysql_errno() > 0) 
        {
            return (object)['error' => ['message' => mysql_error(), 'count' => mysql_errno() ]];
        }
        
        $result = Model::runSql($sql);

        $total = mysql_num_rows($result);

        if($total == 0)
        {
            return null;
        }

        $model = (object)Model::fetchAssoc($result);

        $object = $this->model->init();

        $object->setId($model->id);
        $object->setName($model->name);
        $object->setLocation($model->location);
        $object->setStartDate($model->start_date);
        $object->setEndDate($model->end_date);
        $object->setDescription($model->description);
        $object->setCreatedAt($model->created_at);

        return $object;
    }


    public function all()
    {
         $sql = "
            SELECT 
                id,
                name,
                location,
                start_date,
                end_date,
                description,
                created_at
            FROM 
            ". PROJECT_SITE;

        Model::runSql($sql);

        if (mysql_errno() > 0) 
        {
            return (object)['error' => ['message' => mysql_error(), 'count' => mysql_errno() ]];
        }
        
        $result = Model::runSql($sql);

        $collection = [];

        while ($model = Model::fetchAssoc($result)) 
        {
            $model = (object)$model;

            $object = $this->model->init();

            $object->setId($model->id);
            $object->setName($model->name);
            $object->setLocation($model->location);
            $object->setStartDate($model->start_date);
            $object->setEndDate($model->end_date);
            $object->setDescription($model->description);
            $object->setCreatedAt($model->created_at);

            $collection[] = $object;    
        }

        return $collection;

    }

    public function fetchCostCenterToProjectSite()
    {
        $sql = "
            SELECT DISTINCT
                cost_center
            FROM 
            ". EMPLOYEE;

        $result = Model::runSql($sql);
        $cost_centers = [];
        while ($model = Model::fetchAssoc($result)) 
        {
            $model = (object)$model;
            $cost_centers[] = $model;
        }

        foreach ($cost_centers as $key => $item) 
        {
            $model = $this->create(['name' => $item->cost_center]); 
        }

        foreach($this->all() as $model)
        {
             $emp_sql = 
            "
                UPDATE ".EMPLOYEE."
                SET
                `project_site_id` = ".Model::safeSql($model->getId())."
                WHERE `cost_center` = ".Model::safeSql($model->getName())."
            ";
             Model::runSql($emp_sql);
        }
        // Model::runSql($sql);

        return json_encode(['sucess' => true, 'cost_centers' => $cost_centers, 'total_fetch' => count($cost_centers)], true);
    }

}
