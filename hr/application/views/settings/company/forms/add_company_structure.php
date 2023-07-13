<!--<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>-->
<script>
function addType(){
	var s_type = document.getElementById("s_type").value;
	if(s_type == 0){
		//load_add_subdivision_type(<?php echo $p_id; ?>);		
	}
}

function quickAddBranch(){
	var company_branch_id = $("#company_branch_id").val();
	if(company_branch_id == 0){
		addSubBranch(<?php echo $main_parent; ?>);
	}
}
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="addCompanyStructure" id="addCompanyStructure" method="post" action="<?php echo url('settings/add_company_structure'); ?>">
    <input type="hidden" value="<?php echo $p_id; ?>" name="parent_id" id="parent_id" /> 
    <input type="hidden" id="company_branch_id" name="company_branch_id" readonly="readonly" value="<?php echo ($b ? $b->getId() : $cs->getCompanyBranchId()); ?>" />     
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label">Name:</td>
            <td>
                <input type="text" value="" name="name" class="validate[required]  text" id="name" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Type:</td>
            <td>
            	<select name="s_type" onchange="javascript:addType();" id="s_type" class="validate[required] text">
                <option value="" selected="selected">Please Select</option>
                <?php foreach($subdivision_type as $st){ ?>
                	<?php
						if($cs->getType() == 'Department' || $cs->getType() == 'Group'){
							if($st->getType() == 'Department'){ 
							
							}else{
					?>
                    		<option value="<?php echo $st->getType(); ?>"><?php echo $st->getType(); ?></option>                    	
                            <?php } ?>	
					<?php }else{ ?>
						<option value="<?php echo $st->getType(); ?>"><?php echo $st->getType(); ?></option>                    	
                    <?php }} ?>
                    
					             	
                	<!--<option value="0">Others</option>-->
                </select>
            </td>
        </tr>
        <!--<tr>
            <td class="field_label">Branch</td>
            <td>
            	
            	<select name="company_branch_id" onchange="javascript:quickAddBranch(<?php //echo $main_parent; ?>);" id="company_branch_id" class="validate[required] text">
                <option value="" selected="selected">Please Select</option>
                <?php //foreach($branches as $b){ ?>
                	<option value="<?php //echo $b->getId(); ?>"><?php //echo $b->getName(); ?></option>
                <?php //} ?>	                
                	<option value="0">Others</option>
                </select>
            </td>
        </tr>-->
       </table>
	</div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addCompanyStructure');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>