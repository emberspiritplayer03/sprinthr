<div id="form_main" class="inner_form popup_form">
	<form name="edit_project_form" id="editProjectType" method="post" action="<?php echo url('settings/update_project_site'); ?>">   
    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>" />
    <input type="hidden" name="project_id" id="project_id" value="<?php echo $results[0]->id;?>" />
    <div id="form_default">
        <table width="100%"> 
            <tr>
                <td class="field_label">Project Site Name:</td>
                <td >
                 <input type="text"  name="project_site_name" class="validate[required] text" id="project_site_name" value="<?php echo $results[0]->name;?>" style="width:300px;margin-left:20px" />
                </td>
            </tr> 
            <tr>
                <td class="field_label">Project Site Address:</td>
                <td >
                 <input type="text" value="<?php echo $results[0]->location;?>" name="project_site_address" class="validate[required] text" id="project_site_address" style="width:300px;margin-left:20px" /> 
                </td>
            </tr>    
            <tr>
                <td class="field_label">Project Site Descriptions:</td>
                <td >
                 <input type="text" value="<?php echo $results[0]->description;?>" name="project_site_description" class="validate[required] text" id="project_site_description" style="width:300px;margin-left:20px" />        
                </td>
            </tr>
            <tr>
                <td class="field_label">Device Number</td>
                <td>
                    <select class="validate[required] select_option" name="project_site_machine_id" id="remarks" >
                        <option value="" selected="selected">-- Select Device Number --</option>
                        <option value="0">--no device--</option>
                        <?php 
                            
                            foreach($devices as $key => $device) { 
                                $existing_device = 0;
                                foreach($project_devices as $project_device){
                                    if($device->machine_no == $project_device->device_id){
                                        $existing_device = 1;
                                    }
                                }
                                if($existing_device == 0){ ?>
                                    <option value="<?php echo $device->machine_no; ?>"><?php echo $device->machine_no .' - '. $device->device_name; ?></option>
                                <?php } 
                                if($results[0]->device_id == $device->machine_no){ ?>
                                    <option value="<?php echo $device->machine_no; ?>" selected><?php echo $device->machine_no .' - '. $device->device_name; ?></option>
                                <?php }
                            } 
                        ?>
                    </select>
                </td>            
            </tr>
        </table>
   
    </div>
    <div id="form_default" style="float:right;">
            <input value="Update" id="add_project_type_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editProjectType');">Cancel</a>
    </div>    
    </form>
</div>
