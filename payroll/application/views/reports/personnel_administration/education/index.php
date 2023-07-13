<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/applicant_list'); ?>">
     <div id="form_default">
        <h3 class="section_title">Education</h3>
        <!--<table width="100%">
          
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">-->
        <table width="100%">
             <tr>
                <td class="field_label">Course:</td>
                <td>
                <input type="text" name="course_field"/>
              </td>
            </tr>
            <tr>
                <td class="field_label">School:</td>
                <td>
              <input type="text" name="school_field"/>
                </td>
            </tr>
            <tr>
                <td class="field_label">Year:</td>
                <td>
              <input type="text" name="year_field"/>
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
