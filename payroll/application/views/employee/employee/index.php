<script>

	$(function() {
		$( "#datepicker" ).datepicker({
			onSelect: function(dateText, inst) { 
					$("#search").val($("#search").val()+dateText);
					$("#search").focus();
					$("#search").setCursorToTextEnd();
					$("#datepicker").hide();
  			},
			dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true
	
		});
	});

</script>
<div id="branch_wrapper_form" style="display:none" >
<?php include 'form/add_new_branch.php'; ?>
</div>

<div id="department_wrapper_form" style="display:none" >
<?php include 'form/add_department.php'; ?>
</div>

<div id="position_wrapper_form" style="display:none" >
<?php include 'form/add_position.php'; ?>
</div>

<div id="status_wrapper_form" style="display:none" >
<?php include 'form/add_employment_status.php'; ?>
</div>

<?php if($_GET['add_employee']=='true') { ?>
		<div id="employee_form_wrapper" >
<?php }else { ?>
		<div id="employee_form_wrapper" style="display:none" >
<?php } ?>

<?php include 'form/add_employee.php'; ?>
</div>
<div id="import_employee_wrapper" style="display:none">
<?php include 'form/import_employee.php'; ?>
</div>

<div class="esearch_fieldhold">
<?php include 'includes/esearch.php'; ?>
</div>
<div id="employee_search_container">

<div id="employeesearchmain">
	<!-- DONT REMOVE THIS! --><div></div><!-- -->
	<div id="search_wrapper" class="employee_basic_search searchcnt">
        <input name="search" type="text" class="curve" id="search" size="100" />       
        <select onchange="javascript:loadCategory();" class="curve" name="category" id="category">
          <option value="" selected="selected">-- Select --</option>
          <?php if(count($branches)>1) { ?>
		   <option value="Branch:" >Branch</option>
		  <?php    
		  } ?>
          
		  <option value="Department:" >Department</option>
          <option value="Position:">Position</option>
          <option value="Employment Status:">Employment Status</option>
          <option value="Employee ID:">Employee ID</option>
          <option value="Lastname:">Lastname</option>
          <option value="Firstname:">Firstname</option>
          <option value="Birthdate:">Birthdate</option>
          <option value="Age:">Age</option>
          <option value="Gender:">Gender</option>
          <option value="Marital Status:">Marital Status</option>
          <option value="Address:">Address</option>
          <option value="City:">City</option>
          <option value="Home Telephone:">Home Telephone</option>
          <option value="Mobile:">Mobile</option>
          <option value="Work Email:">Work Email</option>          
<!--          <option value="License:">License</option>
          <option value="Skills:">Skills</option>
          <option value="Course:">Course</option>
          <option value="Language:">Language</option>
          <option value="Requirements:incomplete">Incomplete Requirements</option>-->
          <option value="Hired Date:">Hired Date</option>
          <option value="Terminated Date:">Terminated Date</option>
          <option value="End of Contract:">End of Contract</option>
        </select>
      <button type="submit" class="blue_button"  onclick="javascript:searchEmployee();">Search</button>

    </div>
    <div id="advance_search_wrapper" style="display:none;" class="employee_advanced_search searchcnt">
    <form class="advancedsearchform">
		<div id="branch_search_dropdown_wrapper" class="advanced_sitems">Branch:<br />
            <select name="search_branch" class="curve" id="search_branch" onchange="javascript:loadDepartment();" >
                <option value="all">-All Branch-</option>
                <?php foreach($branches as $key=>$value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>
                <?php } ?>
            </select>
        </div>
        <div id="department_search_dropdown_wrapper" class="advanced_sitems">Search by Department:<br />
            <select name="search_department" class="curve" id="search_department" onchange="javascript:loadPosition();">
                <option value="all">-All Department-</option>
                <?php foreach($departments as $key=>$value) { ?>
                <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
                <?php } ?>
            </select>
        </div>
		<div id="position_search_dropdown_wrapper" class="advanced_sitems">Position: <br />
          <select class="curve" name="search_position" id="search_job">
            <option value="all">-All Position-</option>
             <?php foreach($positions as $key=>$value) { ?>
             <option value="<?php echo $value->id; ?>"><?php echo $value->title; ?></option>
             <?php } ?>
          </select>
        </div>
        <div id="status_search_dropdown_wrapper" class="advanced_sitems">Status:<br />
        <select name="search_status" class="curve" id="search_status">
        <option value="all">-All Status-</option>
        <?php foreach($GLOBALS['employee_status'] as $key=>$value) { ?>
        <option value="<?php echo $key;  ?>"><?php echo $value; ?></option>
        <?php } ?>
        </select>
        </div>
        <div class="advancedsearch_buttons">
		<button class="blue_button"  onclick="javascript:loadEmployeeByPosition();">Search</button>
        <a href="javascript:void(0);" onclick="javascript:load_search();">Basic Search</a>
        </div>
	    <div class="clear"></div>
    </form>
    </div>       
</div><!-- #employeesearchmain -->
</div><!-- #employee_search_container -->
<a class="gray_button" href="javascript:void(0);" onclick="javascript:importEmployee();">Import Employee</a><br /><br />
<div align="right"><a href="javascript:loadListView();">List View</a> | <a href="javascript:loadImageView();">Image View</a></div>

<b id="total_result_wrapper">Total Record(s): 0</b>
<div class="yui-skin-sam">
  <div id="employee_datatable"></div>
</div>
<div id="employee_wrapper"></div>
<div id="confirmation"></div>

<script>
load_employee_datatable('nothing');
</script>
<input type="hidden" name="employee_hash" id="employee_hash"/>
