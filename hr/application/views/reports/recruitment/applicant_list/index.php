<h2><?php echo $title; ?></h2>
<script>
$("#from").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#to").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#birthdate").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});
$("#applicant_list_form").validationEngine({scroll:false});
</script>
<div id="form_main" class="employee_form">
<form id="applicant_list_form" name="applicant_list_form" method="post" action="<?php echo url('reports/download_applicant_list'); ?>">	
	<div id="form_default">
      <h3 class="section_title">Date Applied</h3>
      <table width="100%">
        <tr>
          <td class="field_label">From:</td>
          <td><input class="validate[required] text-input" type="text" name="from" id="from" /></td>
        </tr>
        <tr>
          <td class="field_label">To:</td>
          <td><input class="validate[required] text-input" type="text" name="to" id="to" /></td>
        </tr>        
      </table>
    </div>
    <div class="form_separator"></div>
    <div id="form_default">
      <table width="100%">
      	<tr>
          <td class="field_label">Search by:</td>
          <td><select name="search_field" class="select_option_sched" id="search_field" onChange="javascript:checkIfAll();">
            <option value="all">All</option>
            <option value="firstname">Firstname</option>
            <option value="lastname">Lastname</option>
            <option value="birthdate">Birthdate</option>
            <option value="gender">Gender</option>
            <option value="marital_status">Marital Status</option>
            <option value="address">Address</option>
          </select>
          <input style="display:none;" class="input-medium" type="text" name="search" id="search" placeholder="Search">
          <input class="input-medium" type="text" style="display:none" name="birthdate" id="birthdate" /></td>
        </tr>
        <tr>
          <td class="field_label">Position:</td>
          <td><label for="position_applied"></label>
            <select name="position_applied" class="select_option_sched" id="position_applied" >
                <option value="all">All Position</option>
              <?php foreach($job as $key=>$value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
              <?php } ?>
          </select></td>
        </tr>
        <!--<tr>
          <td colspan="2" class="field_label">--><!--<table width="100%" border="1">
            <tr>
              <td width="32%"><label><input name="checkbox1" type="checkbox" id="checkbox1" value="gender" />Gender</label></td>
              <td width="28%"><label><input name="checkbox4" type="checkbox" id="checkbox4" value="birthplace" />Birthplace</label></td>
              <td width="40%"><label><input name="checkbox7" type="checkbox" id="checkbox7" value="province" />Province</label></td>
            </tr>
            <tr>
              <td><label><input name="checkbox2" type="checkbox" id="checkbox2" value="marital_status" />Marital Status</label></td>
              <td><label><input name="checkbox5" type="checkbox" id="checkbox5" value="address" />Address</label></td>
              <td><label><input name="checkbox8" type="checkbox" id="checkbox8" value="zip_code" />Zip Code</label></td>
            </tr>
            <tr>
              <td><label><input name="checkbox3" type="checkbox" id="checkbox3" value="birthdate" />Birthdate</label></td>
              <td><label><input name="checkbox6" type="checkbox" id="checkbox6" value="city" />City</label></td>
              <td><label><input name="checkbox9" type="checkbox" id="checkbox9" value="home_telephone" />Home Telephone</label></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><label><input name="checkbox10" type="checkbox" id="checkbox10" value="email_address" />Email Address</label></td>
              <td><label><input name="checkbox11" type="checkbox" id="checkbox11" value="mobile" />Mobile</label></td>
            </tr>
          </table>--><!--</td>
        </tr>-->
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
