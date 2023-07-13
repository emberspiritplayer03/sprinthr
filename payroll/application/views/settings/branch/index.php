<style>
div.button-container{ margin:10px 0;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Branch</a></li>		
	</ul>
	<div id="tabs-1">
    	<div class="actions_holder" align="left">
        	<a class="add_button" href="javascript:void(0);" onclick="javascript:load_add_new_branch();"><strong>+</strong><b>Add New</b></a>
        </div>
		<div id="branch-list"></div>
        <div class="yui-skin-sam">
        	<div id="branch_list"></div>
        </div>
	</div>
</div>
<?php include_once('includes/modal_forms.php'); ?>
<script>
load_branch_list_dt();

$('#tipsy_edit').tipsy({gravity: 's'});
$('#yuievtautoid-0 #delete').tipsy({gravity: 's'});
</script>
