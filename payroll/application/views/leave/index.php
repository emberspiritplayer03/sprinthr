<input type="hidden" name="eid" id="eid" value="<?php echo $_SESSION['hr']['eid']; ?>" >
<input type="hidden" name="id" id="id" value="<?php echo $_SESSION['hr']['lid']; ?>" >

<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>

<?php if($_GET['add_employee_leave']=='true') { ?>
	<div id="employee_leave_form_wrapper">
<?php }else { ?>
	<div id="employee_leave_form_wrapper" style="display:none" ><?php } ?>
	<?php include 'leave_list/form/add_employee_leave.php'; ?>
</div>
<!--<div id="search_wrapper" class="employee_basic_search searchcnt">-->
<div id="employee_search_container" class="employee_basic_search searchcnt">
        <input name="search" type="text" class="curve" id="search" size="100" />       
        <select onchange="javascript:loadCategory();" class="curve" name="category" id="category" style="display:none">
          <option value="" selected="selected">-- Select --</option>
		  <option value="Leave Type:" >Leave Type</option>
          <option value="Employee Code:">Employee Code</option>
          <option value="Lastname:">Lastname</option>
          <option value="Firstname:">Firstname</option>
          <option value="Date Filed:">Date Filed</option>
          <option value="Status:">Status</option>
          <option value="Date Started:">Date Started</option>
        
        </select>
        <!--<input type="submit" name="button" id="button" value="Search"  class="curve blue_button" /> -->
      <button type="submit" class="blue_button"  onclick="javascript:searchLeave();">Search</button>
        <!--<a href="javascript:void(0);" onclick="javascript:load_advance_search();">Advanced Search</a>-->
</div>
<a class="gray_button" href="javascript:void(0);" onclick="javascript:importLeave();">Import Leave</a><br /><br />
<div id="list_wrapper"></div>
<div id="employee_leave_profile_wrapper"></div>