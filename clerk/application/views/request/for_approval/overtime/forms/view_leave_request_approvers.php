<div id="form_main" class="inner_form popup_form">
    <div id="form_default">
    <table width="100%" border="0" cellspacing="1" cellpadding="2">
		<?php foreach($leave_request_approvers_employee as $lra): ?>
        <?php 
			$employee 	= G_Employee_Finder::findById($lra->getPositionEmployeeId()); 
			$position	= G_Employee_Job_History_Finder::findCurrentJob($employee);
			
			$name	  	= $employee->getFirstName() . ' ' . $employee->getLastName();
			$pos		= $position->getName();
			
		?>
              <tr>
                <td style="width:5%" align="left" valign="middle"><img style="height:22px; width:22px;" src="<?php echo BASE_FOLDER .'images/profile_noimage.gif'; ?>" /></td>
                <td style="width:30%" align="left" valign="middle"><?php echo $name; ?></td>
                <td style="width:30%" align="left" valign="middle"><?php echo $pos; ?></td>
                <td style="width:15%" align="left" valign="middle">
                    <?php 
                        if($lra->getStatus() == -1) { echo 'DISAPPROVED'; }
                        if($lra->getStatus() == 0) { echo 'PENDING'; }
                        if($lra->getStatus() == 1) { echo 'APPROVED'; }
                    ?>
                </td>
                <td style="width:15%" align="left" valign="middle">
                <?php if($lra->getLevel() == 0) { ?>
                    <div class="ui-icon ui-icon-info info" style="float:right" title="This will override the permission of other approver(s). "></div>
                <?php } ?>
                </td>
              </tr>
            <?php endforeach; ?>
    </table>
    </div>
</div><!-- #form_main.inner_form -->   
<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>