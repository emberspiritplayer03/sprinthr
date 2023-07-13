<?php
/**
* WGFramework Wg_Filter_StripTags class
*
* This class strips tags from a string, and can set the allowable tags
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07
*/

// Sample Usage:
/********************
	$allowed = array
	(
		'<b>',
		'<h1>',
		'<center>'
	);
	
	$strip = new Wg_Filter_StripTags;
	$string = $strip->setAllowedTags($allowed);
	$strip_val = $strip->filter('<b>hi there</b>');
*/

require_once 'abstract.php';

class Wg_Filter_StripTags extends Wg_Filter_Abstract 
{
	protected $value; 
	public $allowed;
	private $result;	
	
	public function __construct() {}

	/**
	 * Set allowed tags
	 *
	 * @param array $allowed
	 */
	public function setAllowedTags($allowed)
	{
		if(is_array($allowed))
		{
			foreach ($allowed as $key => $string)
			{
				$allow .= $string;
			}
			$this->result = $allow;
		}
	}
	
	/**
	 * Strip tags
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
    {
		$this->setValue($value);
		if($this->result)
		{
			return strip_tags($this->value, $this->result);
		}
		else 
		{
			return strip_tags($this->value);
		}
	}
}
?>