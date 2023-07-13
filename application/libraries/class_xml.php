<?php
// Created by Marvin
// date: January 17,2012
class Xml {
	

	function objectsIntoArray($arrObjData, $arrSkipIndices = array())
	{
		$arrData = array();
	   
		// if input is object, convert into array
		if (is_object($arrObjData)) {
			$arrObjData = get_object_vars($arrObjData);
		}
	   
		if (is_array($arrObjData)) {
			foreach ($arrObjData as $index => $value) {
				if (is_object($value) || is_array($value)) {
					$value = $this->objectsIntoArray($value, $arrSkipIndices); // recursive call
				}
				if (in_array($index, $arrSkipIndices)) {
					continue;
				}
				$arrData[$index] = $value;
			}
		}
		return $arrData;
	}
	
	
	 function setNode($rootNode){
        $this->xmlResult = new SimpleXMLElement("<$rootNode></$rootNode>");
    }
   
    private function iteratechildren($object,$xml){
        foreach ($object as $name=>$value) {
            if (is_string($value) || is_numeric($value)) {
                $xml->$name=$value;
            } else {
                $xml->$name=null;
                $this->iteratechildren($value,$xml->$name);
            }
        }
    }
   
    function toXml($object) {
        $this->iteratechildren($object,$this->xmlResult);
		
        return $this->xmlResult->asXML();
		
	}

	
}

/*
//usage
  	$ob->id=10;
    $ob->desc ="textA";
    $ob->comment="textB";
    //----test object----

	header("Content-Type:text/xml");
	$xml = new Xml;
	
	$xml->setNode('kpi');
	echo $xml->toXml($ob);
	//echo $xmlStr = $xml->toXml($ob);
	
	

echo "Usage:";
$xmlUrl = "catalog.xml"; // XML feed file/URL
//$xmlStr = file_get_contents($xmlUrl);
$xmlObj = simplexml_load_string($xmlStr);
$xml = new Xml;
$arrXml = $xml->objectsIntoArray($xmlObj);
echo "<pre>";
print_r($arrXml);*/
?>