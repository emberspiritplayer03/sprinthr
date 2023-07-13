<?php
class G_Employee_Contribution_Helper {

    public static function addContribution($employee_id, $salary) {

        //$ph = G_Philhealth_Finder::findBySalary($salary); //old computation 2017
        $ph = G_Philhealth_Table_Finder::findBySalary($salary);

        if($ph) {
            //$philhealth_er = (float) $ph->getCompanyShare();
            //$philhealth_ee = (float) $ph->getEmployeeShare();
            $philhealth_er = (float) round($ph['company_share'],2);
            $philhealth_ee = (float) round($ph['employee_share'],2);             
        }else{
            $philhealth_er = 0;
            $philhealth_ee = 0;
        }
        
        $sss = G_SSS_Finder::findBySalary($salary);
        if($sss) {
            $sss_er = (float) $sss->getCompanyShare();
            $sss_ee = (float) $sss->getEmployeeShare();
        }else{
            $sss_er = 0;
            $sss_ee = 0;
        }

        // OLD
        /*$pagibig = G_Pagibig_Finder::findBySalary($salary);
        $pagibig_er = (float) $pagibig->getCompanyShare();
        $pagibig_ee = (float) $pagibig->getEmployeeShare();*/

        $pagibig = G_Pagibig_Table_Finder::findBySalary($salary);
        $pagibig_er = (float) $pagibig['company_share'];
        $pagibig_ee = (float) $pagibig['employee_share'];

        //check if fixed pagibig contri in settings is enabled
        $dc = G_Settings_Fixed_Contributions_Finder::findByName('pagibig');
        $is_fixed_enabled = false;
        if($dc->getIsEnabled() == 1){
           
            $is_fixed_enabled = true;
            $amount = 100;
             $gefc = new G_Employee_Fixed_Contribution();
             $gefc->setEmployeeId($employee_id);                
             $gefc->deleteAllByEmployeeId();
                                
             $gefc->setType(G_Employee_Fixed_Contribution::TYPE_HDMF);
             $gefc->setEEAmount($amount);     
             $gefc->setERAmount($amount);
             $gefc->setIsActivated(1);              
             $gefc->save();
        }



        $contribution = G_Employee_Contribution_Finder::findByEmployeeId($employee_id);
        if (!$contribution) {
            $to_deduct_arr['sss'] = G_Employee_Contribution::YES;
            $to_deduct_arr['philhealth'] = G_Employee_Contribution::YES;
            $to_deduct_arr['pagibig'] = G_Employee_Contribution::YES;

            if($is_fixed_enabled){
                $to_deduct_arr['pagibig'] = G_Employee_Contribution::NO;
            }

            $c = self::generate($employee_id, $sss_ee, $pagibig_ee, $philhealth_ee, $sss_er, $pagibig_er, $philhealth_er);
            $c->setToDeduct(serialize($to_deduct_arr));
            $c->save();
        }else{
            $c = self::generate($employee_id, $sss_ee, $pagibig_ee, $philhealth_ee, $sss_er, $pagibig_er, $philhealth_er);
            $c->setId($contribution->getId());
            $c->setToDeduct($contribution->getToDeduct());
            $c->save();
        }
    }

    public static function updateContribution($employee_id, $basic_salary){
        //$ph = G_Philhealth_Table_Finder::findBySalary($salary);
        $ph = G_Philhealth_Table_Finder::findBySalary($salary);

        if($ph) {
            //$philhealth_er = (float) $ph->getCompanyShare();
            //$philhealth_ee = (float) $ph->getEmployeeShare();
            $philhealth_er = (float) round($ph['company_share'],2);
            $philhealth_ee = (float) round($ph['employee_share'],2);             
        }else{
            $philhealth_er = 0;
            $philhealth_ee = 0;
        }

        $sss = G_SSS_Finder::findBySalary($basic_salary);
        if($sss) {
            $sss_er = (float) $sss->getCompanyShare() + $sss->getProvidentEr();
            $sss_ee = (float) $sss->getEmployeeShare() + $sss->getProvidentEe();
           // self::updateSSS($employee_id, $sss_ee, $sss_er);
        }else{
            $sss_er = 0;
            $sss_ee = 0;
        }

        $sql = "
            UPDATE " . G_EMPLOYEE_CONTRIBUTION . " SET sss_ee = " . Model::safeSql($sss_ee) . ", 
                sss_er = " . Model::safeSql($sss_er) . " 
                WHERE employee_id = " . Model::safeSql($employee_id) . "
        ";

        $result = Model::runSql($sql);
    }

    public static function generate($employee_id, $sss_ee, $pagibig_ee, $philhealth_ee, $sss_er, $pagibig_er, $philhealth_er) {
        $cont = new G_Employee_Contribution;
        $cont->setEmployeeId($employee_id);
        $cont->setSssEe($sss_ee);
        $cont->setPagibigEe($pagibig_ee);
        $cont->setPhilhealthEe($philhealth_ee);
        $cont->setSssEr($sss_er);
        $cont->setPagibigEr($pagibig_er);
        $cont->setPhilhealthEr($philhealth_er);
        return $cont;
    }
		
	public static function isIdExist(G_Employee_Contribution $e) {
		$sql = "
			SELECT COUNT(*) as total
			FROM " . G_EMPLOYEE_CONTRIBUTION ."
			WHERE employee_id = ". Model::safeSql($e->getEmployeeId()) ."
		";

		$result = Model::runSql($sql);
		$row    = Model::fetchAssoc($result);
		return $row['total'];
	}

}
?>