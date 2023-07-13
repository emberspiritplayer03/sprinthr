<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>
<script>
function callSuccessFunction(){
	load_my_pending_tasks(<?php ?>);
};
function callFailFunction(){alert("Error on SQL")}
	$(document).ready(function() {		
		 $("#taskForm").validationEngine({						
			ajaxSubmit: true,
			scroll: false,
			ajaxSubmitFile: base_url + 'settings/add_company_structure',	
			ajaxSubmitMessage: "",		
			success : function() {load_company_structure();var $dialog = $('#action_form');$dialog.dialog("destroy");},
			unbindEngine:true,
			failure : function() {}

		});
	});
function addType(){
	var s_type = document.getElementById("s_type").value;
	if(s_type == 0){
		load_add_subdivision_type(<?php echo $p_id; ?>);		
	}
}
function addBranch(){
	var company_branch_id = document.getElementById("company_branch_id").value;
	if(company_branch_id == 0){
		load_add_branch(<?php echo $p_id; ?>);
	}
}
</script>
<div class="formWrapper">
	<form name="taskForm" id="taskForm" method="post" action="">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" />    
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td width="30%" valign="top" class="formControl">Name</td>
            <td width="70%" valign="top" class="formLabel">
                <input type="text" value="" name="name" class="validate[required] text-input text" id="name" />    
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Type</td>
            <td width="70%" valign="top" class="formLabel">
            	<select name="s_type" onchange="javascript:addType();" id="s_type" class="validate[required] text">
                <option value="" selected="selected">Please Select</option>
                <?php foreach($subdivision_type as $st){ ?>
                	<option value="<?php echo $st->getType(); ?>"><?php echo $st->getType(); ?></option>
                <?php } ?>                	
                	<option value="0">Others</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="30%" valign="top" class="formControl">Branch</td>
            <td width="70%" valign="top" class="formLabel">
            	<select name="company_branch_id" onchange="javascript:addBranch();" id="company_branch_id" class="validate[required] text">
                <option value="" selected="selected">Please Select</option>
                <?php foreach($branches as $b){ ?>
                	<option value="<?php echo $b->getId(); ?>"><?php echo $b->getName(); ?></option>
                <?php } ?>	                
                	<option value="0">Others</option>
                </select>
            </td>
        </tr>
       </table>
    <br />
    <div align="right">
    <input type="submit" value="Add" />
    </div>
    </form>
</div>