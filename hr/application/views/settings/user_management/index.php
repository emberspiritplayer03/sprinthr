<style>
#btn-add-user, #btn-add-role{margin-bottom:10px;display:inline-block;}
</style>
<script type="text/javascript">
	$(function() {
		var jqAction = jQuery.noConflict();   
		$("#tabs").tabs({selected: '2'});

		$("#btn-import-user").click(function(){
			//addUserAccount();
			importUser();
		});

		$("#btn-add-user").click(function(){
			//addUserAccount();
			addUser();
		});

		$("#btn-refresh-user").click(function(){
			load_user_management_dt();
		});

		$("#btn-add-role").click(function(){
			addRole();
		});

		$("#btn-refresh-role").click(function(){
			load_roles_dt();
		});

		load_roles_dt();
		load_user_management_dt();
	});
</script>
<div id="user-role-form-container"></div>
<div id="tabs" class="user-role-container">
	<ul>
		<li><a href="#tabs-1">Users</a></li>
        <li><a href="#tabs-2">Roles</a></li>
	</ul>
	<div id="tabs-1">
		<div class="pull-right">
			<a id="btn-import-user" class="blue_button pull-left" href="javascript:void(0);">Import User</a>
			<a id="btn-add-user" class="blue_button pull-left" href="javascript:void(0);">Add User</a>		
			<a id="btn-refresh-user" class="blue_button pull-left" href="javascript:void(0);">Refresh</a>
		</div>		
		<?php include_once('includes/user_management.php'); ?>
	</div>
    <div id="tabs-2">
    	<div class="pull-right">
    		<a id="btn-add-role" class="blue_button pull-left" href="javascript:void(0);">Add Role</a>
    		<a id="btn-refresh-role" class="blue_button pull-left" href="javascript:void(0);">Refresh</a>			
    	</div>	
		<?php include_once('includes/roles.php'); ?>
	</div>    
</div>