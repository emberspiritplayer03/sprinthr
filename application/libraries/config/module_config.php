<?php

/**

* WGFramework Module_Config class

*

* This class gets and sets configuration to xml using SimpleXML

*

* @version 1.0.0

* @package WGFramework

* @author Webgroundz::zyke

* @category Library

* @date created Nov-2-2007

*/



include 'config.php';



class Module_Config extends Config

{

	/**

	 * Path where xml is located

	 *

	 * @var string

	 */

	protected $config_path;

	

	/**

	 * Session Object

	 *

	 * @var object

	 */

	protected $session;

	

	/**

	 * SimpleXMLElement Object

	 *

	 * @var object

	 */

	protected $xml;

	

	function __construct($config_path = '', $session_namespace = 'config')

	{

		Loader::sysLibrary('session');

		$this->session = new WG_Session(array('namespace' => $session_namespace));



		if (!file_exists($config_path))

		{

			exit('News config file can not found');

		}

		

		$this->config_path = $config_path;

		

		// Set SimpleXML object

		// Use XML object in the session if set

		if ($xml_object = $this->session->get('xml'))

		{

			$xml_object = unserialize($xml_object);

			$this->xml = $xml_object;

		}

		else

		{

			$this->xml = new SimpleXMLElement(file_get_contents($this->config_path));

			$serialized = $this->serializeSimpleXmlObject($this->xml);

			$this->session->set('xml', $serialized);

		}

	}

	

	/**

	 * Get value from the XML file

	 *

	 * @param string $field if empty it returns object

	 * @return mixed

	 */

	public function get($field = '')

	{

		if ($field != '')

		{

			return $this->xml->setting->$field;

		}

		else

		{

			return (array) $this->xml;

		}

	}

	

	public function set($field_value = array())

	{

		if (is_array($field_value) && count($field_value) > 0)

		{

			foreach ($field_value as $field => $value)

			{

				$this->xml->setting->$field = $value;

			}

			

			$xml = new SimpleXMLElement(file_get_contents($this->config_path));

			

			if (serialize($xml) != serialize($this->xml))

			{

				file_put_contents($this->config_path, $this->xml->asXML());

				$this->session->removeAll();

			}

		}

	}



	protected function serializeSimpleXmlObject($xml_object)

	{

		$serialized = str_replace(

			array('O:16:"SimpleXMLElement":0:{}', 'O:16:"SimpleXMLElement":'),

			array('s:0:"";', 'O:8:"stdClass":'),

			serialize($xml_object)

		);

	}	

}

?>