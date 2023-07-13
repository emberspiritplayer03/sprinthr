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
            remote: {url: base_url + 'autocomplete/ajax_get_employees_autocomplete'}
        }
    }});
    
    t.addEvent('blur',function(o) {
        load_show_employee_request_approvers();
    });

    var t2 = new $.TextboxList('#signatory_employee_id', {max:1,plugins: {
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
    
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-government-remittances" name="form1" method="post" action="<?php echo url('reports/download_government_remittances_data'); ?>">
    <div id="form_default">
        <h3 class="section_title">Date </h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td><input type="text" id="government_remittances_date_from" class="validate[required]" name="date_from" style="width: 56%;" /></td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td><input type="text" id="government_remittances_date_to" class="validate[required]"  name="date_to" style="width: 56%;" /></td>
            </tr>
        </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
    <table width="100%">
        <tr>
            <td class="field_label">Employee Name: </td>
            <td><input type="text" id="h_employee_id" name="h_employee_id" class="validate[required]" /></td>
        </tr>
        <tr>
            <td class="field_label">Type:</td>
            <td>
                <select name="remittance_type">
                    <option value="pagibig_contribution">Pagibig Contribution</option>
                    <option value="pagibig_loan">Pagibig Loan</option>
                    <option value="sss_contribution">SSS Contribution</option>
                    <option value="sss_loan">SSS Loan</option>
                    <option value="philhealth">Philhealth</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="field_label">Purpose:</td>
            <td><input type="text" id="purpose" class="validate[required]"  name="purpose" style="width: 56%;" /></td>
        </tr>
        <tr>
            <td class="field_label">Signatory Name: </td>
            <td><input type="text" id="signatory_employee_id" name="signatory_employee_id" class="validate[required]"  /></td>
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
