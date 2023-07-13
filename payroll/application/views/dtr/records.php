<table width="52%" bgcolor="#ffffff" border="0" align="center" cellpadding="6" cellspacing="1">
<?php 
$counter = 1;
foreach ($records as $r):?>
<?php
$date = $r->getDate();
if ($date == date('Y-m-d', strtotime('today'))) {
	$date_string = 'Today';	
} else {
	$date_string = $date;	
}
?>
<?php if ($counter == 1):?>
  <tr style="color:blue">
    <td width="19%" bgcolor="#CCCCCC"><h1><?php echo $r->getEmployeeCode();?></h1></td>
    <td width="44%" bgcolor="#CCCCCC"><h1><?php echo $r->getEmployeeName();?></h1></td>
    <td width="37%" bgcolor="#CCCCCC"><h1><?php echo date('g:i a', strtotime($r->getTime()));?> - <?php echo $date_string;?></h1></td>
  </tr>
<?php else:?>
  <tr>
    <td width="19%" bgcolor="#CCCCCC"><?php echo $r->getEmployeeCode();?></td>
    <td width="44%" bgcolor="#CCCCCC"><?php echo $r->getEmployeeName();?></td>
    <td width="37%" bgcolor="#CCCCCC"><?php echo date('g:i a', strtotime($r->getTime()));?> - <?php echo $date_string;?></td>
  </tr>
<?php endif;?>
<?php $counter++;?> 
<?php
$date_string = '';
$date = '';
?>
<?php endforeach;?>
</table>