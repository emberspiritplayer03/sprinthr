<?php
/**
* WGFramework Crypt class
*
* This class encrypts plain text
*
* @version 1.0.0
* @package WGFramework
* @author Webgroundz
* @category Library
* @date created Dec-20-2011    
*/


// Sample Usage:
/********************
	$key = 'thequickrbrownfox';
	$string = 'eighteen';
	$crypt = new Wg_Crypt;
	echo $crypt->encrypt_string($string, $key);
*/

// Sample Usage:
/********************
	$string = 'thequickrbrownfox';
	$salt = 'eighteen';
	$crypt = new Wg_Crypt;
	echo $crypt->encrypt($string, $salt);

// Sample Usage:
/********************
	$string = 'thequickrbrownfox';
	$salt = 'eighteen';
	$crypt = new Wg_Crypt;
	echo $crypt->decrypt($string, $salt);

*/
class Wg_Crypt
{
	var $salt_length = 9;

	/**
	 * Convert into encrypted string
	 *
	 * @param string $plainText
	 * @param string $key
	 * @return string

	 */

	function encrypt_string($plainText, $key = null)
	{
	    if ($key == null)
	    {
	        $key =  substr(md5($plainText), 0, $this->salt_length);
	    }
	    else
	    {
	        $key = substr(md5($key), 0, $this->salt_length);
	    }

		$encrypt = substr(sha1($key . $plainText), 0, $this->salt_length);
	    return $encrypt;

	}
	
	/**
	 * Convert into encrypted string
	 *
	 * @param string $text
	 * @param string $salt
	 * @return string

	 */
	
	function encrypt($text,$salt = '') 
	{ 
		if($salt=='') {
			$salt=ENCRYPTION_KEY;
		}
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
	} 
	
	/**
	 * Convert into decrypted string
	 *
	 * @param string $text
	 * @param string $salt
	 * @return string

	 */
	
	function decrypt($text,$salt='') 
	{ 
		if($salt=='') {
			$salt=ENCRYPTION_KEY;
		}
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
	} 

}

?>