<?php
/**
* WGFramework Validate_Alnum class
*
* Validates value if contains only alpha numeric
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************

	$valid = new Wg_Validate_Alnum;		
	$string = 'the_string';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_Alnum extends Wg_Validate_Abstract
{
	protected $white_space = false;
	
	const NOT_ALNUM = 'not_alnum';
	const NO_VALUE = 'no_value';
	
	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::NOT_ALNUM => 'Value is not alphanumeric',
		self::NO_VALUE => 'Value is empty'
	);
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {}
	
	/**
	 * Allow whitespace in the value
	 *
	 */
	public function allowWhiteSpace()
	{
		$this->white_space = true;
	}
	
	/**
	 * Check if valid alpha numeric value
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
		
		$white_space = ($this->white_space) ? '\s' : '';
		
		if(preg_match('/[^a-zA-Z0-9' . $white_space . ']/', (string) $value))
		{		
			$this->addError(self::NOT_ALNUM);
			return false;
		}

		return true;
	}
}
?>