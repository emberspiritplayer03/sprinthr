<h2><?php echo $title; ?></h2>
<script>
$(function(){
    $("#frm-coe").validationEngine({scroll:false}); 

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
    
});
</script>
<div id="form_main" class="employee_form">
<form id="frm-coe" name="form1" method="post" action="<?php echo url('reports/download_coe_data'); ?>">
     <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Employee Name: </td>
                <td><input type="text" id="h_employee_id" name="h_employee_id" class="validate[required]" /></td>
            </tr>
            <tr>
                <td class="field_label">Reason: </td>
                <td><input type="text" id="coe_reason" name="coe_reason" /></td>
            </tr>
            <tr>
                <td class="field_label">Signatory: </td>
                <td><input type="text" id="coe_signatory" name="coe_signatory" /></td>
            </tr>
            <tr>
                <td class="field_label">Signatory Position: </td>
                <td><input type="text" id="coe_position" name="coe_position" /></td>
            </tr>
        </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">File Type:</td>
                <td>
                    <select class="select_option" name="report_type" id="report_type">
                        <!-- <option value="PDF">PDF</option> -->
                        <option selected="selected" value="EXCEL">EXCEL</option> 
                    </select>              
                </td>
            </tr>
            <tr>
                <td class="field_label">Template:</td>
                <td>
                    <select class="select_option" name="template" id="template">
                        <option selected="selected" value="DEFAULT">DEFAULT</option>
                    </select>              
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
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
