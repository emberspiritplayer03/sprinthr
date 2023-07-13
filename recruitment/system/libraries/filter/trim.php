<?php
/**
* WGFramework Wg_Filter_Trim class
*
* This class trims all spaces in string
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07   
*/

// Sample Usage:
/********************
	$trim = new Wg_Filter_Trim;
	$value = $trim->filter('  hello world  ');
*/

require_once 'abstract.php';

class Wg_Filter_Trim extends Wg_Filter_Abstract 
{
	protected $value;
	
	/**
	 * Set whether to trim the inner characters
	 *
	 * @var bool
	 */
	protected $trim_inner;
	
	public function __construct($trim_inner = false)
	{
		$this->trim_inner = $trim_inner;
	}
	
	/**
	 * Trim spaces in string
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$this->setValue($value);
		
		$this->value = trim($this->value);
		
		if ($this->trim_inner)
		{
			$this->value = str_replace(' ', '', $this->value);
		}
		
		return $this->value;
	}
}
?>