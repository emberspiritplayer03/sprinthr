<h2><?php echo $title; ?></h2>
<script>
$("#nationality_form").validationEngine({scroll:false});
</script>
<div id="form_main" class="employee_form">
<form id="nationality_form" name="nationality_form" method="post" action="<?php echo url('reports/download_nationality'); ?>">
     <div id="form_default">
        <h3 class="section_title">&nbsp;</h3>
    </div>

    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Nationality</td>
                <td><input class="validate[required]" type="text" name="nationality" id="nationality" /> 
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
