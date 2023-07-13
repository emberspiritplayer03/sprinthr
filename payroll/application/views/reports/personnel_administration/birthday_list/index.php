<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/download_birthday_list'); ?>">
     <div id="form_default">
        <h3 class="section_title">Birthday List</h3>
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
                <td class="field_label">Month:</td>
                <td><select  name="month_birthday" >
                <option value="">All Months</option>
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
                </select></td>
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
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Search"></td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
