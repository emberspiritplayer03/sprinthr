<?php
/**
* WGFramework Wg_Filter_Character class
*
* This class replaces character to desired character
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Library
* @date created Aug-3-07 
* @last modified Aug-8-07     
*/

// Sample Usage:
/********************
	$filter = array
	(
		' ' => '_'
	);
	
	$char = new Wg_Filter_Character($filter);
	$char_val = $char->filter('hello world');
*/

require_once 'abstract.php';

class Wg_Filter_Character extends Wg_Filter_Abstract 
{
	protected $value; 
	protected $char;
	
	public function __construct($char) 
	{
		$this->char = $char;
	}

	/**
	 * Filter characters in array of string
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$this->setValue($value);
		if(is_array($this->char))
		{
			$result = $value;
			foreach ($this->char as $find => $replace)
			{
				$result = preg_replace('/' . $find . '/', $replace, $result);
			}
		 	return $result;
		}
	}
}
?>