<script>
$(function(){
  $("#cashfile-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'cashfile-pay-period-container',$("#cashfile-report-frequency-selector").val());
  });

  $("#cashfile-report-frequency-selector").change(function(){    
     changePayPeriodByYear($("#cashfile-report-year-selector").val(),'cashfile-pay-period-container',this.value);
  });

  changePayPeriodByYear($("#cashfile-report-year-selector").val(),'cashfile-pay-period-container',$("#cashfile-report-frequency-selector").val());

   
});

function downloadCashFileReport() {
    var frequency = $("#cashfile-report-frequency-selector").val();
    var answer = $('.cashfile-pay-period-container #cutoff_period').val();
    var cost_center = $("#cost_center").val();
    var period = answer.split('/');
    var q = $("#cashfile_q").val();

    var project_site_id = $("#cashfile_project_site_id").val();

    if( $("#cashfile_remove_resigned").is(":checked") ){
      var remove_resigned   = 1;  
    }else{
      var remove_resigned   = false;
    }

    if( $("#cashfile_remove_terminated").is(":checked") ){
      var remove_terminated   = 1;  
    }else{
      var remove_terminated   = false;
    }    
    
    if( $("#cashfile_remove_endo").is(":checked") ){
      var remove_endo   = 1;  
    }else{
      var remove_endo   = false;
    } 

    if( $("#cashfile_remove_inactive").is(":checked") ){
      var remove_inactive   = 1;  
    }else{
      var remove_inactive   = false;
    } 

    if( $("#show_yearly_bonus").is(":checked") ){
      var show_yearly_bonus = 1;
    }else{
      var show_yearly_bonus = false;
    } 

    if( $("#payslip_bonus_service_award").is(":checked") ){
      var bonus_service_award = 1;
    }else{
      var bonus_service_award = false;
    }

    if( $("#add_13th_month_pay").is(":checked") ){
      var add_13th_month_pay = 1;
    }else{
      var add_13th_month_pay = false;
    }   

    if( $("#add_bonus_service_award").is(":checked") ){
      var add_bonus_service_award = 1;
    }else{
      var add_bonus_service_award = false;
    }

    if( $("#payslip_converted_leaves").is(":checked") ){
      var add_converted_leaves = 1;
    }else{
      var add_converted_leaves = false;
    }  


    location.href=base_url + 'payroll/download_cash_file?from='+ period[0] +'&to='+ period[1] + '&remove_resigned=' + remove_resigned + '&bonus_service_award=' + bonus_service_award + '&add_13th_month_pay=' + add_13th_month_pay + '&remove_terminated=' + remove_terminated + '&remove_endo=' + remove_endo + '&remove_inactive=' + remove_inactive + '&q=' + q + '&show_yearly_bonus=' + show_yearly_bonus + '&add_bonus_service_award=' + add_bonus_service_award + '&add_converted_leaves=' + add_converted_leaves + '&frequency=' + frequency + '&project_site_id=' + project_site_id; 

    /*
    var answer = $('#cutoff_cashfile').val();
    var period = answer.split('/');    
    var employee_type = $("#employee_type_cashfile").val();
    location.href=base_url + 'payroll/download_cash_file?from='+ period[0] +'&to='+ period[1] + '&employee_type=' + employee_type;
    */
}
</script>
<h2><?php echo $title;?></h2>
<form id="cash_file_form" name="form1" onsubmit="return checkForm()" method="post" action="">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td style="width:17%;">Frequency Type</td>
          <td class="form-inline">:
            <select id="cashfile-report-frequency-selector" name="frequency">
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
          <td>Year</td>
          <td class="form-inline">:
            <select id="cashfile-report-year-selector">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>     
        <tr>
            <td>Payroll Period</td>
            <td>:
                <div class="cashfile-pay-period-container" style="display:inline-block;"></div><br />                   
               <!--  <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="show_yearly_bonus" id="show_yearly_bonus" value="1" />Show 13th month only</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="payslip_bonus_service_award" id="payslip_bonus_service_award" value="1" />Show Bonus and Service Award only</label>  
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="add_bonus_service_award" name="add_bonus_service_award" value="1" />Add Bonus and Service Award to earnings</label>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="payslip_converted_leaves" value="1" />Add Converted Leaves</label>                           
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" name="add_13th_month_pay" id="add_13th_month_pay" value="1" />Add 13th Month </label> -->                             
            </td>
        </tr>
        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="q" id="cashfile_q">
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
                  <label class="checkbox"><input type="checkbox" name="remove_resigned" id="cashfile_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" name="remove_terminated" id="cashfile_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" name="remove_endo" id="cashfile_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" name="remove_inactive" id="cashfile_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr> 
        <tr>
          <td><!-- Cost Center -->Project Site</td>
          <td>: 
              <!--<select name="cost_center" id="cost_center">
                  <option selected="selected" value="all">All</option>
                  <?php ksort($emp_cost_center); ?>
                  <?php foreach($emp_cost_center as $emp_cost_key => $emp_cost) { ?>
                          <option value="<?php echo $emp_cost_key; ?>"><?php echo $emp_cost; ?></option>
                  <?php } ?>
              </select>  -->

              <select class="select_option" name="cashfile_project_site_id" id="cashfile_project_site_id" >
                <option value="all">All Project Site</option>
                     <?php foreach($project_site as $key=>$value){  ?>
                          <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>

                      <?php } ?>
                </select>
              

          </td>
        </tr>       
        </table>

	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input onmouseup="javascript:downloadCashFileReport()" class="blue_button" type="button" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
