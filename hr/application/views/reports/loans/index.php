<style>
.textboxlist{display:inline-block;}
.apply-to-container p{ font-size:12px;padding:8px;display: block;width:97%;height:20px;background-color:#E3E3E3;font-weight: bold}
.apply-to-container table{margin-left:37px;}
.custom-criteria-date-range-box{padding:7px;background-color: #e3e3e3;border:solid 1px #999999;margin-top: 7px;}
ul.benefit-criteria li{list-style: none;display: inline-block;width:231px;margin:10px;vertical-align: top;}
.hdr-filter-data{padding:8px;background-color: #e3e3e3;width: 97%; font-weight: bold;}
.cutoff-form-container,.monthly-form-container{margin-top:15px;width: 70%; border: 1px solid #BBBBBB;}
</style>
<script>
$(function(){
    $("#frm-report-loan-data").validationEngine({scroll:false});   
    var t = new $.TextboxList('#loans_employee_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
          }
      }});

      var t = new $.TextboxList('#loans_dept_section_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_department_autocomplete'}
          }
      }});

      var t = new $.TextboxList('#loans_employment_status_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employment_status_autocomplete'}
          }
      }});

      $(".chk-loan-data-all-employees").click(function(){
        if( $(this).prop("checked") ){
            $(".apply-to-tr").hide();
        }else{
            $(".apply-to-tr").show(); 
        }         
      });

      $(".loan-period").click(function(){
        var loan_period_selected = $(this).val();
        if( loan_period_selected == 1 ){
          $(".cutoff-form-container").show();
          $(".monthly-form-container").hide();
        }else{
          $(".cutoff-form-container").hide();
          $(".monthly-form-container").show();
        }
      });

      $(".cutoff-form-container").show();
      $(".monthly-form-container").hide();
});
</script>
<h2><?php echo $title; ?></h2>
<div id="form_main" class="employee_form">
<form id="frm-report-loan-data" name="frm-report-loan-data" method="post" action="<?php echo url('reports/download_loan_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">Loan Type :</td>
                <td>
                  <select style="width:30%;" name="loan_type">
                  <?php foreach($loan_types as $type){ ?>
                    <option value="<?php echo $type['id']; ?>"><?php echo $type['loan_type']; ?></option>
                  <?php } ?>
                  </select>                
                </td>
            </tr>
            <tr>
                <td class="field_label">Loan Period :</td>
                <td>
                  <select style="width:30%;" class="loan-period" name="loan_report_type">
                    <option value="1">Cutoff</option>
                    <option value="2">Monthly</option>
                  </select>
                  <div class="cutoff-form-container">
                    <table width="50%">
                      <tr><td style="width:31%;background-color:#E3E3E3;">Select Year :</td><td style="width:31%;background-color:#E3E3E3;">Select Cutoff</td></tr>
                      <tr>
                        <td style="width:31%;background-color:#E3E3E3;">
                          <select style="width:100%;" name="year_tag[1]">
                            <?php foreach( $year_tags as $y ){ ?>
                              <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td style="width:40%;background-color:#E3E3E3;">
                          <select style="width:72%;" name="period[1]">
                            <?php 
                              foreach ($cutoff_periods as $key => $frequency){
                                foreach( $frequency as $subKey => $f ){
                            ?>
                              <option value="<?php echo $f; ?>"><?php echo $key . " " . strtoupper($subKey) ?></option>
                            <?php
                                }
                              }
                            ?>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="monthly-form-container">
                    <table width="47%">
                      <tr><td style="width:31%;background-color:#E3E3E3;">Select Year :</td><td style="width:31%;background-color:#E3E3E3;">Select Month</td></tr>
                      <tr>
                        <td style="width:31%;background-color:#E3E3E3;">
                          <select style="width:100%;" name="year_tag[2]">
                            <?php foreach( $year_tags as $y ){ ?>
                              <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                        <td style="width:40%;background-color:#E3E3E3;">
                          <select style="width:72%;" name="period[2]">
                            <?php foreach($months_tags as $m){ ?>
                              <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                            <?php } ?>
                          </select>
                        </td>
                      </tr>
                    </table>
                  </div>
                </td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <p class="hdr-filter-data pull-left">Filter Data <label class="checkbox pull-right"><input type="checkbox" class="chk-loan-data-all-employees" name="all_employees" value="1" /> All Employees</label></p>        
        <div class="clear"></div>
        <table width="100%">           
            <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Employee :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="loans_employee_id" id="loans_employee_id" value="" />                        
               </td>
          </tr>
          <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Department / Section :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="loans_dept_section_id" id="loans_dept_section_id" value="" />                        
               </td>
          </tr>
          <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Employment status :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="loans_employment_status_id" id="loans_employment_status_id" value="" />                        
               </td>
          </tr>         
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Search"></td>
            </tr>
        </table>
    </div>
</form>
</div>
