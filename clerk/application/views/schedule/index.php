<div id="create_schedule_handler"></div>

<div id="employee_search_container">
<form method="get" action="<?php echo $action;?>">
	<div class="employee_basic_search"><input id="search" class="curve" type="text" name="query" />
    <button id="employee_search_submit" class="blue_button" type="submit"><i class="icon-search icon-white"></i> Search Employee</button>
    </div>
</form>
</div><!-- #employee_search_container -->
<div>
	<strong>Import:</strong>&nbsp;&nbsp;<select size="">
    	<option selected="selected">- Select to Import -</option>
        <option onclick="javascript:importSchedule()">Weekly Schedule</option>
        <option onclick="javascript:importScheduleSpecific()">Changed Schedule</option>
    </select>
    <!--<a class="gray_button" href="javascript:void(0)" "><i class="icon-arrow-left"></i> Import Weekly Schedule</a>
    <a class="gray_button" href="javascript:void(0)" onclick="javascript:importScheduleSpecific()"><i class="icon-arrow-left"></i> Import Changed Schedule</a>-->
    <br /><br />
</div>
<div id="schedule_list"></div>

<!--<div style="display:none" id="create_schedule_id">
<?php //include 'application/views/schedule/forms/create_schedule_form.php';?>
</div>-->

<script>
	showWeeklyScheduleList('#schedule_list');
</script>