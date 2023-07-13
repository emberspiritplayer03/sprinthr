<script type="text/javascript">
$(function() {
	$("#date_created").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
	$("#examination_form").validationEngine({scroll:false});
	$('#examination_form').ajaxForm({
		success:function(o) {
			if(o.is_success ==1){
				load_add_examination_confirmation(o.eid); 
			}else{			
				dialogOkBox(o.message,{});		
			}
		},
		dataType:'json',
		beforeSubmit:function() {
		showLoadingDialog('Saving...');
		}	
		});
	
	var emp_pos = new $.TextboxList('#emp_pos', {unique: true,plugins: {
			autocomplete: {
				minLength: 1,
				onlyFromValues: true,
				queryRemote: true,
				remote: {url: base_url + 'settings/ajax_get_positions_autocomplete'}
			
			}
		}});
	
	$('ul.textboxlist-bits').attr("title","Type position/job to see suggestions.");
	$('ul.textboxlist-bits').tipsy({gravity: 's'});	
});

function disableTextBox(obj_txtboxlist_id,obj_default_id,checkbox_id){	
	if($("#" + checkbox_id).is(':checked')){
		$("#" + obj_txtboxlist_id).show();
		$("#" + obj_default_id).hide();
	}else{		
		$("#" + obj_txtboxlist_id).hide();
		$("#" + obj_default_id).show();
	}
}
</script>
<div class="formwrap inner_form">
<form action="<?php echo url('settings/_insert_examination'); ?>" method="post"  name="examination_form" id="examination_form" >
<input type="hidden" id="company_structure_id" name="company_structure_id" value="<?php echo $company_structure_id; ?>"  />
<h3 class="form_sectiontitle"><span>Add Examination</span></h3>
<div id="form_main">
    <div id="form_default">
        <h3 class="section_title">Examination Detail</h3>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">Exam Title:</td>
            <td><input name="title" type="text" class="validate[required] text" id="title" value="" /> <br />
              <small><em>(HR Examination, Production Exam)</em></small></td>
          </tr>
          <tr>
            <td class="field_label">Apply to job(s):</td>
            <td>
            	<div id="txt_positions">
            		<input type="text" name="emp_pos" id="emp_pos" />
            	</div>
            	<div id="txt_hidden_positions" style="display:none;">
               	<input type="text" name="dummy_pos" id="dummy_pos" disabled="disabled" style="width:290px" value="Apply to all jobs" />
               </div>
               <label class="checkbox">
               	<input id="apply_to_all_jobs" value="Yes" name="apply_to_all_jobs" type="checkbox" onclick="javascript:disableTextBox('txt_hidden_positions','txt_positions','apply_to_all_jobs');" />Apply to all jobs
              	</label>        
            </td>
          </tr>
          <tr>
            <td class="field_label">Description:</td>
            <td><textarea name="description" id="description"></textarea></td>
          </tr>
          <tr>
            <td class="field_label">Duration:</td>
            <td>
            	<input type="text" title="Set 0 if none" placeholder="Hours" style="width:10%;" value="" name="days" class="validate[required,custom[integer]] text" id="days" />
                <input type="text" title="Set 0 if none" placeholder="Minutes" style="width:10%;" value="" name="hours" class="validate[required,custom[integer]] small-input text" id="hours" />
                <input type="text" title="Set 0 if none" placeholder="Seconds" style="width:10%;" value="" name="minutes" class="validate[required,custom[integer]] small-input text" id="minutes" />
            </td>
          </tr>   
          <tr>
            <td class="field_label">Passing:</td>
            <td>            	
                <div class="input-append">
                <input type="text" value="" name="passing_percentage" class="validate[required,custom[integer]] input-mini" id="passing_percentage" />
                <span class="add-on" style="height:17px;">%</span>
                </div>
            </td>
          </tr>
          <tr>
            <td class="field_label">Date Created:</td>
            <td><input type="text" value="" name="date_created" class="validate[required] text" id="date_created" /></td>
          </tr>
          <tr>
            <td class="field_label">Created by:</td>
            <td><input type="text" value="<?php echo $ename; ?>" name="created_by" class="validate[required] text" id="created_by" /></td>
          </tr>
        </table>    
    </div>
   
    <div id="form_default" class="form_action_section">
    	<table>
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                	<input type="submit" value="Add Examination" class="curve blue_button" />
            		<a href="javascript:cancel_add_examination_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div>
</form>
</div>
<script>
$('#days').tipsy({gravity: 's'});
$('#hours').tipsy({gravity: 's'});
$('#minutes').tipsy({gravity: 's'});
</script>
