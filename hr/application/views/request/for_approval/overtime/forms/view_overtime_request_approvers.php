<div id="form_main" class="inner_form popup_form">
<div id="form_default">
    <table width="100%" border="1">
    <tr>
    <th align="left" valign="top">By Employee</th>
    <th align="left" valign="top">By Position</th>
    </tr>
      <tr>
        <td style="width:60%" align="left" valign="middle">
            <table width="100%" border="0" cellspacing="1" cellpadding="2">
            <tr>
                <th width="45%" align="left" colspan="2" valign="top">Employee Name</th>
                <th width="40%" align="left" valign="top">Position</th>
                <th width="10%" align="left" valign="top">Status</th>
                <th width="10%" align="left" valign="top">Level</th>
            </tr>
                <?php foreach($overtime_request_approvers_employee as $lra): ?>
                <?php 
                    $employee 	= G_Employee_Finder::findById($lra->getPositionEmployeeId()); 
                    $position	= G_Employee_Job_History_Finder::findCurrentJob($employee);
                    
                    $name	  	= $employee->getFirstName() . ' ' . $employee->getLastName();
                    $pos		= $position->getName();
					$title = ($lra->getPositionEmployeeId() == Utilities::decrypt($eid) ? 'You will override the permission of other approver(s).' : 'This will override the permission of other approver(s).');
                                
                ?>
                      <tr>
                        <td style="width:5%" align="left" valign="middle"><img style="height:22px; width:22px;" src="<?php echo BASE_FOLDER .'images/profile_noimage.gif'; ?>" /></td>
                        <td style="width:40%" align="left" valign="middle"><?php echo $name; ?></td>
                        <td style="width:40%" align="left" valign="middle"><?php echo $pos; ?></td>
                        <td style="width:10%" align="left" valign="middle">
                            <?php echo $lra->getStatus(); ?>
                        </td>
                        <td style="width:10%" align="left" valign="middle">
                        <?php if($lra->getLevel() == 0) { ?>
                            <div class="ui-icon ui-icon-info info" style="float:left;" title="<?php echo $title; ?>"></div>
                        <?php } else { ?>
                            <span style="text-align:center;"><?php echo $lra->getLevel(); ?></span>
                        <?php }  ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
            </table>
        </td>
        <td style="width:40%" align="left" valign="middle">
        	 <table width="100%" border="0" cellspacing="1" cellpadding="2">
            <tr>
                <th width="60%" align="left" valign="top">Position</th>
                <th width="20%" align="left" valign="top">Status</th>
                <th width="20%" align="left" valign="top">Level</th>
            </tr>
                <?php foreach($overtime_request_approvers_position as $lra): ?>
                <?php 
                    $position	= G_Employee_Job_History_Finder::findById($lra->getPositionEmployeeId());
					$title = ($lra->getPositionEmployeeId() == Utilities::decrypt($h_job_position_id) ? 'You will override the permission of other approver(s).' : 'This will override the permission of other approver(s).');         
                ?>
                      <tr>
                        <td style="width:60%" align="left" valign="middle"><?php echo $position->getName(); ?></td>
                        <td style="width:20%" align="left" valign="middle">
                            <?php echo $lra->getStatus(); ?>
                        </td>
                        <td style="width:20%" align="left" valign="middle">
                        <?php if($lra->getLevel() == 0) { ?>
                            <div class="ui-icon ui-icon-info info" style="float:left" title="<?php echo $title; ?>"></div>
                        <?php } else { ?>
                            <span style="text-align:center;"><?php echo $lra->getLevel(); ?></span>
                        <?php }  ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
            </table>
        </td>
      </tr>
    </table>
</div>
</div><!-- #form_main.inner_form -->   
<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
$('.info').tipsy({gravity: 's'});
</script>