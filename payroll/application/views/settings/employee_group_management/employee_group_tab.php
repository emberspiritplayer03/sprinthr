<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:450px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
		load_group_list_dt();
	});
</script>
<input type="hidden" id="h_company_structure_id" name="h_company_structure_id" value="<?php echo $h_company_structure_id; ?>" />
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" onclick="javascript:load_group_list_dt();">Group</a></li>
        <li><a href="#tabs-2" onclick="javascript:load_employee_list_dt();">Employee</a></li>
		
	</ul>    
	<div id="tabs-1">	
    <div class="actions_holder">
        <a class="add_button" onclick="javascript:addGroup();" href="#"><strong>+</strong><b>Add Group</b></a>
    </div>
    		
       <div id="group_list_dt_wrapper"></div>
	</div>  
    
    <div id="tabs-2">
		<div id="employee_list_dt_wrapper"></div>
	</div>
</div>

<div id="add_group_form_modal_wrapper" style="display:none;"><?php include('forms/add_group.php'); ?></div>