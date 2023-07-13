<?php
abstract class Wg_Filter_Abstract
{
	protected $value;
	
	protected function setValue($value)
	{
		$this->value = $value;	
	}
	
	abstract function filter($value);
}
?>