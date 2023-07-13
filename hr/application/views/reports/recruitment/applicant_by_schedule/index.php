<h2><?php echo $title; ?></h2>
<script>
$("#from_field").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#to_field").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#applicant_by_schedule_form").validationEngine({scroll:false});
</script>
<div id="form_main" class="employee_form">
<form name="applicant_by_schedule_form" method="post" action="<?php echo url('reports/download_applicant_by_schedule'); ?>">
    <div id="form_default">
        <!--<h3 class="section_title">Applicant by Schedule</h3>-->
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td>                
                <input id="from_field" class="text-input" type="text"  name="from_field"/>                                
                </td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td>
                <input id="to_field" class="text-input" type="text" name="to_field" />
                </td>
            </tr>
        </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
            <tr>
                <td width="23%" class="field_label">Job Position:</td>
                <td width="77%"><label for="position_applied"></label>
                <select class="select_option" name="position_applied" id="position_applied" >
                <option value="all">All Position</option>
                <?php foreach($job as $key=>$value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
                </select></td>
            </tr>        
        </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
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
