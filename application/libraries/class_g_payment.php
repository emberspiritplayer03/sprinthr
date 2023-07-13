<?php
/*
	Usage:
		$p = new G_Payment;
		$p->setName('ID');		
		$p->setTotalAmount(1000);
		$p->setDateStarted('2012-10-22');
			$pb = new G_Payment_History;
			$pb->setAmountPaid(500);
			$pb->setDatePaid('2012-06-05');
		$p->addPaymentHistory($pb);	
			$pb = new G_Payment_History;
			$pb->setAmountPaid(500);
			$pb->setDatePaid('2012-06-10');			
		$p->addPaymentHistory($pb);
		$p->saveToEmployee($e);	
*/
class G_Payment extends Payment {
	protected $id;
	protected $employee_id;
	
	public function __construct() {
		
	}
	
	public function setId($value) {
		$this->id = $value;	
	}
	
	public function getId() {
		return $this->id;	
	}
	
	public function setEmployeeId($value) {
		$this->employee_id = $value;	
	}
	
	public function getEmployeeId() {
		return $this->employee_id;	
	}
	
	public function computeTotalAmountPaid() {
		$payments = $this->getPaymentHistories();	
		$total_payment = 0;
		foreach ($payments as $payment) {
			$total_payment += (float) $payment->getAmountPaid();
		}
		return $total_payment;
	}
	
	public function saveToEmployee(IEmployee $e) {
		return G_Payment_Manager::saveToEmployee($e, $this);
	}
}
?>