<!--<style>
	h3.cinfo-header-form{width:99%; background-color:#666; color:#FFF; padding:10px 0 8px 5px; margin:0;}
	.text{width:250px;}
</style>-->
<div id="form_main" class="inner_form popup_form">
	<form name="editSubdivision" id="editSubdivision" method="post" action="<?php echo url('settings/add_company_branch'); ?>">
    <input type="hidden" value="<?php echo Utilities::encrypt($b->getId()); ?>" name="eid" id="eid" />    
    <div id="form_default">
    <table width="100%" border="0" cellpadding="3" cellspacing="0"> 
        <tr>
            <td class="field_label">Name:</td>
            <td>
                <input type="text" value="<?php echo $b->getName(); ?>" name="name" class="validate[required]  text" id="name" />    
            </td>
        </tr>    
        <tr>
            <td class="field_label">Location:</td>
            <td>
                <select name="location_id" id="location_id" style="width:74%;">
                	<?php foreach($locations as $l){ ?>
                    	<option <?php echo($b->getLocationId() == $l->getId() ? 'selected="selected"' : ''); ?> value="<?php echo $l->getId(); ?>"><?php echo $l->getLocation(); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>   
        <tr>
            <td class="field_label">Province:</td>
            <td>
                <input type="text" value="<?php echo $b->getProvince(); ?>" name="province" class="validate[optional]  text" id="province" />    
            </td>
        </tr>
         <tr>
            <td class="field_label">City:</td>
            <td>
                <input type="text" value="<?php echo $b->getCity(); ?>" name="city" class="validate[optional]  text" id="city" />    
            </td>
        </tr>    
         <tr>
            <td class="field_label">Address:</td>
            <td>
                <input type="text" value="<?php echo $b->getAddress(); ?>" name="address" class="validate[optional]  text" id="address" />    
            </td>
        </tr>
         <tr>
            <td class="field_label">Zip Code:</td>
            <td>
                <input type="text" value="<?php echo $b->getZipCode(); ?>" name="zip_code" class="validate[optional]  text" id="zip_code" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Phone:</td>
            <td>
                <input type="text" value="<?php echo $b->getPhone(); ?>" name="phone" class="validate[optional]  text" id="phone" />    
            </td>
        </tr>
        <tr>
            <td class="field_label">Fax:</td>
            <td>
                <input type="text" value="<?php echo $b->getFax(); ?>" name="fax" class="validate[optional]  text" id="fax" />    
            </td>
        </tr>     
    </table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td><input value="Save" id="edit_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#editSubdivision');">Cancel</a></td>
            </tr>
		</table>
    </div>    
    </form>
</div>