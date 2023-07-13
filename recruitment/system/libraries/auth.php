<?php

// Created on June 8, 2007 by webgroundz::zyklone

// Sample Usage:

/*		$auth = new WG_Auth('login');

		$auth->authenticate('zyke', 'zyke');

		

		if ($auth->isValid())

		{

			echo "do valid stuffs";

		}

		else

		{

			$this->view->render('login/login_form.php');

		}*/



class WG_Auth

{

	const AUTH_OBJECT = 'auth_object';

	

	protected $table;

	protected $username_field;

	protected $password_field;

	protected $is_auth = false;

	protected $username;

	protected $id_field = 'id';

	protected $id;

	

	private $session;

		

	function __construct($table, $username_field = 'username', $password_field = 'password')

	{

		$this->table = $table;

		$this->username_field = $username_field;

		$this->password_field = $password_field;

		

		load_sys_class('session');

		load_sys_class('sql');

		

		$this->session = new WG_Session('auth');

	}

	

	function setIdField($field)

	{

		$this->id_field = $field;

		//$this->session->set($this->id_field, $field);

	}

	

	static function getInstance()

	{

		load_sys_class('session');

		$unserialized_object = self::_getUnserializedObject();



		if (is_object($unserialized_object))

		{

			return $unserialized_object;

		}

		else

		{

			return new WG_Auth(NULL);

		}

	}

	

	public function authenticate($username, $password)

	{

		$sql = 	"SELECT " . $this->username_field . ", " . $this->id_field . "

				FROM " . $this->table . "

				WHERE " . $this->username_field . "='$username'

				AND " . $this->password_field . "='$password'";

			

		$result = WG_SQL::query($sql);

		

		$total = WG_SQL::count_row($result);

		

		$return = false;

		

		if ($total > 0)

		{

			$row = WG_SQL::fetch_array($result);

			

			$this->is_auth = true;

			$this->username = $username;

			$this->id = $row[$this->id_field];

			

			$this->_serializeObject();

			

			$return = true;

		}

		

		return $return;

	}

	

	static function _getUnserializedObject()

	{

		$session = new WG_Session('auth');

		return unserialize($session->get(AUTH_OBJECT));

	}

	

	private function _serializeObject()

	{

		$class_auth = serialize($this);

		$this->session->set(AUTH_OBJECT, $class_auth);	

	}

	

	public function isValid()

	{

		$return = false;

		if ($this->is_auth)

		{

			$return = true;

		}

		

		return $return;

	}

	

	public function getUsername()

	{

		return $this->username;

	}

	

	public function getId()

	{

		return $this->id;

	}

}

?>