<style>
.qry-options{width:20% !important;margin-left:8px;}
.qry-inputs{width:30%;height:21px;margin-left:8px;}
.btn-remove-other-detail{margin-left:10px;}
.qry-title{background-color: #e3e3e3; padding-left: 11px;margin:22px 4px 17px; width: 100%;font-size: 15px;}
.btn-add-qry{margin-top: 7px;margin-right:7px;}
.rep-checkbox-container{margin-left:8px;}
.rep-checkbox-container .checkbox{margin-right:3px;}
</style>
<script>
$(function(){
  $(".btn-add-qry").click(function(){
      var total_rows  = $('.qry-container tr').length;          
      var remove_btn  = '<a class="btn btn-small btn-remove-other-detail" href="javascript:void(0);"><i class="icon-remove-sign"></i></a>';     
      $(".qry-container").append('<tr><td class="form-inline"><select class="qry-options" name="qry_fields[' + (total_rows+1) + ']"><?php foreach($qry_tbl_structure as $value){ ?><?php foreach($value as $key => $subValue){ ?><option value="<?php echo $key; ?>"><?php echo $subValue; ?></option><?php } ?><?php } ?></select><select class="qry-options" name="qry_options[' + (total_rows+1) + ']"><?php foreach($qry_options as $value){ ?><option><?php echo $value; ?></option><?php } ?></select><input class="qry-inputs" type="text" class="input-small" name="qry_values[' + (total_rows+1) + ']" value="" placeholder="Value" />' + remove_btn + '</td></tr>');   
      removeOtherDetail();   
  });

  $("#payreg-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'payreg-pay-period-container',$("#payreg-report-frequency-selector").val());
  });

  $("#payreg-report-frequency-selector").change(function(){    
     changePayPeriodByYear($("#payreg-report-year-selector").val(),'payreg-pay-period-container',this.value);
  });

  changePayPeriodByYear($("#payreg-report-year-selector").val(),'payreg-pay-period-container',$("#payreg-report-frequency-selector").val());

  function removeOtherDetail(){
      $(".btn-remove-other-detail").click(function(){        
        $(this).closest("tr").remove();
      });
  }
});

$("#payroll_register_form #payroll_date_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#payroll_register_form #payroll_date_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

/*function downloadReportPayroll() {
    var answer = $('#cutoff_payroll').val();
    var option_remove_resigned   = $("#removed-resigned").val();
    var option_remove_terminated = $("#removed-terminated").val();
    var period = answer.split('/');
    location.href=base_url + 'payroll/download_payroll_register?from='+ period[0] +'&to='+ period[1] + '&option_remove_resigned=' + option_remove_resigned + '&option_remove_terminated' + option_remove_terminated;
}*/
</script>
<h2><?php echo $title;?></h2>
<form id="payroll_register_form" name="form1" method="post" action="<?php echo url('payroll/download_payroll_register'); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
      <table width="100%">   
        <tr>
          <td style="width:17%;">Frequency Type</td>
          <td class="form-inline">:
            <select id="payreg-report-frequency-selector" name="frequency">
              <?php
              foreach(G_Settings_Pay_Period_Finder::findAll() as $period)
              {
              ?>

                  <option value = "<?php echo $period->id; ?>"> <?php echo $period->pay_period_name; ?> </option>    

              <?php
              }
              ?>
                <!-- <option value = "1">Bi-Monthly</option>
                <option value = "2">Weekly</option>     -->
            </select>
          </td>
        </tr>  
        <tr>   
        <tr>
          <td style="width:17%;">Year</td>
          <td class="form-inline">:
            <select id="payreg-report-year-selector">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>  
        <tr>
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <div class="payreg-pay-period-container" style="display:inline-block;"></div><br /> 
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="show_13th_month_only" value="1" />Show 13th month only</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="add_bonus_service_award" value="1" />Add Bonus and Service Award to earnings</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="add_13th_month" value="1" />Add 13th Month to earnings</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="add_converted_leave" value="1" />Add Converted Leave to earnings</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="bonus_and_service_award_only" value="1" />Bonus and Service Award Only</label>
            </td>
        </tr>
        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="q">
                      <option selected="selected" value="both">Both</option>
                      <option value="confidential">Confidential</option>
                      <option value="non-confidential">Non-Confidential</option>
                  </select>                
              </td>
          </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td class="form-inline">                
                <div class="rep-checkbox-container">
                  <label class="checkbox"><input type="checkbox" name="remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" name="remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" name="remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" name="remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr>

         <tr>
          <td>Cost Center</td>
          <td>: 
              <select name="cost_center" id="cost_center">
                  <option selected="selected" value="all">All</option>
                  <?php ksort($emp_cost_center); ?>
                  <?php foreach($emp_cost_center as $emp_cost_key => $emp_cost) { ?>
                          <option value="<?php echo $emp_cost_key; ?>"><?php echo $emp_cost; ?></option>
                  <?php } ?>
              </select>                
          </td>
        </tr> 
      </table>

      <h3 class="pull-left qry-title">Query Builder<a class="btn btn-small pull-right btn-add-qry" href="javascript:void(0);"><i class="icon-plus-sign"></i> Add Query</a></h3>      
      <div class="clear"></div>
      <table width="100%" class="qry-container">
        <tr>
          <td class="form-inline">
            <?php for($x = 1; $x <= $ini_start; $x++){ ?>
                  <select class="qry-options" name="qry_fields[<?php echo $x; ?>]">
                    <?php foreach($qry_tbl_structure as $value){ ?>
                      <?php foreach($value as $key => $subValue){ ?>
                        <option value="<?php echo $key; ?>"><?php echo $subValue; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select><select class="qry-options" name="qry_options[<?php echo $x; ?>]">
                    <?php foreach($qry_options as $value){ ?>
                      <option><?php echo $value; ?></option>
                    <?php } ?>
                  </select><input class="qry-inputs" type="text" class="input-small" name="qry_values[<?php echo $x; ?>]" value="" placeholder="Value" />         
            <?php } ?>
          </td>  
        </tr>

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
