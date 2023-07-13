<?php
class G_Applicant_Logs_Helper {
	public static function isIdExist(G_Applicant_Logs $al) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT_LOGS ."
			WHERE id = ". Model::safeSql($al->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

	public static function isEmailExist($email) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT_LOGS ."
			WHERE email = ". Model::safeSql($email) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function isPasswordExists($password) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . APPLICANT_LOGS ."
			WHERE password = ". Model::safeSql($password) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}	
	
	public static function getUserIPAndCountry(){		
		$user_ip = Tools::getRealIpAddr();				
		$url     = "http://api.hostip.info/country.php?ip={$my_ip}&position=true";
		$user['ip']     = $user_ip;
		$user['country']= file_get_contents($url);
		return $user; 
	}	
	
	public static function generateApplicantRandomPassword(){
		$password 			  = Tools::createRandomPasswordByLength(10);
		$epassword 		     = Utilities::encrypt($password);	
		$is_password_exists = self::isPasswordExists($epassword);
		if($is_password_exists > 0){
			$password = self::generateApplicantRandomPassword();
		}
		$ar_password['password'] = $password;
		$ar_password['epassword']= $epassword;
		return $ar_password;
	}
	
	public static function generateVerificationLink($id){
		$hash =  Utilities::createHash($id);
		$eid  =  Utilities::encrypt($id);
		$url = url("account/verification?eid={$eid}&hid={$hash}");
		return $url;
	}
	
	public static function generateVerificationLinkWithJobId($id,$jeid){
		$hash =  Utilities::createHash($id);
		$eid  =  Utilities::encrypt($id);
		
		if($jeid != "none"){			
			$url = url("account/verification?eid={$eid}&hid={$hash}&jeid={$jeid}");
		}else{
			$url = url("account/verification?eid={$eid}&hid={$hash}");
		}
		return $url;
	}
}
?>