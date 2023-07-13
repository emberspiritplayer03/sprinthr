<?php if($attendance) { ?>  
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>          
          	<th align="left" valign="middle"></th>      
            <th align="left" valign="middle">Actual Time In / Time Out</th>
          </tr>
           <?php foreach($attendance as $t){ ?>
          <tr>  
          	<td align="left" valign="middle"><?php echo $t->getTimesheet()->getDateIn(); ?></td>           
            <td align="left" valign="middle">
            	<?php echo Tools::convert24To12Hour($t->getTimesheet()->getTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getTimesheet()->getTimeOut()); ?>
            </td>            
          </tr>
          <?php } ?>
        </table>
<?php } else { echo 'No schedule found!'; } ?>

