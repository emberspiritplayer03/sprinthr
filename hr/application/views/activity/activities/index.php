<?php include('includes/_wrappers.php'); ?>

<form name="withSelectedAction" id="withSelectedAction">
    <div id="activities_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() {
		load_activities_list_dt();       
	});
</script>