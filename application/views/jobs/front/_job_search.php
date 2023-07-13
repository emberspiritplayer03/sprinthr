<script>
var date_from_str = $("#date_from").val();
var date_to_str   = $("#date_to").val();

$(document).ready(function() {		
	$('#job_search_form').validationEngine({scroll:false});
});
</script>
<form id="job_search_form" name="job_search_form" action="<?php echo url('job_vacancy/search'); ?>" method="post"> 
	<h2 class="sidebar_title">Job Search</h2>
 	<input type="text" class="input-large search-query validate[required]" id="job_search_input" name="job_search_input">
 	<button type="submit" class="btn">Search</button>
 </form>

