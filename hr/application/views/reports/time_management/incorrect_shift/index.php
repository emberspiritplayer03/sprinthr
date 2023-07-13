<style>
.textboxlist{display:inline-block;}
.apply-to-container p{ font-size:12px;padding:8px;display: block;width:97%;height:20px;background-color:#E3E3E3;font-weight: bold}
.apply-to-container table{margin-left:37px;}
.custom-criteria-date-range-box{padding:7px;background-color: #e3e3e3;border:solid 1px #999999;margin-top: 7px;}
ul.benefit-criteria li{list-style: none;display: inline-block;width:231px;margin:10px;vertical-align: top;}
.hdr-filter-data{padding:8px;background-color: #e3e3e3;width: 97%; font-weight: bold;}
</style>
<script>
$(function(){
    $("#frm-report-inc-shift").validationEngine({scroll:false});   
    $("#inc_shift_birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
    $("#inc_shift_date_from").datepicker({  
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
            $("#inc_shift_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
        }
    }); 

    $("#inc_shift_date_to").datepicker({    
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
        
        }
    }); 

    var t = new $.TextboxList('#employee_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
          }
      }});

      var t = new $.TextboxList('#dept_section_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_department_autocomplete'}
          }
      }});

      var t = new $.TextboxList('#employment_status_id', {
        unique: true,
        plugins: {
          autocomplete: {
            minLength: 2,       
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employment_status_autocomplete'}
          }
      }});
      
      $(".chk-inc-shift-all-employees").click(function(){
        if( $(this).prop("checked") ){
            $(".apply-to-tr").hide();
        }else{
            $(".apply-to-tr").show(); 
        }         
      });
});
</script>
<h2><?php echo $title; ?></h2>
<div id="form_main" class="employee_form">
<form id="frm-report-inc-shift" name="form1" method="post" action="<?php echo url('reports/download_incorrect_shift_data'); ?>">
     <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From :</td>
                <td><input type="text" id="inc_shift_date_from" name="date_from" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">To :</td>
                <td><input type="text" id="inc_shift_date_to" name="date_to" class="validate[required]" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <p class="hdr-filter-data pull-left">Filter Data <label class="checkbox pull-right"><input type="checkbox" class="chk-inc-shift-all-employees" name="all_employees" value="1" /> All Employees</label></p>        
        <div class="clear"></div>
        <table width="100%">           
          <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Employee :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="employee_id" id="employee_id" value="" />                        
               </td>
          </tr>
          <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Department / Section :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="dept_section_id" id="dept_section_id" value="" />                        
               </td>
          </tr>
          <tr class="apply-to-tr">
               <td style="width:15%" align="left" valign="middle">Employment status :</td>
               <td style="width:15%" align="left" valign="middle"> <input class="validate[required] text-input" type="text" name="employment_status_id" id="employment_status_id" value="" />                        
               </td>
          </tr>   
         
          <tr>
              <td></td>
              <td class="form-inline">                
                  <div class="rep-checkbox-container">
                    <label class="checkbox"><input type="checkbox" name="incshift_remove_resigned" checked="checked" value="1" />Remove Resigned Employees</label> 
                    <label class="checkbox"><input type="checkbox" name="incshift_remove_terminated" checked="checked" value="1" />Remove Terminated Employees</label>
                    <label class="checkbox"><input type="checkbox" name="incshift_remove_endo" checked="checked" value="1" />Remove End of Contract</label>
                    <label class="checkbox"><input type="checkbox" name="incshift_remove_inactive" checked="checked" value="1" />Remove Inactive Employees</label>
                  </div>
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