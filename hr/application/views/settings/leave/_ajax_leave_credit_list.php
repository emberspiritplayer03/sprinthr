<?php if(!empty($leave_credits)) { ?>
<?php
	$yrConstant = array(
		1 => '1st',
		2 => '2nd',
		3 => '3rd',
		4 => '4th',
		5 => '5th',
		6 => '6th',
		7 => '7th',
		8 => '8th',
		9 => '9th',
		10 => '10th',
		);
?>
<table border="0" cellpadding="3" cellspacing="0" width="100%"> 
    <tbody>
    	<?php foreach($leave_credits as $key => $credits) {?>
        <tr>
            <td class="field_label" valign="">On Employee's <b><?php echo $yrConstant[$credits['employment_years']]; ?> year</b> onwards add <b><?php echo $credits['default_credit']; ?> credits</b> in <b><?php echo $credits['name']; ?></b> to all <b><?php echo $credits['status']; ?></b> employee's</td>
            <td class="">
            	<div style="width:35px;">
		            <a style="float:right;" original-title="Remove Credit" id="edit" class="ui-icon ui-icon-trash g_icon" href="javascript:void(0);" onclick="javascript:deleteLeaveCredit('<?php echo Utilities::encrypt($credits['id']); ?>');"></a>				            	
		            <a style="float:right;" original-title="Edit Credit" id="edit" class="ui-icon ui-icon-pencil g_icon" href="javascript:void(0);" onclick="javascript:load_edit_leave_credits('<?php echo Utilities::encrypt($credits['id']); ?>');"></a>
	            </div>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php } ?>