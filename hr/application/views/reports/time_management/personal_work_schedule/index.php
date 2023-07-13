<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});


var t = new $.TextboxList('#employee_id', {plugins: {
	autocomplete: {
		minLength: 3,
		onlyFromValues: true,
		queryRemote: true,
		remote: {url: base_url + 'employee/ajax_get_employees_autocomplete'}
	
	}
}});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/download_personal_work_schedule'); ?>">
     <div id="form_default">
        <h3 class="section_title">&nbsp;</h3>
        <table width="100%">
            <tr>
                <td class="field_label">Employee Name</td>
                <td><input type="text" name="employee_id" id="employee_id" /></td>
            </tr>
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default"></div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Download"></td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
