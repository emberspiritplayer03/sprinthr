<script>
$("#frm-set-employee-fixed-contri").validationEngine({scroll:false});
$('#frm-set-employee-fixed-contri').ajaxForm({
	success:function(o) {
		if(o==1) {
			dialogOkBox('Successfully Updated',{});			
			loadContribution("<?php echo $employee_id; ?>");
			
		}else {
			dialogOkBox(o,{});	
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Saving...');	
	}
});
</script>
<form id="frm-set-employee-fixed-contri" name="frm-set-employee-fixed-contri" method="post" action="<?php echo url('employee/update_employee_fixed_contributions'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
<div id="form_default">
  <?php foreach( $fixed_contri_types as $type ){ ?>
    <h3><?php echo $type; ?></h3>
    <table>
      <tr>
        <td class="field_label">EE</td>
        <td><input type="text" class="validate[required] text-input" name="fixed_contri[<?php echo $type; ?>][ee]" id="<?php echo $type . "_ee"; ?>" value="<?php echo array_key_exists($type, $employee_fixed_contri) ? $employee_fixed_contri[$type]['ee_amount'] : '0.00'; ?>" /></td>
      </tr>
      <tr>
        <td class="field_label">ER</td>
        <td><input type="text" class="validate[required] text-input" name="fixed_contri[<?php echo $type; ?>][er]" id="<?php echo $type . "_er"; ?>" value="<?php echo array_key_exists($type, $employee_fixed_contri) ? $employee_fixed_contri[$type]['er_amount'] : '0.00'; ?>" /></td>
      </tr>
      <tr>
        <td class="field_label">Is Activated</td>
        <td>
          <?php 
            $selected = 0;
            if( array_key_exists($type, $employee_fixed_contri) ){
              $selected = $employee_fixed_contri[$type]['is_activated'];
            }
          ?>
          <select name="fixed_contri[<?php echo $type; ?>][is_activated]" id="<?php echo $type . "_is_activated"; ?>">
            <option <?php echo $selected > 0 ? 'selected="selected"' : ''; ?>  value="1">Yes</option>
            <option <?php echo $selected == 0 ? 'selected="selected"' : ''; ?> value="0">No</option>
          </select>
        </td>
      </tr>
    </table>
    <hr />
  <?php } ?>

</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" name="button" id="button" value="Save" />&nbsp;<a href="javascript:void(0);" onclick="javascript:loadContribution('<?php echo $employee_id; ?>');">Cancel</a></td>
    	</tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
