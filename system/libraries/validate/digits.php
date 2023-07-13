<?php
/**
* WGFramework Validate_Digits class
*
* Validates value if contains only digits
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************

	$valid = new Wg_Validate_Digits;		
	$string = 'the_digits';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_Digits extends Wg_Validate_Abstract
{
	const NOT_DIGIT = 'not_digit';
	
	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::NOT_DIGIT => 'Value is not a digit'
	);
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {}
	
	/**
	 * Check if valid digit value
	 *
	 * @param absolute number $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$this->setValue($value);
		
		if(preg_match('/[^0-9]/', $value))
		{		
			$this->addError(self::NOT_DIGIT);
			return false;
		}

		return true;
	}
}
?>