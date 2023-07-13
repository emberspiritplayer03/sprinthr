<?php if($attendance) { ?>  
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>          
          	<th align="left" valign="middle"></th>      
            <th align="left" valign="middle">Actual Time In / Time Out</th>
          </tr>
           <?php foreach($attendance as $t){ ?>
          <tr>  
          	<td align="left" valign="middle"><?php echo $t->getDate(); ?></td>           
            <td align="left" valign="middle">
              <?php
                $a_remarks = array();
                $s_remarks = "";
                if( $t->isRestday() ){
                  $a_remarks[] = "(RD)";
                }                 
                if( $t->isHoliday() ){
                  $a_remarks[] = "(HOLIDAY)";
                }
                if( $t->isLeave() ){
                  $a_remarks[] = "(ONLEAVE)";
                }
                if( $t->isOfficialBusiness() ){
                  $a_remarks[] = "(OB)";
                }

                $s_remarks = implode("/", $a_remarks);

                if( $t->getTimesheet()->getTimeIn() != "" ){ 
                  echo Tools::convert24To12Hour($t->getTimesheet()->getTimeIn()) . ' - ' . Tools::convert24To12Hour($t->getTimesheet()->getTimeOut()) . "<b>{$s_remarks}</b>";
                }else{
                  if( $s_remarks == "" ){
                    echo "- Absent -";  
                  }else{
                    echo "<b>" . $s_remarks . "</b>";
                  }
                }
              ?>            	
            </td>            
          </tr>
          <?php } ?>
        </table>
<?php } else { echo 'No schedule found!'; } ?>

