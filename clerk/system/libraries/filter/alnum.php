<?php
/**
* WGFramework Wg_Filter_Alnum class
*
* This class filters all alphanumeric string
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07   
*/

// Sample Usage:
/********************
	$alnum = new Wg_Filter_Alnum;
	$value = $alnum->filter('to$#%li333s');
*/

require_once 'abstract.php';

class Wg_Filter_Alnum extends Wg_Filter_Abstract 
{
	protected $value; 
	
	public function __construct() {}
	
	/**
	 * Filter alphanumeric value
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$this->setValue($value);
		return preg_replace('/[^A-Za-z0-9]/', '', $this->value);
	}
}
?>