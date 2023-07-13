<h2 class="field_title"><?php echo $title; ?></h2>
<div id="application_history_edit_form_wrapper"></div>
<div id="application_history_add_form_wrapper" style="display:none">
<?php 
include 'form/application_add.php';
?>
</div>
<div id="application_history_edit_form_wrapper"></div>
<div id="application_history_delete_wrapper"></div>
<div id="application_history_table_wrapper">
<table width="858" id="hor-minimalist-b"  border="0">
      <thead>
        <tr>
          <th width="206" scope="col">Event</th>
          <th width="383" scope="col">Date</th>
          <th width="255" scope="col">Notes</th>
        </tr>
      </thead>
      <tbody>
      <?php 
	  $ctr = 0;
	  $count=0;
	   foreach($details as $key=>$e) { 
	   $prefix = '';
	   if($e->event_type==INTERVIEW) {
		   $count++;
		 	  	if($count==1) {
					$prefix = '1st';	
				}else if($count==2) {
					$prefix = '2nd';
				}else if($count==3) {
					$prefix = '3rd';
				}else if($count==4) {
					$prefix = '4th';
				}else if($count==5) {
					$prefix = '5th';
				}else {
					$prefix = '';	
				}
	   }	
	   
	   ?>
        <tr>
          <td><a href="javascript:void(0);" onclick="javascript:loadApplicationHistoryEditForm('<?php echo $e->id; ?>');"><?php echo $prefix. ' ' . $GLOBALS['hr']['application_status'][$e->event_type]; ?></a></td>
          <td><?php echo Date::convertDateIntIntoDateString(substr($e->date_time_event,0,10),2). ' '. substr($e->date_time_event,11,18); ?></td>
          <td><?php echo $e->notes; ?></td>
        </tr>
       <?php 
	   $ctr++;
	   }

	  if($ctr==0) { ?>
		  <tr>
          <td colspan="3"><center><i>No Record(s) Found</i></center></td>
        </tr> 
		<?php }  ?>
      </tbody>
    </table>
</div>