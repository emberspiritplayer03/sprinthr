<div class="script-container"></div>
<script>
$(function(){  
  <?php echo $ini_script; ?>
});
</script>
<?php for($start = 1; $start <= $ini_approvers; $start++){ ?>
<tr class="tr-approvers">
      <td style="width:15%" align="left" valign="middle">Approver (Level <?php echo $start; ?>)</td>
      <td style="width:15%" align="left" valign="middle">: 
        <input class="validate[required] text-input" type="text" name="approvers[<?php echo $start; ?>]" id="approver_<?php echo $start; ?>" value="" />
      </td>
</tr>
<?php } ?>
