<style>
h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
.text{width:250px;}
legend{
	-moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: -moz-use-text-color -moz-use-text-color #E5E5E5;
    border-image: none;
    border-style: none none solid;
    border-width: 0 0 1px;
    color: #333333;
    display: block;
    font-size: 21px;
    line-height: 40px;
    margin-bottom: 20px;
    padding: 0;
    width: 100%;
}
	
</style>
<script>
$(document).ready(function() {	
	$('#databaseSettings').validationEngine({scroll:false});	
		
	$('#databaseSettings').ajaxForm({
		success:function(o) {
			if (o.is_success == 1) {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);					
				$("#error_container").html(o.message);																
			} else {
				closeDialog('#' + DIALOG_CONTENT_HANDLER);										
				$("#error_container").html(o.message);
			}
		},
		dataType:'json',
		beforeSubmit: function() {
			showLoadingDialog('Updating...');
		}
	});		
});

function checkBoxSwitchState(obj_id) {
	disAbleSprintTables();
	if(obj_id == 'all'){		
		//document.getElementById("truncate_all").checked = true;
		document.getElementById("truncate_recruitment").checked = false;
		document.getElementById("truncate_recommended").checked = false;
	}else if(obj_id == 'db_recruitment'){
		document.getElementById("truncate_all").checked = false;
		document.getElementById("truncate_recommended").checked = false;
	}else{
		document.getElementById("truncate_recruitment").checked = false;
		document.getElementById("truncate_all").checked = false;
		//document.getElementById("truncate_recommended").checked = true;
	}	
}

function disAbleSprintTables() {
	if(document.getElementById("truncate_recommended").checked == true || document.getElementById("truncate_all").checked == true || document.getElementById("truncate_recruitment").checked == true){
		$("#sprint_tables").attr('disabled','disabled');
		$("#sprint_tables").removeClass('validate[required] text');
		$("#sprint_tables").val("");
	}else{
		$("#sprint_tables").addClass('validate[required] text');
		$("#sprint_tables").removeAttr('disabled');
	}
}
</script>
<div id="error_container"></div>
<div class="formWrapper">		
	<form class="form-inline" id="databaseSettings" name="databaseSettings" method="post" action="<?php echo url('settings/truncate_table'); ?>">
    <fieldset>
    <legend>Current SprintHR Version : <?php echo $app_version; ?></legend>
    <div class="alert alert-block alert-error" style="font-size:13px;">
    	Note :
	    <b>Resetting will delete all the data in all tables and will restore to current version factory default values.</b>
        
    </div>    
    	<!-- <select id="sprint_tables" name="sprint_tables" class="validate[required] text" style="width:50%;">
        	<option value="">-- Select Table --</option>
            <?php 
                foreach($tables as $key => $value) {
                    foreach($value as $table) {
                        echo "<option value='{$table}'>{$table}</option>";
                    }
                }
            ?>
        </select>  -->   	
        <!-- <button type="submit" class="btn btn-primary">Truncate</button>     -->    
        <br />
        <!-- <label class="checkbox">
        	<input name="truncate_all" id="truncate_all" type="checkbox" onchange="checkBoxSwitchState('all');" />Truncate All            
        </label>
        <label class="checkbox">
        	<input name="truncate_recruitment" id="truncate_recruitment" type="checkbox" onchange="checkBoxSwitchState('db_recruitment');" />Truncate Recruitment
        </label>
        <label class="checkbox">
        	<input name="truncate_recommended" id="truncate_recommended" type="checkbox" onchange="checkBoxSwitchState('recommended');" />Truncate Recommended
        </label>
        <br /><br /> -->
        <!-- <div>
        	<a href="javascript:void(0);" onclick="javascript:_createRecommendedTables();" class="btn btn-primary">Create Recommended Tables</a>
         <a href="javascript:void(0);" onclick="javascript:load_table_default_values_confirmation();" class="btn btn-primary">Load Default Values</a>
        </div> -->
        <div>
            <a href="<?php echo url('settings/factory_reset');?>"  class="btn btn-primary">Factory Reset</a>
        </div> 
    </fieldset>
    </form>
</div>