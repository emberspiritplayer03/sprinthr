<?php
class Validate {
	public static function isInteger($value) {
		return (preg_match('/(?<!\S)\d++(?!\S)/', $value)) ? true : false ;
	}
	public static function hasValue($value) {
		return (strlen(trim($value)) > 0) ? true : false ;
	}
	public static function isValidDate($value) {		
		return (preg_match('/(19|20)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/', $value)) ? true : false ;	
	}
}
?>