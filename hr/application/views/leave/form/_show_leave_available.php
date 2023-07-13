<h3 class="leave-header">Leave Available</h3>
<?php if($leave_available) { ?>  
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>          
          	<th align="left" valign="middle"></th>      
            <th align="left" valign="middle">Available Days</th>
          </tr>
           <?php foreach($leave_available as $la){ ?>
          <tr>  
          	<td>
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
<?php } else { ?>
  <div class="alert alert-error">Selected employee has no leave available</div>
<?php } ?>

