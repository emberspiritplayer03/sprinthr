<?php if($attendance) { ?>  
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>          
          	<th align="left" valign="middle">Date</th>          	 
            <th align="left" valign="middle">Plotted Schedule</th>          
          </tr>
          <?php foreach($attendance as $t){ ?>           
          <tr>  
          	<td align="left" valign="middle"><?php echo $t->getDate(); ?></td>           
            <td align="left" valign="middle">
            	<?php echo Tools::convert24To12Hour($t->getTimesheet()->getScheduledTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getTimesheet()->getScheduledTimeOut()); ?>
            </td>            
          </tr>
          <?php } ?>
        </table>
<?php } else { echo 'No schedule found!'; } ?>

