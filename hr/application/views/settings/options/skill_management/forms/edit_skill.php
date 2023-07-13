<script>
function callSuccessFunction(){
    load_add_new_skill();
};
function callFailFunction(){alert("Error on SQL")}
    $(function(){
        $('#skillManagement').validationEngine({scroll:false});       
        $('#skillManagement').ajaxForm({
            success:function(o) {
                if (o.is_success) {        
                    dialogOkBox(o.message,{});                                  
                    load_skill_management_dt();

                    var $dialog = $('#action_form');                    
                    $dialog.dialog("destroy");                    

                } else {                            
                    dialogOkBox(o.message,{});          
                }                   
            },
            dataType:'json',
            beforeSubmit: function() {
                    showLoadingDialog('Saving...');
            }
        });
    }); 
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="skillManagement" id="skillManagement" method="post" action="<?php echo url("settings/update_skill"); ?>">
    <input type="hidden" value="<?php echo $s->getId(); ?>" name="skill_id" id="skill_id" />   
    <div id="form_default"> 
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Skill:</td>
            <td>
                <input type="text" value="<?php echo $s->getSkill(); ?>" name="skill" class="validate[required] text" id="skill" />    
            </td>
        </tr>       
    </table>
    </div>
    <div id="form_default" class="form_action_section">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">&nbsp;</td>
            <td><input type="submit" class="blue_button" value="Save" /></td>
        </tr>          
    </table>
    </div>
    </form>
</div>