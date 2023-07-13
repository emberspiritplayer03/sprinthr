<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/download_telephone_directory'); ?>">
    <div id="form_default">
        <table width="100%">
            <tr>
                <td width="28%" class="field_label">Department</td>
                <td width="72%"><select class="select_option" name="department_id" id="department_id" >
                  <option value="all">All Department</option>
                  <?php foreach($department as $key=>$value) { ?>
                  <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                  <?php } ?>
                </select>
                  <input style="display:none" type="text" name="search" id="search">
               </td>
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