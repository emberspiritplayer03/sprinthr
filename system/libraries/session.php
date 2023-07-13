<?php

/**

 * WGFramework Session class

 *

 * Manages session

 *

 * @package		WGFramework

 * @author		Webgroundz

 * @category	System

 */



class WG_session

{

	/**

	 * Namespace of the session

	 *

	 * @var string

	 */

	protected $namespace;

	

	/**

	 * Constructor

	 *

	 * @param array $config

	 */

	function __construct($config = array('namespace' => 'default'))

	{

		$this->namespace = $config['namespace'];

	}

	

	/**

	 * Set session

	 *

	 * @param string $name

	 * @param string $value

	 */

	public function set($name, $value)

	{

		$_SESSION[$this->namespace][$name] = $value;

	}

	

	/**

	 * Set multiple session data

	 *

	 * @param array $data_array

	 * @return mixed

	 */

	public function setMultiple($data_array)

	{

		if(!is_array($data_array)) // is not an array? return false

		{

			return false;

		}

		

		foreach ($data_array AS $name => $value)

		{

			$_SESSION[$this->namespace][$name] = $value;

		}		

	}

	

	/**

	 * Get session value

	 *

	 * @param string $name

	 * @return mixed

	 */

	public function get($name)

	{

		$return = false;

		if (isset($_SESSION[$this->namespace][$name]))

		{

			$return = $_SESSION[$this->namespace][$name];

		}

		

		return $return;

	}

	

	/**

	 * Remove session data

	 *

	 * @param string $name

	 */

	public function remove($name)

	{

		unset($_SESSION[$this->namespace][$name]);

	}

	

	/**

	 * Remove all session datas in a namespace

	 *

	 */

	public function removeAll()

	{

		unset($_SESSION[$this->namespace]);

	}



	/**

	 * Get all session data in a namespace

	 *

	 * @param bool $print Print or not

	 * @return mixed

	 */

	public function getAll($print = false)

	{

		if (!is_array($_SESSION[$this->namespace]))

		{

			return false;	

		}

		

		foreach ($_SESSION[$this->namespace] AS $name => $value)

		{

			$session[$name] = $value;

		}

		

		if ($print)

		{

			echo "<pre>";

			print_r($session);

			echo "</pre>";

		}

		else 

		{

			return $session;	

		}

		

	}

	

	/**

	 * Get the namespace

	 *

	 * @return string

	 */

	public function getNamespace()

	{

		return $this->namespace;

	}

}



?>