<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/applicant_list'); ?>">
    <div id="form_default">
        <h3 class="section_title">Planned Activities</h3>
        <table width="100%">
            <tr>
                <td class="field_label">From:</td>
                <td>
                <input type="text" name="From_field" />
              </td>
            </tr>
            <tr>
                <td class="field_label">To:</td>
                <td>
                <input type="text" name="To_field" />
                </td>
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
