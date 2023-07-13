<script>
$(function() {
	//set user access right to the global variable, this is for ajax
	can_manage = "<?php echo $can_manage ?>";
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
<div id="import_applicant_wrapper" style="display:none;">
<?php include 'form/import_applicant.php'; ?>
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
          <option value="Applied Position:" >Applied Position</option>
          <option value="Date Applied:">Date Applied</option>
          <option value="Lastname:">Lastname</option>
          <option value="Firstname:">Firstname</option>
          <option value="Birthdate:">Birthdate</option>
          <option value="Gender:">Gender</option>
          <option value="Marital Status:">Marital Status</option>
          <option value="Address:">Address</option>
          <option value="City:">City</option>
          <option value="Province:">Province</option>
<!--          <option value="License:">License</option>
          <option value="Course:">Course</option>
          <option value="Skills:">Skills</option>
          <option value="Attainment:">Educ. Attainment</option>-->
          <option value="Requirements:incomplete">Incomplete Requirements</option>
          <option value="Status:">Application Status</option>
        </select>
        <!--<input type="submit" name="button" id="button" value="Search"  class="curve blue_button" /> -->
      <button type="submit" class="blue_button"  onclick="javascript:searchApplicant();"><i class="icon-search icon-white"></i> Search</button>

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
		<button class="blue_button"  onclick="javascript:loadEmployeeByPosition();"><i class="icon-search icon-white"></i> Search</button>
        <a href="javascript:void(0);" onclick="javascript:load_search();">Basic Search</a>
        </div>
	    <div class="clear"></div>
    </form>
    </div>       
</div><!-- #employeesearchmain -->
</div>
<!-- #employee_search_container -->
<?php if($_GET['add_candidate']=='true') { ?>
		<div id="candidate_form_wrapper" >
<?php }else { ?>
		<div id="candidate_form_wrapper" style="display:none" >
<?php } ?>
<?php include 'form/add_candidate_form.php'; ?>
</div>
<div class="btn-group float-right">
	<a title="List View" href="javascript:loadListView();" id="btn_listview" class="btn btn-small">&nbsp;&nbsp;<i class="icon-align-justify"></i>&nbsp;&nbsp;</a>
    <a title="Image View" href="javascript:loadImageView();" id="btn_imageview" class="btn btn-small">&nbsp;&nbsp;<i class="icon-picture"></i>&nbsp;&nbsp;</a>
    <a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_view_all_candidate_datatable();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
</div>
<?php if($can_manage) { ?>
<a class="gray_button" href="javascript:void(0);" onclick="javascript:importApplicant();"><i class="icon-arrow-left"></i> Import Applicant</a>
<span id="imported_applicant_button"><?php echo $imported_button; ?></span>
<?php } ?>
<div class="clear"></div>
<br />
<!--<div style="position:relative; top:-6px;" class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><div id="total_result_wrapper">Total Record(s): 0</div></div>
--><div class="yui-skin-sam">
	<div id="candidate_datatable"></div>
</div>
<div id="candidate_wrapper"></div>
<div id="confirmation"></div>
<script>
//load_candidate_datatable('nothing','all');
load_view_all_candidate_datatable();
$(function(){
	$('#btn_listview').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_imageview').tipsy({trigger: 'focus',html: true, gravity: 's'});	
	$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
});
</script>

<input type="hidden" name="applicant_hash" id="applicant_hash"/>