
<?php 
$next_id = G_Employee_Helper::getNextId(Utilities::decrypt($employee_id));
$next_applicant = G_Employee_Finder::findById($next_id);
$next_hash = G_Employee_Helper::getNextHash(Utilities::decrypt($employee_id));
	
$e = G_Employee_Finder::findById($next_id);

$file = PHOTO_FOLDER.$e->photo;

if(Tools::isFileExist($file)==1 && $e->photo!='') {
	$filemtime_next = md5($e->photo).date("His");
	$filename_next = $file;
	
}else {
	$filename_next = BASE_FOLDER. 'images/profile_noimage.gif';
	
}			
$tipsy_next = '<div class=tipsynextprev_record><table width=200 border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td width=40 rowspan=4><img width=50 src='.$filename_next.'?'.$filemtime_next.'></td>
    <td width=160><font size=-2>Name:<br><strong> '.$e->lastname .' ' . $e->firstname.'</strong></font></td>
  </tr>
</table></div>';

$prev_id = G_Employee_Helper::getPreviousId(Utilities::decrypt($employee_id));
$prev_applicant = G_Employee_Finder::findById($prev_id);
$prev_hash = G_Employee_Helper::getPreviousHash(Utilities::decrypt($employee_id));

$e = G_Employee_Finder::findById($prev_id);

$file = PHOTO_FOLDER.$e->photo;

if(Tools::isFileExist($file)==1 && $e->photo!='') {
	$filemtime_prev = md5($e->photo).date("His");
	$filename_prev = $file;
	
}else {
	$filename_prev = BASE_FOLDER. 'images/profile_noimage.gif';
	
}
			
			
$tipsy_prev = '<div class=tipsynextprev_record><table width=200 border=0 cellpadding=0 cellspacing=0>
  <tr>
    <td width=40 rowspan=4><img width=50 src='.$filename_prev.'?'.$filemtime_prev.'></td>
    <td width=160><font size=-2>Name:<br><strong> '.$e->lastname .' ' . $e->firstname.'</strong></font></td>
  </tr>
</table></div>';;
if($prev_id) {
	$previous_record = '<a title="'.$tipsy_prev.'" class="tooltip_prev" href="'. url('startup/profile?eid='.Utilities::encrypt($prev_id).'&hash='.$prev_hash.'#personal_details') .'"><span>Previous Record</span></a>';	
}
else {
	$previous_record = '<strong class="disabled_prev"><span>Previous Record</span></strong>';	
}
if($next_id) {
	$next_record  = '<a title="'.$tipsy_next.'" class="tooltip_next" href="'. url('startup/profile?eid='.Utilities::encrypt($next_id).'&hash='.$next_hash.'#personal_details') .'"><span>Next Record</span></a>';	
}
else {
	$next_record = '<strong class="disabled_next"><span>Next Record</span></strong>';
}
?>
