<?php
/**
* WGFramework Validate_NoWhitespace class
*
* Checks if value does'nt contain whitespace
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************
	$valid = new Wg_Validate_NoWhitespace;		
	$string = 'the_value';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_NoWhitespace extends Wg_Validate_Abstract
{
	const HAS_SPACE = 'has_digit';
	const NO_VALUE = 'no_value';
	
	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::HAS_SPACE => 'Value has whitespace',
		self::NO_VALUE => 'Value is empty'
	);
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {}
	
	/**
	 * Check if valid value
	 *
	 * @param string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (!$value)
		{
			$this->addError(self::NO_VALUE);
			return false;
		}
		
		$this->setValue($value);
		
		if (strpos($value, ' ') !== false)
		{
			$this->addError(self::HAS_SPACE);
			return false;
		}

		return true;
	}
}
?>