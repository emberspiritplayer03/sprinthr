<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<?php 
  $data_day_shift   = $data['day_schedule'];
  $data_night_shift = $data['night_schedule'];
  if( $report_type == 'ds' ){
    include_once('day_shift.php');
  }elseif( $report_type == 'ns' ){
    include_once('night_shift.php');
  }else{
    include_once('day_shift.php');
    echo "<br />";
    include_once('night_shift.php');
  }
?>
<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $filename);
header("Pragma: no-cache");
header("Expires: 0");
?>