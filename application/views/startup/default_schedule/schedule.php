<!-- start of schedule -->
<!--<h3 class="section_title">Schedule</h3> -->   

<div id="create_schedule_handler"></div>

<!--<div id="employee_search_container">
<form method="get" action="<?php //echo $action;?>">
	<div class="employee_basic_search"><input id="search_employee" class="curve" type="text" name="search_employee" />
    <button id="employee_search_submit" onclick="javascript:showSearchScheduleList();" class="blue_button" type="button"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>-->
<div id="search_employee_schedule_result"></div>
</div><!-- #employee_search_container -->
<div class="section_container">
    <div>
   	  <a style="vertical-align:middle;" class="add_button" onclick="javascript:createWeeklySchedule()" href="javascript:void(0)" original-title="Create New Schedule"><strong>+</strong> <b>Create Schedule</b></a>
        <!--<a class="gray_button" href="javascript:void(0)"><i class="icon-arrow-left"></i> Import Weekly Schedule</a>
        <a class="gray_button" href="javascript:void(0)" ><i class="icon-arrow-left"></i> Import Changed Schedule</a>        -->
        &nbsp;&nbsp;<strong style="display:inline;">Import:</strong>&nbsp;&nbsp;<select size="">
        	<option selected="selected">- Select to Import -</option>
            <option onclick="javascript:importSchedule()">Weekly Schedule</option>
            <option onclick="javascript:importScheduleSpecific()">Changed Schedule</option>
        </select>
        <br /><br />
    </div>
    <div id="schedule_list"></div>
</div>

<!--<div style="display:none" id="create_schedule_id">
<?php //include 'application/views/schedule/forms/create_schedule_form.php';?>
</div>-->

<script>
	
 $(document).ready(function() {
   showWeeklyScheduleList('#schedule_list');
 });
</script>
<!-- end of schedule -->