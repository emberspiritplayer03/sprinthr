<?php

/*
    USAGE 
    $sql = "SELECT * from table_name";
        
    $a = new Mysqli_Connect();
    $result = $a->query($sql,true);
    Utilities::displayArray($result);
 */

class Mysqli_Connect {
    private static $mysqli;

    function __construct($remote = '') {
        if( $remote == 'local' ){
            self::$mysqli = new mysqli(
                DB_HOST,
                DB_USERNAME,
                DB_PASSWORD,
                DB_DATABASE
            ); 
        }else{
        	self::$mysqli = new mysqli(
                REMOTE_DB_HOST,
                REMOTE_DB_USERNAME,
                REMOTE_DB_PASSWORD,
                REMOTE_DB_DATABASE
            ); 
        }
    }

    public function is_connected() {
        if(!self::$mysqli->connect_errno) {
            return true;
        }else{
            return false;
        }
    }

    public function getLastInsertedId() {
        return self::$mysqli->insert_id;
    }

    public function query($sql = "", $is_array = false) {
    	if(!self::$mysqli->connect_errno) {
            $data = self::$mysqli->query($sql);
            if($is_array){
                return self::fetchArray($data);
            }else{
               //return self::$mysqli->insert_id;
               return $data;
            }
		    
    	}else{
            echo "Failed to connect to MySQL: (" . self::$mysqli->connect_errno . ") " . self::$mysqli->connect_error;
        }
    }

    public function fetchArray($result) {
        if($result) {
            while($row = $result->fetch_assoc()) {
               $result_set[] = $row;
            }
            return $result_set;
        }else{
            return null;
        }
    }

    public function fetchAssoc($result) {
        if($result) {
            while($row = $result->fetch_assoc()) {
               $result_set[] = $row;
            }
            return $result_set[0];
        }else{
            return null;
        }
    }

}
?>