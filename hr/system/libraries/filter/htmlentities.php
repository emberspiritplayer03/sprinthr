<?php
/**
* WGFramework Wg_Filter_Htmlentities class
*
* This class converts characters to their corresponding HTML entity
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07   
*/

// Sample Usage:
/********************
	$html = new Wg_Filter_Htmlentities;
	$value = $html->filter("<script>alert('sample');</script>");
*/

require_once 'abstract.php';

class Wg_Filter_Htmlentities extends Wg_Filter_Abstract 
{
	protected $value; 
	
	public function __construct() {}
	
	/**
	 * Filter htmlentities value
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$this->setValue($value);
		//return strip_tags($this->value);
		return htmlentities($this->value, ENT_QUOTES);
	}
}
?>