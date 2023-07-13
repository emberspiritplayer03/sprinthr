<script>
var date_from_str = $("#date_from").val();
var date_to_str   = $("#date_to").val();

$(document).ready(function() {		
	$('#job_search_form').validationEngine({scroll:false});
});
</script>
<div class="job_search_container">
<form id="job_search_form" name="job_search_form" action="<?php echo url('job_vacancy/search'); ?>" method="post"> 
 	<h2 class="sidebar_title search_inline">Job Search</h2>
    <input type="text" class="input-large input-xlarge search_inline search-query validate[required]" id="job_search_input" name="job_search_input">
 	<span class="search_inline">&nbsp;&nbsp;&nbsp;</span><button class="btn btn-info search_inline" type="submit" class="btn">Search</button>
    <div class="clear"></div>
 </form>
 </div>

