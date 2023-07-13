<?php if($t) { ?>
	<?php if($t->getTimeIn() != "" && $t->getTimeIn() != "") { ?>
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <th align="left" valign="middle">Schedule</th>
            <th align="left" valign="middle">Time In / Time Out</th>
            <th align="left" valign="middle">Actual Time In / Time Out</th>
          </tr>
          <tr>
            <td align="left" valign="middle">
                <?php 
                    //echo date('F j',strtotime($t->getDateIn())) . ' - ' . date('F j Y',strtotime($t->getDateIn())); 
                    if($t->getTimeIn() == $t->getTimeOut()) {
                        echo ($t->getDateIn() != "" ? date('F j, Y',strtotime($t->getDateIn())) : 'No Date-In!');
                    } else {
                        if(date('F',strtotime($t->getDateIn())) == date('F',strtotime($t->getDateOut()))) {
							if(date('j',strtotime($t->getDateIn())) == date('j',strtotime($t->getDateOut()))) { 
								echo ($t->getDateIn() != "" ? date('F j, Y',strtotime($t->getDateIn())) : 'No Date-In!');
							} else {
								echo ($t->getDateIn() != "" ?  date('F j',strtotime($t->getDateIn())) . ' - ' . date('j, Y',strtotime($t->getDateOut())) : 'No Date-In / Date-Out!');
							}
                        } else {
							echo ($t->getDateIn() != "" ?  date('F j',strtotime($t->getDateIn())) . ' - ' . date('F j, Y',strtotime($t->getDateOut())) : 'No Date-In / Date-Out!');
                        }
                    }
                ?>
            </td>
            <td align="left" valign="middle">
            	<?php
					 echo Tools::convert24To12Hour($t->getScheduledTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getScheduledTimeOut());
				?>
            </td>
            <td align="left" valign="middle">
            	<?php
					 echo Tools::convert24To12Hour($t->getTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getTimeOut());
				?>
            </td>
          </tr>
        </table>
    <?php } else { echo 'No Time-In / Time-Out Found!'; } ?>
<?php } else { echo 'No schedule found!'; } ?>

