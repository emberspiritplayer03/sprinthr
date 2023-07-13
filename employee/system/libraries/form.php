<?php

/**

 * WGFramework Form class

 *

 * This class is used to put a form element in the page.

 *

 * @package		WGFramework

 * @author		Webgroundz

 * @category	Library

 */



class WG_Form

{

	/**

	 * The data inputted from form, it may be $_POST or $_GET

	 *

	 * @var array

	 */

	public $data_array;

	

	/**

	 * Constructor

	 *

	 */

	public function __construct() {}

	

	/**

	 * Start the form, it is equal to <form> tag

	 *

	 * @param string $name

	 * @param string $action

	 * @param string $method

	 * @param array $attributes

	 * @return string

	 */

	public function startForm($name, $action, $method = null, $attributes = array())

	{

		if ($method == null) 

		{

			$method = 'post';

			$this->data_array = $_POST;

		}

		else if ($method == 'get')

		{

			$this->data_array = $_GET;

		}

		

		$att = $this->mergeAttributes($attributes);

		

		return "<form id='$name' action='$action' name='$name' method='$method' $att>";

	}

	

	/**

	 * Start the form with multipart or with uploading functionality, it is equal to <form enctype='multipart/form-data'>

	 *

	 * @param string $name

	 * @param string $action

	 * @param string $method

	 * @param array $attributes

	 * @return string

	 */

	public function startMultipartForm($name, $action, $method = null, $attributes = array())

	{

		if ($method == null) 

		{

			$method = 'post';

			$this->data_array = $_POST;

		}

		else if ($method == 'get')

		{

			$this->data_array = $_GET;

		}

		

		$att = $this->mergeAttributes($attributes);

		

		return "<form id='$name' action='$action' name='$name' method='$method' enctype='multipart/form-data' $att>";

	}

	

	/**

	 * Merge the attributes of a control and make it like "name='the_name', value='the_value'"

	 *

	 * @param array $attributes

	 * @return string

	 */

	private function mergeAttributes($attributes)

	{

		$att = '';

		if (is_array($attributes))

		{

			foreach ($attributes AS $name => $value)

			{

				$att .= "$name='$value'";

			}

		}

		

		return $att;

	}

	

	/**

	 * Put a textbox

	 *

	 * @param string $name

	 * @param string $label

	 * @param array $attributes

	 * @return string

	 */

	public function text($name, $label = '', $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);

		return "$label <input type='text' id='$name' name='$name' value='" . $this->data_array[$name] . "' $att />";

	}



	/**

	 * Put hidden value

	 *

	 * @param string $name

	 * @param string $value

	 * @return string

	 */

	public function hidden($name, $value)

	{

		return "<input type='hidden' id='$name' name='$name' value='$value' />";

	}

	

	/**

	 * Put password textbox

	 *

	 * @param string $name

	 * @param string $label

	 * @param array $attributes

	 * @return string

	 */

	public function password($name, $label = '', $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);

		return "$label <input type='password' id='$name' name='$name' value='" . $this->data_array[$name] . "' $att />";

	}

	

	/**

	 * Put combo box

	 *

	 * @param string $name

	 * @param array $values_array The values in array

	 * @param array $attributes

	 * @return string

	 */

	public function comboBox($name, $values_array = array(), $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);	

		$str = "<select id='$name' name='$name' $att>";

		foreach($values_array AS $caption => $value)

		{

			if (is_integer($caption))

			{

				$caption = $value;

			}

			

			if($value == $this->data_array[$name])

			{

				$selected = 'selected=selected';

			}

			else

			{

				$selected = '';

			}

			

			$str .= "<option value='$value' $selected>$caption</option>";

		}

		$str .= "</select>";

		

		return $str;		

	}

	

	/**

	 * Put text area

	 *

	 * @param string $name

	 * @param array $attributes

	 * @return string

	 */

	public function textArea($name, $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);

		return "<textarea name='$name' id='$name' $att>" . $this->data_array[$name] . "</textarea>";

	}

	

	/**

	 * Put radio button

	 *

	 * @param string $name

	 * @param string $value

	 * @param array $attributes

	 * @return string

	 */

	public function radio($name, $value, $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);

		if(isset($this->data_array[$name]) && $this->data_array[$name] == $value)

		{

			$checked = 'checked=checked';

		}

		return "<input type='radio' name='$name' id='$name' value='$value' $checked $att />";

	}

	

	/**

	 * Put check box

	 *

	 * @param string $name

	 * @param string $value

	 * @param array $attributes

	 * @return string

	 */

	public function checkBox($name, $value, $attributes = array())

	{

		$name_arr="$name"."[]";

		

		$att = $this->mergeAttributes($attributes);

		

		$total_array=count($this->data_array[$name]);

		

		if (count($this->data_array[$name]) > 0)

		{

			foreach ($this->data_array[$name] AS $key => $selected_value)

			{

				if ($value == $selected_value)

				{

					$checked = 'checked=checked';

				}

			}

		}

		

		return "<input type='checkbox' name='$name_arr' id='$name' value='$value' $att $checked />";

	}

	

	/**

	 * Put file browser

	 *

	 * @param string $name

	 * @param array $attributes

	 * @return string

	 */

	public function fileBrowser($name, $attributes = array())

	{

		$att = $this->mergeAttributes($attributes);

		return "<input type='file' id='$name' name='$name' $att />";

	}

	

	/**

	 * Put submit button

	 *

	 * @param string $name

	 * @param string $value

	 * @return string

	 */

	public function submitButton($name='Submit', $value='Submit')

	{

		return "<input type='submit' name='$name' id='$name' value='$value'>";

	}

	

	/**

	 * Put end form tag, equal to </form>

	 *

	 * @return unknown

	 */

	public function endForm()

	{

		return "</form>";

	}

	

	/**

	 * Determine if the form is submitted

	 *

	 * @param string $name

	 * @return bool

	 */

	public function isSubmit($name='Submit')

	{

		$return = false;

		if(isset($this->data_array[$name]))

		{

			$return = true;

		}

		

		return $return;

	}

}

?>