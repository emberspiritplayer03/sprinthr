<script>
$(function(){
  $("#editPayPeriod").validationEngine({scroll:false});
  $('#editPayPeriod').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
              dialogOkBox(o.message,{});                                              
              var $dialog = $('#ini_user_modal');                    
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
    <form name="editPayPeriod" id="editPayPeriod" method="post" action="<?php echo $action_pay_period; ?>">  
    <input type="hidden" value="<?php echo $pp->getId() ?>" id="pay_period_id" name="pay_period_id" />    
    <div id="form_default">   
    <p>Before using <b>SprintHR</b>, we need you to specify below the correct <b>pay period</b>.</p>  
    <div class="form_separator"></div>    
    <table width="100%" border="0" cellpadding="3" cellspacing="0">        
        <tr>
          <td class="field_label">1st Cut Off:</td>
          <td><input style="width:10%;" type="text" value="<?php echo $first_cutoff[0]; ?>" name="first_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_a" /> to <input style="width:10%;" type="text" value="<?php echo $first_cutoff[1]; ?>" name="first_cutoff_b" class="alidate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_b" /></td>
        </tr>
        <tr>
          <td class="field_label">Payday:</td>
          <td>
            <input style="width:37%;" type="text" placeholder="Pay Day: Day number" value="<?php echo $payoutday[0]; ?>" name="first_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="first_cutoff_payday" />
          </td>
        </tr>  
        <tr><td colspan="2"><div class="form_separator"></div></td></tr>       
        <tr>
          <td class="field_label">2nd Cut Off:</td>
          <td><input style="width:10%;" type="text" value="<?php echo $second_cutoff[0]; ?>" name="second_cutoff_a" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_a" /> to <input style="width:10%;" type="text" value="<?php echo $second_cutoff[1]; ?>" name="second_cutoff_b" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_b" />            
          </td>
        </tr>         
        <tr>
          <td class="field_label">Payday:</td>
          <td>
            <input style="width:37%;" type="text" placeholder="Pay Day: Day number" value="<?php echo $payoutday[1]; ?>" name="second_cutoff_payday" class="validate[required,custom[integer],min[1],max[31]] text" id="second_cutoff_payday" />
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