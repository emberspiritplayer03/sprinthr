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
	
	
	/**
	 * Encrypt given plain text using the key with RC4 algorithm.
	 * All parameters and return value are in binary format.
	 *
	 * @param string key - secret key for encryption
	 * @param string pt - plain text to be encrypted
	 * @return string
	 */
	
	function rc4Encrypt($key, $pt) {
		$s = array();
		for ($i=0; $i<256; $i++) {
			$s[$i] = $i;
		}
		$j = 0;
		$x;
		for ($i=0; $i<256; $i++) {
			$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
		}
		$i = 0;
		$j = 0;
		$ct = '';
		$y;
		for ($y=0; $y<strlen($pt); $y++) {
			$i = ($i + 1) % 256;
			$j = ($j + $s[$i]) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
			$ct .= $pt[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
	return $ct;
	}
	
	/**
	 * Decrypt given cipher text using the key with RC4 algorithm.
	 * All parameters and return value are in binary format.
	 *
	 * @param string key - secret key for decryption
	 * @param string ct - cipher text to be decrypted
	 * @return string
	*/
	function rc4Decrypt($key, $ct) {
		return rc4Encrypt($key, $ct);
	}

}

?>