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

   $("#per_month_pagibig").hide();

    $("#pagibig-report-type-selector").change(function(){ 

        if($("#pagibig-report-type-selector").val() == 'per_pay_period'){

            $("#per_pay_period_pagibig").show();
            $("#per_month_pagibig").hide();

        } else{

            $("#per_pay_period_pagibig").hide();
            $("#per_month_pagibig").show();
        }  
     
  });


  $("#pagibig-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'pagibig-pay-period-container',$("#pagibig-report-frequency-selector").val());
  });

  $("#pagibig-report-frequency-selector").change(function(){    
     changePayPeriodByYear($("#pagibig-report-year-selector").val(),'pagibig-pay-period-container',this.value);
  });

  changePayPeriodByYear($("#pagibig-report-year-selector").val(),'pagibig-pay-period-container',$("#pagibig-report-frequency-selector").val());
});
</script>
<h2><?php echo $title;?></h2>
<form id="pagibig_form" name="pagibig_form" method="post" action="<?php echo url('reports/download_pagibig'); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
      <table width="100%"> 
        <tr>
          <td style="width:17%;">Frequency Type</td>
          <td class="form-inline">:
            <select id="pagibig-report-frequency-selector" name="frequency_id">
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
            <select id="pagibig-report-year-selector" name="year_selecter">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>   


          <tr>
          <td style="width:17%;">Report Type:</td>
          <td class="form-inline">:
            <select id="pagibig-report-type-selector" name="report_type">
                <option value="per_pay_period">Per Pay Period</option>
                 <option value="per_month">Per Month</option>
            </select>
          </td>
        </tr> 


        <tr id="per_pay_period_pagibig">
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <div class="pagibig-pay-period-container" style="display:inline-block;"></div><br />                                
            </td>
        </tr>

         <tr id="per_month_pagibig">
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <select id="cutoff_period2" name="cutoff_period2">

                  <?php

                      for($x = 1; $x <= 12; $x++ ){

                           $month_name = date('F', $month);
                           $month_number = date('m', $month);
                          echo '<option value="'. $month_number. '">'.$month_name.'</option>';
                          $month = strtotime('+1 month', $month);

                      }

                  ?>

                   
                </select>                             
            </td>
        </tr>

        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="pagibig_q">
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
                  <label class="checkbox"><input type="checkbox" name="pagibig_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" name="pagibig_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" name="pagibig_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" name="pagibig_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr>
        
         <tr>
          <td><!-- Cost Center -->Project Site</td>
          <td>: 
             <!-- <select name="cost_center" id="cost_center">
                  <option selected="selected" value="all">All</option>
                  <?php ksort($emp_cost_center); ?>
                  <?php foreach($emp_cost_center as $emp_cost_key => $emp_cost) { ?>
                          <option value="<?php echo $emp_cost_key; ?>"><?php echo $emp_cost; ?></option>
                  <?php } ?>
              </select>   -->

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
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
