<h3 class="leave-header">Request Approvers</h3>
<?php if( $approvers ){ ?>
<table width="100%" border="0" cellspacing="1" cellpadding="2"> 
  <?php foreach($approvers as $level => $approver) { ?>
    <tr>
    <td align="right" class="field_label">Approver #<?php echo $level;?></td>
    <td>
      <select name="approvers[<?php echo $level; ?>]" id="approver_id_<?php echo $level;?>" class="select-approver validate[required]" style="width:219px;">
      <?php foreach($approver as $key => $value) { ?>
        <option value="<?php echo Utilities::encrypt($value['employee_id']); ?>"><?php echo $value['employee_name']; ?></option>
      <?php } ?>
      </select>
    </td>
    </tr>
  <?php } ?>
</table>
<?php }else{ ?>
  <div class="alert alert-error">No approvers set for selected employee</div>
<?php } ?>