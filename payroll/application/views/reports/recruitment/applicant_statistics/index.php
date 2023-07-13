<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/download_applicants_statistics'); ?>">
     <div id="form_default">
        <h3 class="section_title">Applicant Statistics</h3>
        <table width="100%">
            <tr>
                <td class="field_label">Year:</td>
                <td><select name="year" id="year">
                <?php
				$year = date("Y");

				 while($x<=10) { ?>
                  <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php 
					$year-=1;
					$x++;
				} ?>
                </select></td>
            </tr>
            <tr>
                <td class="field_label">Month</td>
                <td><select name="month" id="month">
                 <option value="0" selected="selected">All Months</option>
                  <option value="1">January</option>
                  <option value="2">February</option>
                  <option value="3">March</option>
                  <option value="4">April</option>
                  <option value="5">May</option>
                  <option value="6">June</option>
                  <option value="7">July</option>
                  <option value="8">August</option>
                  <option value="9">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option>December</option>
                </select></td>
            </tr>
    	</table>
    </div>
    <div id="form_default"></div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td width="27%" class="field_label">&nbsp;</td>
                <td width="73%"><input class="blue_button" type="submit" name="button" id="button"  value="Download"></td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
