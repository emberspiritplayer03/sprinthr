<script>
$(function(){
  $("#add_deductions_form").validationEngine({scroll:false});
  $('#add_deductions_form').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
            dialogOkBox(o.message,{});           
            hide_add_deductions_form();   
            load_approved_deductions_list_dt('"' + o.eid + '"');
            load_sum_approved_deductions('"' + o.eid + '"');

            var $dialog = $('#action_form');                    
            $dialog.dialog("destroy");                    

          } else {  
            hide_add_deductions_form();                          
            dialogOkBox(o.message,{});          
          }                   
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });

  var t = new $.TextboxList('#employee_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'deductions/ajax_get_employees_autocomplete'}      
        }
    }});

  var t2 = new $.TextboxList('#department_section_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'autocomplete/ajax_get_all_department_type_autocomplete'}     
        }
    }});

  var t3 = new $.TextboxList('#employment_status_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'autocomplete/ajax_get_employment_status_autocomplete'}     
        }
    }});
});


/*$(document).ready(function() {		
	$('#add_deductions_form').validationEngine({scroll:false});	
		
	$('#add_deductions_form').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				load_deductions_list_dt('"' + o.eid + '"');			
				hide_add_deductions_form();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});						
			} else {
				hide_add_deductions_form();
				closeDialog('#' + DIALOG_CONTENT_HANDLER);	
				dialogOkBox(o.message,{});			
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Saving...');
		}
	});		
	
	
	
});
*/
function chkEmployee(chk) {
  if(chk.checked){    
    $("#all_employee").show();
    $("#autcomplete_emp").hide();
     $("#all_department_section").show();
    $("#autcomplete_dept_sect").hide();
    $("#all_employment_status").show();
    $("#autcomplete_emp_status").hide();
  }else{
    $("#all_employee").hide();
    $("#autcomplete_emp").show();
    $("#all_department_section").hide();
    $("#autcomplete_dept_sect").show();
    $("#all_employment_status").hide();
    $("#autcomplete_emp_status").show();
  }
}

function chkDepartmentSection(chk) {
  if(chk.checked){    
    $("#all_department_section").show();
    $("#autcomplete_dept_sect").hide();
    $("#all_employee").show();
    $("#autcomplete_emp").hide();
  }else{
    $("#all_department_section").hide();
    $("#autcomplete_dept_sect").show();
    $("#all_employee").hide();
    $("#autcomplete_emp").show();
  }
}

function chkEmploymentStatus(chk) {
  if(chk.checked){    
    $("#all_employment_status").show();
    $("#autcomplete_emp_status").hide();
    $("#all_employee").show();
    $("#autcomplete_emp").hide();
  }else{
    $("#all_employment_status").hide();
    $("#autcomplete_emp_status").show();
    $("#all_employee").hide();
    $("#autcomplete_emp").show();
  }
}
</script>
<div id="formcontainer">
<form id="add_deductions_form" name="add_deductions_form" action="<?php echo url('deductions/_save_deduction'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="cutoff_period_id" name="cutoff_period_id" value="<?php echo $eid; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add New Deduction</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table>
        	 <tr>
               <td class="field_label">Title:</td>
               <td>
               		<input class="validate[required] input-large" type="text" name="e_title" id="e_title" value="" style="width:292px;" />
               </td>
             </tr>     
             <tr>
               <td class="field_label">Employee:</td>
               <td>
               		<div id="autcomplete_emp">
               			<input class="validate[required] input-large" type="text" name="employee_id" id="employee_id" value="" />
                    </div>
                    <div id="all_employee" style="display:none;">
                    	<input class="input-large" type="text" name="all_emp" id="disabledInput" disabled="" value="All Employee" style="width:292px;" />
                    </div>
                    <label class="checkbox">
                    	<input type="checkbox" onchange="javascript:chkEmployee(this);" id="apply_to_all_employee" name="apply_to_all_employee" />Apply to all Employee
                    </label>
               
               </td>
             </tr>
             <tr>
               <td class="field_label">Department/Section:</td>
               <td>
                  <div id="autcomplete_dept_sect">
                    <input class="validate[required] input-large" type="text" name="department_section_id" id="department_section_id" value="" />
                    </div>
                    <div id="all_department_section" style="display:none;">
                      <input class="input-large" type="text" name="all_dept_sect" id="disabledInput" disabled="" value="All Department/Section" style="width:292px;" />
                    </div>
                   <!--  <label class="checkbox">
                      <input type="checkbox" onchange="javascript:chkDepartmentSection(this);" id="apply_to_all_department_section" name="apply_to_all_department_section_id" />Apply to all Department/Section
                    </label> -->
               
               </td>
             </tr>  
             <tr>
               <td class="field_label">Employment Status:</td>
               <td>
                  <div id="autcomplete_emp_status">
                    <input class="validate[required] input-large" type="text" name="employment_status_id" id="employment_status_id" value="" />
                    </div>
                    <div id="all_employment_status" style="display:none;">
                      <input class="input-large" type="text" name="all_emp_status" id="disabledInput" disabled="" value="All Employment Status" style="width:292px;" />
                    </div>
                    <!-- <label class="checkbox">
                      <input type="checkbox" onchange="javascript:chkEmploymentStatus(this);" id="apply_to_all_employment_status" name="apply_to_all_employment_status" />Apply to all Employment Status
                    </label> -->
               
               </td>
             </tr>                    
             <tr>
               <td class="field_label">Amount:</td>
               <td>
               		 <div class="input-append">
                     	<input style="width:254px;height:18px;" class="validate[required,custom[money]] text-input" type="text" name="amount" id="amount" value="" />
                    	<span class="add-on">Php</span>
                    </div>               		
                   <!-- <label class="checkbox">
                    	<input type="checkbox" id="is_taxable" name="is_taxable" />Taxable
                    </label>-->
               </td>
             </tr>
             <tr>
               <td class="field_label">Add to Payroll Period:</td>
               <td>
               		<input class="input-large" type="text" name="all_emp" id="disabledInput" disabled="" value="<?php echo $cutoff_period; ?>" style="width:292px;font-weight:bold;" />
               		<!--<select class="validate[required] select_option" name="payroll_period_id" id="payroll_period_id">        
               		<?php //foreach($cutoff_periods as $ct){ ?>
                    	<option value="<?php //echo Utilities::encrypt($ct->getId()); ?>"><?php //echo $ct->getStartDate() . ' to ' . $ct->getEndDate(); ?></option>
                    <?php //} ?>
                    </select>-->
               </td>
             </tr>                                                   
             <tr>
               <td class="field_label">Remarks:</td>
               <td>
               		<textarea class="input-large" rows="3" id="remarks" name="remarks"></textarea>               		
               </td>
             </tr>                                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_add_deductions_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

