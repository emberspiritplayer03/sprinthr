<?php include('includes/_wrappers.php'); ?>
<h2>Grace Period</h2>
<form name="withSelectedAction" id="withSelectedAction">
<!--<div class="break-bottom inner_top_option">-->
    <!--<a id="request_leave_button" class="add_button" href="javascript:addNewGracePeriod();"><i class="icon-plus"></i> <b>Add New Grace Period</b></a>-->
    <!--<div class="pull-right">
    	<a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_leave_type_list_dt();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
    <a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_archive_leave_type_list_dt();">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>
    </div>
    <div class="clear"></div>
<!--</div>-->
    <div id="grace_period_list_dt_wrapper" class="dtContainer"></div>    
</form>

</div>
<script>
$(function() {	
	load_grace_period_list_dt();  	
	/*$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	*/ 
  });
</script>
