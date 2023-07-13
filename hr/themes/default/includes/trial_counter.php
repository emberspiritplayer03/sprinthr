
<?php
//$timestamp = sg_get_const("expire_date");

 $datetime = date("Y-m-d", $timestamp);

$today = date('Y-m-d');
$today = strtotime($today);
$finish = $datetime;
$finish = strtotime($finish);
//difference
$diff = $finish - $today;

$daysleft=floor($diff/(60*60*24));


if($daysleft>1) {
	$days="days";
}else {
	$days="day";	
}
 $remaining = "$daysleft $days remaining";
 
 ?>

<div class="trial_container">
	<div class="dtrl_holder">
        <div class="demo_title">Demo Version</div>
        <div class="days_left"><i class="icon-time vertical-middle icon-fade"></i> <span><?php echo $remaining; ?></span></div>
    </div>
</div>
<div class="trial_trans"></div>
<div class="trial_container">
	<div class="dtrl_holder">
        <div class="demo_title">Demo Version</div>
        <div class="days_left"><i class="icon-time vertical-middle icon-fade"></i> <span><?php echo $remaining; ?></span></div>
    </div>
</div>
<div class="trial_trans"></div>