<h2><?php echo $title; ?></h2>
<script>
$("#leave_overview_form").validationEngine({scroll:false});
$("#date_applied_from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#date_applied_to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="leave_overview_form" id="leave_overview_form" method="post" action="<?php echo url('reports/download_leave_overview'); ?>">
     <div id="form_default">
        <!--<h3 class="section_title">&nbsp;</h3>-->
        <table width="100%">
          <tr>
            <td class="field_label">Department:</td>
            <td><select class="select_option" name="department_id" id="department_id" >
              <option value="">All Department</option>
              <?php foreach($department as $key=>$value) { ?>
              <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
            </select></td>
          </tr>
            <tr>
                <td class="field_label">Date Filed From:</td>
                <td><input type="text" class="validate[required]" name="date_applied_from" id="date_applied_from" /></td>
            </tr>
            <tr>
              <td class="field_label">Date Filed To:</td>
              <td><input type="text" class="validate[required]" name="date_applied_to" id="date_applied_to" /></td>
            </tr>
    	</table>
    </div>
    <!--<div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
           
           
           
                  
        </table>
    </div>-->
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
