<script>
$(function(){
  $("#alphalist_date_from").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect: function(date){
         $("#philhealth_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
      }
  });

  $("#alphalist_date_to").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true   
  });  

  $('#alphalist_form').validationEngine({scroll:false});     
});
</script>
<h2><?php echo $title;?></h2>

<form id="alphalist_form" name="form1" method="post" action="<?php echo url($action); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
  <table width="100%">
    <tr>
      <td class="field_label">Year:</td>
      <td>
        <select name="alpha_year">
          <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
            <option value="<?php echo $start; ?>"><?php echo $start; ?></option>
          <?php } ?>
        </select>
      </td>
    </tr>  
    <tr>
                <td class="field_label">Report Type:</td>
                <td>
                    <select class="select_option" name="report_type" id="report_type">
                      <option value="<?php echo DETAILED; ?>">Detailed</option> 
                      <!--<option value="<?php echo SUMMARIZED; ?>">Summarized</option>-->                 
                    </select>              
            </tr>
    <?php if($is_with_confi_nonconfi_option){ ?>
      <tr>
          <td class="field_label">Employee Type</td>
          <td>
              <select name="q" id="q">
                  <option selected="selected" value="both">Both</option>
                  <option value="confidential">Confidential</option>
                  <option value="non-confidential">Non-Confidential</option>
              </select>                
          </td>
      </tr>
    <?php } ?>   
  </table>
  </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
      <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
