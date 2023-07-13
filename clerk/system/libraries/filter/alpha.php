<?php
/**
* WGFramework Wg_Filter_Alpha class
*
* This class filters all alphabetic string
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Filter
* @date created Aug-10-07
*/

// Sample Usage:
/********************
	$alpha = new Wg_Filter_Alpha;
	$value = $alpha->filter('a1b2c3d4');
*/

require_once 'abstract.php';

class Wg_Filter_Alpha extends Wg_Filter_Abstract 
{
	protected $value; 
	
	public function __construct() {}
	
	/**
	 * Filter alphabetic value
	 *
	 * @param string $value
	 * @return string
	 */
	public function filter($value)
	{
		$this->setValue($value);
		return preg_replace('/[^A-Za-z]/', '', $this->value);
	}
}
?>