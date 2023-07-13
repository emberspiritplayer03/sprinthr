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
  $("#activity-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'activity-pay-period-container',$("#activity-report-frequency-selector").val());
  });

  $("#activity-report-frequency-selector").change(function(){    
     changePayPeriodByYear($("#activity-report-year-selector").val(),'activity-pay-period-container',this.value);
  });

  changePayPeriodByYear($("#activity-report-year-selector").val(),'activity-pay-period-container',$("#activity-report-frequency-selector").val());
});
</script>
<h2><?php echo $title;?></h2>
<form id="activity_form" name="activity_form" method="post" action="<?php echo url('activity/download_activity_reports'); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
      <table width="100%"> 
        <tr>
          <td style="width:17%;">Frequency Type</td>
          <td class="form-inline">:
            <select id="activity-report-frequency-selector" name="frequency_id">
                <option value = "1">Bi-Monthly</option>
                <option value = "2">Weekly</option> 
                 <option value = "3">Monthly</option>    
            </select>
          </td>
        </tr>  
        <tr>
          <td style="width:17%;">Year</td>
          <td class="form-inline">:
            <select id="activity-report-year-selector">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>     
        <tr>
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <div class="activity-pay-period-container" style="display:inline-block;"></div><br />                         
            </td>

       <!--     
        </tr>
        <?php if($is_with_confi_nonconfi_option){ ?>
          <tr>
              <td>Employee Type</td>
              <td>: 
                  <select name="activity_q">
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
                  <label class="checkbox"><input type="checkbox" name="activity_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                  <label class="checkbox"><input type="checkbox" name="activity_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                  <label class="checkbox"><input type="checkbox" name="activity_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                  <label class="checkbox"><input type="checkbox" name="activity_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                </div>
            </td>
        </tr>-->
        <tr>
          <td><!-- Cost Center -->Project Site</td>
          <td>:  
              <select name="project_site_id" id="project_site_id">
                <option selected="selected" value="all">All</option>
                <?php foreach($project_sites as $key => $project_site) { ?>
                    <option value="<?php echo $project_site->getId(); ?>"> <?php echo $project_site->getName(); ?> </option>
                <?php } ?>
              </select>                    
          </td>
        </tr> 

        <tr>
        	<td>Report Type</td>
        	<td>:  
              <select name="report_type" id="report_type">
                <option selected="selected" value="summarized">Summarized</option>
                 <option value="detailed">Detailed</option>
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
