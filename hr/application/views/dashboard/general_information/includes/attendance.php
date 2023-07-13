<script>
	$(function() {
$("#date").datepicker({dateFormat:'yy-mm-dd'});
	})
</script>

<input type="text" name="date" id="date" />
<input class="btn" type="submit" name="button" id="button" onclick="javascript:loadAttendanceLog();" value="Display" />
<div class="yui-skin-sam">
  <div id="attendance_datatable"></div>
</div>
<script>
loadAttendanceList();
</script>