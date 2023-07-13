<h2><?php echo $title; ?></h2>
<script>
$(function(){
    $("#frm-government-remittances").validationEngine({scroll:false}); 

    $("#government_remittances_date_from").datepicker({   
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
            $("#government_remittances_date_to").datepicker('option',{minDate:$(this).datepicker('getDate')});
        }
    }); 

    $("#government_remittances_date_to").datepicker({ 
        dateFormat:'yy-mm-dd',
        changeMonth:true,
        changeYear:true,
        showOtherMonths:true,
        onSelect    :function() { 
        
        }
    }); 

    var t = new $.TextboxList('#h_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 3,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_active_and_resigned_employees'}
        }
    }});
    
    t.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    });

    var t2 = new $.TextboxList('#prepared_by_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 3,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        }
    }});
    
    t2.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    }); 

    var t3 = new $.TextboxList('#checked_by_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 3,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        }
    }});
    
    t3.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    }); 

    var t4 = new $.TextboxList('#approved_by_employee_id', {max:1,plugins: {
        autocomplete: {
            minLength: 3,
            onlyFromValues: true,
            queryRemote: true,
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        }
    }});
    
    t4.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    });     
    
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-government-remittances" name="form1" method="post" action="<?php echo url('reports/download_last_pay_data'); ?>">
    
    <div id="form_default">
    <table width="100%">
        <tr>
            <td class="field_label">Employee Name: </td>
            <td><input type="text" id="h_employee_id" name="h_employee_id" class="validate[required]" /></td>
        </tr>
        <tr>
            <td class="field_label">Prepared by: </td>
            <td><input type="text" id="prepared_by_employee_id" name="prepared_by_employee_id" class="validate[required]"  /></td>
        </tr>
        <tr>
            <td class="field_label">Checked by: </td>
            <td><input type="text" id="checked_by_employee_id" name="checked_by_employee_id" class="validate[required]"  /></td>
        </tr>
        <tr>
            <td class="field_label">Approved by: </td>
            <td><input type="text" id="approved_by_employee_id" name="approved_by_employee_id" class="validate[required]"  /></td>
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
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
