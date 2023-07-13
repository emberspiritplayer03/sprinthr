<style>
ul.emp-benefits{list-style:none;}
ul.emp-benefits li{display:inline-block;margin:5px;width:304px;}
</style>
<script>
$(function(){
  $('#add_benefit_form').validationEngine({scroll:false});   
  $('#add_benefit_form').ajaxForm({
      success:function(o) {        
        if( o.is_success ){ 
          hideAddBenefitForm();
          loadEmployeeBenefits(o.eid);
        }
        dialogOkBox(o.message,{});
        $('#token').val(o.token);
      },
      dataType:'json',     
      beforeSubmit: function() {        
        showLoadingDialog('Saving...');
      }
  });

  $("#btn-cancel-add-benefit").click(function(){
    hideAddBenefitForm();
  });
});
</script>
<form id="add_benefit_form" name="add_benefit_form" method="post" action="<?php echo url('employee/_add_benefit_to_employee'); ?>">
<div id="form_main" class="employee_form">
<input type="hidden" name="eid" value="<?php echo $eid; ?>" />
<input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
<div id="form_default">
  <?php echo $description; ?>
  <table>
  	 <tr>      
      <td colspan="2">    
        <ul class="emp-benefits">    
        <?php foreach($benefits as $b){ ?>
          <li>
          <label class="checkbox">
            <input type="checkbox" name="benefits[]" id="benefits" value="<?php echo $b['id']; ?>" /><?php echo $b['name']; ?>
          </label>            
          </li>
        <?php } ?>        
        </ul>
      </td>
    </tr>    
  </table>
</div><!-- #form_default -->
<div class="form_action_section" id="form_default">
    <table>
    	<tr>
          <td class="field_label">&nbsp;</td>
          <td valign="top"><input class="blue_button" type="submit" name="button" id="button" value="Add Benefit" /> 
            <a id="btn-cancel-add-benefit" href="javascript:void(0);">Cancel</a></td>
        </tr>
    </table>
</div><!-- #form_default.form_action_section -->
</div><!-- #form_main.employee_form -->
</form>
