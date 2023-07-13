<?php
interface IEmployee {
	public function getId();
	public function goToWork($date, $time_in, $time_out);
    public function requestOvertime($date, $time_in, $time_out, $reason = '');
    public function requestLeave($leave, $applied_date, $start_date, $end_date, $comment, $is_half_day1 = '', $is_half_day2 = '');
    public function requestOfficialBusiness($applied_date, $start_date, $end_date, $comment);
    public function getPayslip($month, $cutoff_number);
}
?>