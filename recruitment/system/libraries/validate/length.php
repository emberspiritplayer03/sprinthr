<?php
/**
* WGFramework Validate_Length class
*
* Checks value if valid number of characters
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Validate
* @date created Aug-10-07 
*/

// Sample Usage:
/********************
	$valid = new Wg_Validate_Length(3, 5);		
	$string = 'the_value';
	
	if (!$valid->isValid($string))
	{
		print_r($valid->getErrors());
	}
*/

require_once 'abstract.php';

class Wg_Validate_Length extends Wg_Validate_Abstract
{	
	const TOO_SHORT = 'too_short';
	const TOO_LONG = 'too_long';
	
	/**
	 * Minimum allowed length
	 *
	 * @var int
	 */
	protected $min;
	
	/**
	 * Maximum allowed length
	 *
	 * @var int
	 */
	protected $max;
	
	/**
	 * Messages
	 *
	 * @var array
	 */
	protected $message_templates = array
	(
		self::TOO_SHORT => 'Value is too short',
		self::TOO_LONG => 'Value is too long'
	);
	
	/**
	 * Constructor
	 *
	 * @param int $min
	 * @param int $max
	 */
	public function __construct($min, $max = null) 
	{
		$this->min = $min;
		$this->max = $max;
	}
	
	/**
	 * Check if valid length
	 *
	 * @param string $value
	 * @return boolean
	 */
	public function isValid($value)
	{
		$this->setValue($value);

		$length = strlen($value);
		
		if ($length < $this->min)
		{		
			$this->addError(self::TOO_SHORT);
		}
		
		if ($this->max && $length > $this->max)
		{
			$this->addError(self::TOO_LONG);
		}
		
		if (count($this->errors))
		{
			return false;
		}
		return true;
	}
}
?>