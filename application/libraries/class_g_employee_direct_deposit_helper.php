<?php
class G_Employee_Direct_Deposit_Helper {

    public static function addBankAccount($employee_id, $bank_name, $bank_account) {
        $dd = G_Employee_Direct_Deposit_Finder::findByEmployeeId($employee_id);
        if(!$dd) {
            $dd = new G_Employee_Direct_Deposit();
            $dd->setEmployeeId($employee_id);
            $dd->setBankName($bank_name);
            $dd->setAccount($bank_account);
            return $dd->save();
        }
    }
		
	public static function isIdExist(G_Employee_Direct_Deposit $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_DIRECT_DEPOSIT ."
			WHERE id = ". Model::safeSql($e->getId()) ."
		";
		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>