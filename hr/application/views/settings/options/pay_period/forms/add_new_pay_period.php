<script>
$(function(){
  $("#addPayPeriod").validationEngine({scroll:false});
  $('#addPayPeriod').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
              dialogOkBox(o.message,{});                                  
              load_pay_period_dt();

              var $dialog = $('#action_form');                    
              $dialog.dialog("destroy");                    

          } else {                            
              dialogOkBox(o.message,{});          
          }                   
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });
});
</script>
<div id="form_main" class="inner_form popup_form wider">
	<form name="addPayPeriod" id="addPayPeriod" method="post" action="<?php echo $action_pay_period; ?>">   
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Pay Period Code:</td>
            <td>
                <input type="text" value="" name="pay_period_code" class="validate[required] text" id="pay_period_code" />    
            </td>
        </tr>  
        <tr>
            <td class="field_label">Pay Period Name:</td>
            <td>
                <input type="text" value="" name="pay_period_name" class="validate[required] text" id="pay_period_name" />    
            </td>
        </tr>
        <tr>
          <td class="field_label">1st Cut Off:</td>
          <td><input style="width:10%;" type="text" value="" name="first_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_a" /> to <input style="width:10%;" type="text" value="" name="first_cutoff_b" class="alidate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_b" />
            <input style="width:51%;" type="text" placeholder="Pay Day: Day number" value="" name="first_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_payday" />

          </td>
        </tr>        
        <tr>
          <td class="field_label">2nd Cut Off:</td>
          <td><input style="width:10%;" type="text" value="" name="second_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_a" /> to <input style="width:10%;" type="text" value="" name="second_cutoff_b" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_b" />
            <input style="width:51%;" type="text" placeholder="Pay Day: Day number" value="" name="second_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_payday" />
          </td>
        </tr>        
        <tr>
          <td class="field_label">Is Default:</td>
          <td>
          	<select id="is_default" name="is_default" style="width:39%;">
            	<option value="0">No</option>
                <option value="<?php echo G_Settings_Pay_Period::IS_DEFAULT; ?>">Yes</option>
            </select>
          </td>
        </tr>    
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" /></td>
        </tr>          
    </table>
    </div>
    </form>
</div>