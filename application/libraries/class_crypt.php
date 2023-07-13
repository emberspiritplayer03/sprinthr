
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
class Crypt
{
	var $salt_length = 240;
	
	var $skey = ENCRYPTION_KEY;
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
	
	function encrypt($string, $salt = '') 
	{ 
		if($salt!='') {
			$this->skey=$salt;
		}
		
			$return = $this->encode($string);	

		return $return;
	} 
	
	/**
	 * Convert into decrypted string
	 *
	 * @param string $text
	 * @param string $salt
	 * @return string

	 */
	
	function decrypt($string,$salt='') 
	{ 
		if($salt!='') {
			$this->skey=$salt;
		}
		
		$return = $this->decode($string);	

		return $return;
	}
	
	public function createHash($text) {
		$secret = ENCRYPTION_KEY;
		$id = $text;
		return $hash = md5($secret . $id);
	}
	
	public function verifyHash($text,$request) {
		$secret = ENCRYPTION_KEY;
		$id = $text;
		$hash = md5($secret . $id);
		if (trim($hash) == trim($request)) {
		  	$return = true;
		} else {
		   	$return = false;
		}
		
		return $return;
	}
	
	
	private  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    private function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    private  function encode($value){ 
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }

    private function decode($value){
        if(!$value){return false;}
        $crypttext = $this->safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

	
	
}

?>