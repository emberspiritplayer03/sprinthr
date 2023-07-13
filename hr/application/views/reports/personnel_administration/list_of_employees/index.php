<h2><?php echo $title; ?></h2>
<script>

$("#employee_list_form").validationEngine({scroll:false});
</script>
<div id="form_main" class="employee_form">
<form id="employee_list_form" name="employee_list_form" method="post" action="<?php echo url('reports/download_employee_list'); ?>">

    <div id="form_default">
      <table width="100%">
      	<tr>
          <td class="field_label">Department:</td>
          <td><select class="select_option" name="department_id" id="department_id" >
            <option value="all">All Department</option>
            <?php foreach($department as $key=>$value) { ?>
            <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
            <?php } ?>
          </select></td>
        </tr>
      </table>
    </div>
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
