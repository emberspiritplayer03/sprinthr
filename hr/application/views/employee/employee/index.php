<script>
$(function() {
	$( "#datepicker" ).datepicker({
		onSelect: function(dateText, inst) { 
				$("#search").val($("#search").val()+dateText);
				$("#search").focus();
				$("#search").setCursorToTextEnd();
				$("#datepicker").hide();
			},
		dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true,yearRange: "-70:+0"

	});

  $(".btn-import-salary").live("click",function(){
    importEmployeeSalary();
  });
	
	can_manage = "<?php echo $can_manage ?>";
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

<div id="import_employee_wrapper" style="display:none">
<?php include 'form/import_employee.php'; ?>
</div>

<div id="import_employee_training_wrapper" style="display:none">
<?php include 'form/import_employee_training.php'; ?>
</div>


<div id="import_employee_salary_wrapper" style="display:none">
<?php include 'form/import_salary.php'; ?>
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
          <option value="Section:">Section</option>
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
          <!--
          <option value="License:">License</option>
          <option value="Skills:">Skills</option>
          <option value="Course:">Course</option>
          <option value="Language:">Language</option>
          <option value="Requirements:incomplete">Incomplete Requirements</option>
          -->
          <option value="Hired Date:">Hired Date</option>
          <option value="Terminated Date:">Terminated Date</option>
          <option value="End of Contract:">End of Contract</option>
          <option value="Tags:" >Tags</option>
        </select>
      <button type="submit" class="blue_button"  onclick="javascript:searchEmployee();"><i class="icon-search icon-white"></i> Search</button>

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

        <div id="section_search_dropdown_wrapper" class="advanced_sitems">Search by Section:<br />
          <select name="search_section" class="curve" id="section_department" onchange="javascript:loadPosition();">
              <option value="all">-All Section-</option>
              <?php foreach($sections as $key=>$value) { ?>
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
		<button class="blue_button"  onclick="javascript:loadEmployeeByPosition();"><i class="icon-search icon-white"></i> Search</button>
        <a href="javascript:void(0);" onclick="javascript:load_search();">Basic Search</a>
        </div>
	    <div class="clear"></div>
    </form>
    </div>       
</div><!-- #employeesearchmain -->
</div><!-- #employee_search_container -->
<?php if($_GET['add_employee']=='true') { ?>
		<div id="employee_form_wrapper" >
<?php }else { ?>
		<div id="employee_form_wrapper" style="display:none" >
<?php } ?>

<?php include 'form/add_employee.php'; ?>
</div>
<div class="btn-group float-right">
	<!--<a title="List View" id="btn_listview" class="btn btn-small" href="javascript:loadListView();">&nbsp;&nbsp;<i class="icon-align-justify"></i>&nbsp;&nbsp;</a>-->
    <a title="Image View" id="btn_imageview" class="btn btn-small" href="javascript:loadImageView();">&nbsp;&nbsp;<i class="icon-picture"></i>&nbsp;&nbsp;</a>
    <a title="View All" id="btn_viewall" class="btn btn-small active" href="javascript:load_view_all_employee_datatable('nothing');">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
    <!--<a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_view_all_archive_employee_datatable('nothing');">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>-->
</div>
<?php echo $btn_import_employee; echo $btn_import_salary;  echo $btn_import_employee_training; ?>
<div class="clear"></div><br />

<div class="ui-state-highlight ui-corner-all" style="position:relative; top:-6px;">
<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><div id="total_result_wrapper">Total Record(s): 0</div></div>
<div class="yui-skin-sam">
  <div id="employee_datatable"></div>
</div>
<div id="employee_wrapper"></div>
<div id="confirmation"></div>

<script>
load_total_search('nothing');
//load_employee_datatable('nothing');
load_view_all_employee_datatable('nothing');
$(function() {	 
 	$('#btn_listview').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_imageview').tipsy({trigger: 'focus',html: true, gravity: 's'});	
	$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
  });
</script>
<input type="hidden" name="employee_hash" id="employee_hash"/>
