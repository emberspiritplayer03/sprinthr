<?php
/**
* WGFramework Validate_Email class
*
* Validates value if contains valid email format
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************
	$valid = new Wg_Validate_Email;		
	$string = 'user@email.com';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_Email extends Wg_Validate_Abstract
{
	const INVALID = 'invalid';
	
	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::INVALID => 'Invalid Email Address'
	);
	
	
	public function __construct() {}
	
	/**
	 * Check if valid email format
	 *
	 * @param string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$this->setValue($value);
		
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value))
		{		
			$this->addError(self::INVALID);
			return false;
		}

		return true;
	}
}
?>