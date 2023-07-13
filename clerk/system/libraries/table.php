<?php

/**

* WGFramework Table class

*

* This class generate automatic table based on the result of the query 

*

* @version 1.0.2

* @package WGFramework

* @author Webgroundz

* @category Library

* @date created Jul-04-07 

* @last modified Nov-06-07    

*/



// Sample Usage:

/********************

	<?php

	//$result = mysql_query("select fname, lname, age from tbl_person");

	$records = array

	(

		array

		(

		'fname' => 'tolits',

		'lname' => 'dungog',

		'age' => '21'

		),

		

		array

		(

		'fname' => 'harney',

		'lname' => 'cercado',

		'age' => '21'

		)

	);

	

	$fields = array

	(

		array('title' =>"Age", "field" => 'age'),

		array("title"=>"First Name", "field" => 'fname'),

		array("title" =>"Last Name", "field" => 'lname')

	);

	

	//table class

	//has default style

	$table = new Wg_Table($records, $fields);

	$table->generate();

	

	//for table style

	$style['table'] = 'width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#F3F3F3"';

	$style['header'] = 'bgcolor="#EFEFEF"';

	$style['body'] = 'bgcolor="#FFFFFF"';

	$style['column'][1] = 'bgcolor="green"';

	$style['column'][2] = 'bgcolor="blue"';

	

	$table = new Wg_Table($records, $fields);

	echo $table->generate($style);

	?>

*/



class Wg_Table

{

	public $fields;

	public $records;

	

	private $style;

	

	/**

	 * Initialize the fields and records in array

	 *

	 * @param array $records

	 * @param array $fields

	 */

	public function __construct($records, $fields)

	{

		$this->style['table'] = 'width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#F3F3F3"';

		$this->style['body'] = 'bgcolor="#FFFFFF"';

		$this->style['header'] = 'bgcolor="#EFEFEF"';

	

		$this->records = $records;

		$this->fields = $fields;

	}

	

	/**

	 * Get field name

	 *

	 * @return array

	 */

	private function getField()

	{

		if(is_array($this->fields))

		{

			foreach ($this->fields as $key => $field)

			{

				foreach ($field as $fields => $value)

				{

					if($fields == 'field')

					{

						$array[] .= $value;

					}	

				}

			}

			return $array;

		}	

	}

	

	/**

	 * Get table title

	 *

	 * @return string

	 */

	private function getTitle()

	{

		$str .= '<thead  ' . $this->style['header'] . '>';

		$str .= '<tr>';

		if(is_array($this->fields))

		{

			foreach ($this->fields as $key => $title)

			{

				foreach ($title as $caption => $value)

				{

					if($caption == 'title')

					{

						$key = $key + 1;

						$str .= '<th ' . $this->style['column'][$key] . '>';

						$str .= $value;

						$str .= '</th>';

					}	

				}

			}

			$str .= '</tr>';

			$str .= '</thead>';

			return $str;

		}	

	}

	

	/**

	* Get table records

	*

	* @return string

	*/

	private function getRecords()

	{

		if(!is_array($this->records))

		{

			return false;

		}

			

		$str .= '<tbody>';

		$counter = 1;

		foreach ($this->records as $index => $record)

		{			

			$str .= '<tr ' . $this->style['body'] . '>';

			foreach ($this->getField() as $key => $field)

			{

				$key = $key + 1;

				foreach ($record as $records => $value)

				{

					if($records == $field)

					{

						$str .= '<td ' . $this->style['column'][$key] . '>';

						$str .= $value;

						$str .= '</td>';

					}	

				}	

			}

			$counter++;

		}

		$str .= '</tr>';

		$str .= '</tbody>';

		return $str;

	}

	

	/**

	 * Generate records with in a table

	*/

	public function generate($styles = array())

	{

		if(is_array($styles))

		{

			foreach ($styles as $index => $value)

			{

				$this->style[$index] = $value;

			}

		}	

		$str .= '<table ' . $this->style['table'] . '>';

		$str .= $this->getTitle();

		$str .= $this->getRecords();

		$str .= '</table>';

		

		return $str;

	}

}

?>

