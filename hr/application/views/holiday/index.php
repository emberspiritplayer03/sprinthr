<script>
function showHoliday(year) {
    var type = $("#selected_type").val();
	if(type == "cv") {
		showHolidayCalendar('#holiday_list',year);
	}else{
		showHolidayList('#holiday_list', year);
	}
}

$(function(){
	$("#selected_type").change(function(){
		var year = $("#selected_year").val();
		if($(this).val() == "cv") {
			showHolidayCalendar('#holiday_list',year);
		}else{
			showHolidayList('#holiday_list', year);
		}
	});

	$("#selected_year").change(function(){
		var year = $(this).val();
		var type = $("#selected_type").val();
		if(type == "cv") {
			showHolidayCalendar('#holiday_list',year);
		}else{
			showHolidayList('#holiday_list', year);
		}
	});

	showHoliday($("#selected_year").val());
});

</script>

<!--<div class="ui-state-highlight ui-corner-all" style="margin-bottom:20px;">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div>-->
<div id="add_holiday_form_container"></div>
<div class="action_holder">
	 <a id="add_holiday_link" class="add_button" href="javascript:void(0);" onclick="javascript:addHoliday();">
     	<strong>+</strong>
        <b>Add Holiday</b>
     </a>
</div>
<br>
Show : <select id="selected_year" >
<?php foreach ($years as $year):?>
    <option <?php echo ($year == $current_year) ? 'selected="selected"' : '' ;?> value="<?php echo $year;?>"><?php echo $year;?></option>
<?php endforeach;?>
</select>
<div class="pull-right">
Type : <select id="selected_type" ">
	<option value="lv">List View</option>
	<option value="cv">Calendar View</option>
</select>
</div>
<br>
<div id="holiday_list"></div>

<div style="display:none" id="add_holiday_id">
<?php include 'application/views/holiday/forms/add_holiday_form.php';?>
</div>

<script>

</script>

