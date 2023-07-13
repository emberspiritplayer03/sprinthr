<?php

abstract class Wg_Validate_Abstract
{
	protected $value;
	protected $errors = array();
	protected $message_templates = array();
	
	public function getErrors()
	{
		return $this->errors;	
	}
	
	public function isValid($value) {}
	
	protected function setValue($value)
	{
		$this->value = $value;
	}
	
	protected function addError($error_message_key)
	{
		$error = $this->message_templates[$error_message_key];
		$this->errors[] = $error;
	}
	
	public function setMessages($messages)
	{
		if (is_array($messages) && count($messages) > 0)
		{
			$this->message_templates = $messages;
		}
	}
}
?>