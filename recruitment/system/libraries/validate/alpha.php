<?php
/**
* WGFramework Validate_Alpha class
*
* This class validates value if it is an alpha
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************

	$valid = new Wg_Validate_Alpha;		
	$string = 'the_string';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_Alpha extends Wg_Validate_Abstract
{
	protected $white_space = false;
	
	const NOT_ALPHA = 'not_alpha';

	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::NOT_ALPHA => 'Value is not alphabetic',
		self::NO_VALUE => 'No Value'
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
	 * Check if value is valid alpha
	 *
	 * @param string
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
		
		if(preg_match('/[^a-zA-Z' . $white_space . ']/', $value))
		{		
			$this->addError(self::NOT_ALPHA);
			return false;
		}

		return true;
	}
}
?>