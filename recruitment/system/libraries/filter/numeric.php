<?php
/**
* WGFramework Wg_Filter_Numeric class
*
* This class filters all numeric value
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07    
*/

// Sample Usage:
/********************
	$digit = new Wg_Filter_Numeric;
	$value = $digit->filter('a1b2c3d4');
*/

require_once 'abstract.php';

class Wg_Filter_Numeric extends Wg_Filter_Abstract 
{
	protected $value; 
	
	public function __construct() {}
	
	/**
	 * Filter all numeric value
	 *
	 * @param string $value
	 * @return int
	 */
	public function filter($value)
	{
		$this->setValue($value);
		return preg_replace('/[^0-9]/', '', $this->value);
	}
}
?>