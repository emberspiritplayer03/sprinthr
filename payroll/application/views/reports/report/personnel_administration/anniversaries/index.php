<h2><?php echo $title; ?></h2>
<script>
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
</script>
<div id="form_main" class="employee_form">
<form name="form1" method="post" action="<?php echo url('reports/applicant_list'); ?>">
     <div id="form_default">
        <h3 class="section_title">Anniversaries</h3>
      
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Department:</td>
                <td><select class="select_option" name="department_field" id="search_field" onChange="javascript:checkIfAll();">
                <option value="DEPARTMENT">Department</option>
                
                </select>        <input style="display:none" type="text" name="search" id="search">
                <input type="text" style="display:none" name="birthdate" id="birthdate" /></td>
            </tr>
            <tr>
            	<td class="field_label">Year(s):</td>
                <td><label for="years_field"></label>
                <select class="select_option" name="years_field"  >
               <?php 
			   for($i = 1; $i<=20;$i++)
			   {
				   if($i<20)
				   echo "<option value=".$i.">".$i."</option>";
			   	   else
				   echo "<option value=".$i.">20 above</option>"; 
			   }
			   ?>
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
