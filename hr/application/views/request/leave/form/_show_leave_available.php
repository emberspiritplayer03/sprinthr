<?php if($leave_available) { ?>  
	<table class="table table-bordered table-striped table-condensed form_main_inner_table">
	  <tr>          
		<th align="left" valign="middle" style="width:100px;"></th>
		<th align="left" valign="middle">Available Days</th>
	  </tr>
	   <?php foreach($leave_available as $la){ ?>
	  <tr>  
		<td style="width:100px;">
			<?php 
				$l = G_Leave_Finder::findById($la->getLeaveId());
				if($l){echo $l->getName();}
				else{echo 'Record not found';}
			?>
		</td>
		<td align="left" valign="middle"><?php echo $la->getNoOfDaysAvailable(); ?></td> 
	   </tr>
	  <?php } ?>
	</table>
<?php } else { echo 'No Leave Available'; } ?> 
