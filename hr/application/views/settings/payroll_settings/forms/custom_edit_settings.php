<style>
.settings-remarks{font-size: 10px;font-weight: bold;}
.select-small{width:114px !important;}
</style>
<script>
$(function(){
    <?php if($with_date_picker){ ?>
        
    <?php } ?>
});
</script>
<div id="form_main" class="inner_form popup_form">
	<form name="editSetting" id="editSetting" method="post" action="<?php echo $action; ?>">   
    <input type="hidden" name="eid" value="<?php echo $eid; ?>" />
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <div id="form_default">
    <table width="100%"> 
        <tr>
            <td class="field_label" style="width:40%;"><?php echo $variable_description; ?></td>
            <td >:
                <?php 
                    foreach( $form_object as $object ){
                        $object_name  = '';
                        $object_class = '';  
                        $object_value = '';

                        if( isset($object['value']) ){
                            $object_value = "value=\"" . $object['value'] . "\"";
                        }                             

                        if( isset($object['name']) ){
                            $object_name = "name=\"" . $object['name'] . "\"";
                        }

                        if( isset($object['class']) ){
                            $object_class = "class=\"" . $object['class'] . "\"";
                        }

                        switch ($object['input_type']) {
                            case 'select':                                
                                $frm_object = "<select {$object_name} {$object_class}>";
                                    foreach( $object['options'] as $option ){
                                        $selected_tag = '';
                                        if( isset($object['selected']) && $option == $object['selected'] ){
                                            $selected_tag = "selected=\"selected\"";
                                        }
                                        $frm_object .= "<option {$selected_tag}>{$option}</option>";
                                    }
                                $frm_object .= "</select>";
                                break;                            
                            default:
                                $input_type = $object['input_type'];
                                $frm_object = "<input type=\"{$input_type}\" {$object_value} {$object_name}>";
                                break;
                        }
                        echo $frm_object;
                    }
                ?>               
            </td>
        </tr>        
        <tr>
            <td colspan="2"><?php echo $custom_input_a; ?></td>
        </tr>
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="add_leave_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#addRequirement');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>
