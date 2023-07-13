<div>
	<b> Are you sure you want to sync data now?</b> <br/><br/>
	
	<i style="margin-left:10px;"> You have <b><?php echo $total_sync_data;?></b> record(s) to be sync.</i>
	<br/>
	<?php if($total_sync_data > 0) { ?>
		<i style="margin-left:10px;"><?php echo $total_sync_data;?> record(s) may take approximately <b><?php echo $approximate_time; ?></b>.</i><br/>
		<small style="margin-left:10px;font-size:11px;">Internet speed may still vary the time of synchronization.</small>
	<?php } ?>


	<br/><br/>
	<?php if($has_connection) { ?>
		<div class='alert alert-info'> Note : Data synchronization may take few minutes.</div>
	<?php }else{ ?>
		<div class='alert alert-error'> Error : Unable to connect to remote server.</div>
	<?php } ?>
</div>

<!--
local
insert = 7s
delete = 7s
update = 6s

live
insert = 9s
delete = 11s
update = 8s
-->