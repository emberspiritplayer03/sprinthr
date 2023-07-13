<?php
class G_Employee_Contact_Details_Helper {
    public static function addContactDetails($employee_id, $address, $city, $province, $zip_code, $home_phone, $mobile, $work_phone, $work_email, $other_email) {
        $contact_details = G_Employee_Contact_Details_Finder::findByEmployeeId($employee_id);
        if (!$contact_details) {
            $contact_details = new G_Employee_Contact_Details;
            $contact_details->setEmployeeId($employee_id);
            $contact_details->setAddress($address);
            $contact_details->setCity($city);
            $contact_details->setProvince($province);
            $contact_details->setZipCode($zip_code);
            $contact_details->setHomeTelephone($home_phone);
            $contact_details->setMobile($mobile);
            $contact_details->setWorkTelephone($work_phone);
            $contact_details->setWorkEmail($work_email);
            $contact_details->setOtherEmail($other_email);
            return $contact_details->save();
        }
    }

	public static function isIdExist(G_Employee_Contact_Details $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_CONTACT_DETAILS ."
			WHERE employee_id = ". Model::safeSql($e->getEmployeeId()) ."
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}
	

}
?>