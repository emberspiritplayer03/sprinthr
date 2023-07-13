<script>
	function toggleList(id) {
		$(id).toggle();
	}
	
	function showAll() {
		$('.hr_submodule_list').show();
	}
	
	function hideAll() {
		$('.hr_submodule_list').hide();
	}
</script>

<div style="float:right;">
	<a href="javascript:" onclick="javascript:showAll();">Show All</a> | <a href="javascript:void(0);" onclick="javascript:hideAll();">Hide All</a>
</div>
<br />

<div id="form_default">
	<?php include('modules/hr/dashboard.php'); ?>
    <?php include('modules/hr/recruitment.php'); ?>
	<?php include('modules/hr/employee.php'); ?>
   
   <br />
   <br />

   <input type="submit" value="Submit">

   
</div>