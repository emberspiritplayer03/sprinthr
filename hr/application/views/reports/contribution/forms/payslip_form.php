<style>
.rep-checkbox-container{margin-left:9px;}
.rep-checkbox-container .checkbox{margin-right:3px;}
</style>
<script>
$(function(){
  $("#payslip-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'payslip-pay-period-container',$("#payslip-report-frequency-selector").val());
  });

  $("#payslip-report-frequency-selector").change(function(){    
     changePayPeriodByYear($("#payslip-report-year-selector").val(),'payslip-pay-period-container',this.value);
  });

  changePayPeriodByYear($("#payslip-report-year-selector").val(),'payslip-pay-period-container',$("#payslip-report-frequency-selector").val());
});
$("#payslip_form #payslip_date_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#payslip_form #payslip_date_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
//$("#payslip_form #payslip_payout_date").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});

/*function checkForm() {
	var date_from = $('#payslip_form #date_from').val();
	var date_to = $('#payslip_form #date_to').val();
	//var payout_date = $('#payslip_form #payout_date').val();
	if (date_from == '' || date_to == '') {
		return false;	
	} else {
		return true;	
	}
}*/

function downloadReport() {
    var frequency = $("#payslip-report-frequency-selector").val();
    var answer = $('#cutoff_period').val();
    var period = answer.split('/');
    var cost_center = $("#cost_center").val();
    var q = $("#q").val();

    var project_site_id = $("#project_site_id").val();

    if( $("#payslip_remove_resigned").is(":checked") ){
      var remove_resigned   = 1;  
    }else{
      var remove_resigned   = false;
    }

    if( $("#payslip_remove_terminated").is(":checked") ){
      var remove_terminated   = 1;  
    }else{
      var remove_terminated   = false;
    } 

    if( $("#payslip_remove_endo").is(":checked") ){
      var remove_endo   = 1;  
    }else{
      var remove_endo   = false;
    } 

    if( $("#payslip_yearly_bonus").is(":checked") ){
      var yearly_bonus = 1;
    }else{
      var yearly_bonus = false;
    }

    if( $("#payslip_converted_leaves").is(":checked") ){
      var show_converted_leaves_only = 1;
    }else{
      var show_converted_leaves_only = false;
    }    

    if( $("#add_13th_month_pay").is(":checked") ){
      var add_13th_month_pay = 1;
    }else{
      var add_13th_month_pay = false;
    } 

    if( $("#payslip_remove_inactive").is(":checked") ){
      var remove_inactive   = 1;  
    }else{
      var remove_inactive   = false;
    }     

    if( $("#payslip_bonus_service_award").is(":checked") ){
      var bonus_service_award = 1;
    }else{
      var bonus_service_award = false;
    }

    if( $("#add_bonus_service_award").is(":checked") ){
      var add_bonus_service_award = 1;
    }else{
      var add_bonus_service_award = false;
    }
    

    location.href=base_url + 'payslip/download_payslip?from='+ period[0] +'&to='+ period[1] + '&remove_resigned=' + remove_resigned + '&remove_terminated=' + remove_terminated + '&remove_endo=' + remove_endo + '&remove_inactive=' + remove_inactive + '&show_converted_leaves_only=' + show_converted_leaves_only + '&bonus_service_award=' + bonus_service_award + '&yearly_bonus=' + yearly_bonus + '&add_13th_month_pay=' + add_13th_month_pay + '&add_bonus_service_award=' + add_bonus_service_award + '&q=' + q + '&frequency=' + frequency + '&project_site_id=' + project_site_id; 
}
</script>
<h2><?php echo $title;?></h2>
<form id="payslip_form" name="form1" onsubmit="return downloadReport()" method="post" action="">
<div id="form_main" class="employee_form">
	<div id="form_default">      
      <table width="100%">
        <tr>
          <td style="width:17%;">Frequency Type</td>
          <td class="form-inline">:
            <select id="payslip-report-frequency-selector">
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
          <td style="width:17%;">Year</td>
          <td class="form-inline">:
            <select id="payslip-report-year-selector">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>     
        <tr>
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <div class="payslip-pay-period-container" style="display:inline-block;"></div><br />    
<!--                 <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="payslip_yearly_bonus" value="1" />Show 13th month only</label>             
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="payslip_bonus_service_award" value="1" />Show Bonus and Service Award only</label>
                <br />
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="add_converted_leave" name="add_converted_leave" value="1" />Add Converted Leave to earnings</label></br>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="add_bonus_service_award" name="add_bonus_service_award" value="1" />Add Bonus and Service Award to earnings</label></br>
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="payslip_converted_leaves" value="1" />Add Converted Leaves</label>                             
                <label class="checkbox" style="margin-left:10px;"><input type="checkbox" id="add_13th_month_pay" value="1" />Add 13th Month</label>   -->                           
            </td>
        </tr>
        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="q" id="q">
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
                  <label class="checkbox"><input type="checkbox" id="payslip_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" id="payslip_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" id="payslip_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" id="payslip_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr>
         <tr>
          <td><!-- Cost Center -->Project Site</td>
          <td>: 
            <!--
              <select name="cost_center" id="cost_center">
                  <option selected="selected" value="all">All</option>
                  <?php ksort($emp_cost_center); ?>
                  <?php foreach($emp_cost_center as $emp_cost_key => $emp_cost) { ?>
                          <option value="<?php echo $emp_cost_key; ?>"><?php echo $emp_cost; ?></option>
                  <?php } ?>
              </select>  -->
              <select class="select_option" name="project_site_id" id="project_site_id" >
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
            <td><input onmouseup="javascript:downloadReport()" class="blue_button" type="button" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
