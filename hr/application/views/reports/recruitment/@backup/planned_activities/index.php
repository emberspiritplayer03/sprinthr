<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/applicant_list'); ?>">
    <div id="form_default">
        <h3 class="section_title">Date Applied</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
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
                <td class="field_label">To:</td>
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
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Search by:</td>
                <td><select class="select_option" name="search_field" id="search_field" onChange="javascript:checkIfAll();">
                <option value="all">All</option>
                <option value="firstname">Firstname</option>
                <option value="lastname">Lastname</option>
                <option value="birthdate">Birthdate</option>
                <option value="gender">Gender</option>
                <option value="marital_status">Marital Status</option>
                <option value="address">Address</option>
                </select>        <input style="display:none" type="text" name="search" id="search">
                <input type="text" style="display:none" name="birthdate" id="birthdate" /></td>
            </tr>
            <tr>
                <td class="field_label">Position:</td>
                <td><label for="position_applied"></label>
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
