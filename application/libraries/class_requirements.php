<?php
class Requirements {
	
	function __construct($id) {
		$this->id = $id;
	}
	
	//Requirements::getDefaultRequirements();
	public static function getDefaultRequirements()
	{
		//CONVERT XML TO ARRAY
		$xmlUrl = $_SERVER['DOCUMENT_ROOT'].BASE_FOLDER. 'files/xml/requirements.xml';
		
		if(Tools::isFileExist($file)==true) {
			$xmlStr = file_get_contents($xmlUrl);
			$xmlStr = simplexml_load_string($xmlStr);

			$xml2 = new Xml;
			$arrXml = $xml2->objectsIntoArray($xmlStr);
			foreach($arrXml as $key=>$value) {
				$array[Tools::friendlyFormName($value)] = '';
			}	
			$return = $array;
		}else {
			$return = 'No file exist';
		}
		
		return $return;
		
	}
	
}

?>