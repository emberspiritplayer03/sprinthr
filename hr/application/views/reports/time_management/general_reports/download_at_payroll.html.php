 <?php ob_start();?>
 <table width="100%" border="1">

  <thead>
        	
      <tr>

      	<th>Username</th>
      	<th>Role</th>
      	<!--<th valign="top" >Module</th>
      	<th valign="top" >Action</th>
      	<th valign="top" >Activity Type</th>-->
        <th>Audited Action</th>
        <th>From</th>
        <th>To</th>
        <th>Event Status</th>  
        <th>Position</th>
        <th>Department</th>  
        <th>Audit Date & Time</th>  
        <!--<th valign="top" >Audit Time</th>-->
        
      </tr>
      		
  </thead>
        
  <?php 
  $i = 1;
  foreach($data as $value):?>
      <tr> 

       		<td ><?php echo $value['username'];?></td>
       		<td ><?php echo $value['role'];?></td>
       		<td ><?php echo $value['activity_action'].' '.$value['activity_type'].' '.$value['audited_action'];?></td>
       		<td ><?php echo $value['action_from'];?></td>
       		<td ><?php echo $value['action_to'];?></td>
       		<td ><?php echo $value['event_status'];?></td>
       		<td ><?php echo $value['position'];?></td>
       		<td ><?php echo $value['department'];?></td>
       		<td ><?php echo $value['audit_date'].' '.$value['audit_time'];?></td>
       
      </tr>
 <?php endforeach; ?>  
</table>

<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>