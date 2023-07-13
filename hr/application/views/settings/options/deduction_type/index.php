<?php include('includes/_wrappers.php'); ?>
<form name="withSelectedAction" id="withSelectedAction">
<div class="break-bottom inner_top_option">    
    <a id="request_leave_button" class="add_button" href="javascript:addDeductionType();"><i class="icon-plus"></i> <b>Add Deduction Type</b></a>
    <div class="pull-right">
    	<a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_loan_list_dt();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
    <a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_archive_loan_list_dt();">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>
    </div>
    <div class="clear"></div>
</div>
    <div id="loan_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
$(function() {	
	load_loan_list_dt();  	
	$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
  });
</script>

