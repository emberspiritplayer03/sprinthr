<?php if($attendance) { ?>  
        <table class="table table-bordered table-striped table-condensed form_main_inner_table">
          <tr>          
          	<th align="left" valign="top" style="width:100px;"></th>      
            <th align="left" valign="top">Actual Time In / Time Out</th>
          </tr>
           <?php foreach($attendance as $t){ ?>
          <tr>  
          	<td align="left" valign="top" style="width:100px;"><?php echo $t->getTimesheet()->getDateIn(); ?></td>           
            <td align="left" valign="top">
            	<?php echo Tools::convert24To12Hour($t->getTimesheet()->getTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getTimesheet()->getTimeOut()); ?>
            </td>            
          </tr>
          <?php } ?>
        </table>
<?php } else { echo 'No schedule found!'; } ?>

