<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/applicant_list'); ?>">
     <div id="form_default">
        <!--<h3 class="section_title">Employee</h3>-->
        <!--<table width="100%">
          
          
    	</table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">-->
        <table width="100%">
              <tr>
                <td class="field_label">Department:</td>
                <td><select class="select_option" name="department_field" id="search_field" onChange="javascript:checkIfAll();">
                <option value="DEPARTMENT">Department</option>
                </select>
               </td>
            </tr>
            <tr>
                <td class="field_label"><label><input type="radio" name="radio_group1" value="Education" >Entered </input></label></td>
                <td><select class="select_option_sched" name="month_from" id="month_from">
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
                </select>
                <select class="select_option_sched" name="year_from" id="year_from">
                <?php 
                $year = date("Y");
                $x=0;
                while($x<=10) { ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php
                $year--;
                $x++;
                } ?>
                </select></td>
            </tr>
            <tr>
                <td class="field_label"><label><input type="radio" name="radio_group1" value="Education" >Left</input></label></td>
                <td><select class="select_option_sched" name="month_to" id="month_to">
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
                </select>
                <select class="select_option_sched" name="year_to" id="year_to">
                <?php 
                $year = date("Y");
                $x=0;
                while($x<=10) { ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                <?php
                $year--;
                $x++;
                } ?>
                </select></td>
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
