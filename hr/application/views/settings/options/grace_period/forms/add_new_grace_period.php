<div id="form_main" class="inner_form popup_form wider">
	<form name="addGracePeriod" id="addGracePeriod" method="post" action="<?php echo url('settings/save_grace_period'); ?>">
      <div id="form_default">
    <table width="100%"> 
         <tr>
            <td class="field_label">*Title:</td>
            <td><input type="text" value="" name="grace_title" class="validate[required]  text" id="grace_title" /><br />
            
        </tr>
        <tr>
          <td class="field_label">Description:</td>
          <td><input class=" text" type="text" name="grace_period_description" id="grace_period_description" /></td>
        </tr>
        <tr>
            <td class="field_label">*Number of Minutes:</td>
            <td><input type="text" value="" name="number_minute_default" class="validate[required,custom[integer]]  text" id="number_minute_default" /></td>
        </tr>
    </table>
    </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
   	  <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Save" />&nbsp;<a href="#" onclick="javascript:closeDialog('#_dialog-box_','#addGracePeriod');">Cancel</a></td>
          </tr>
        </table>    
    </div><!-- #form_default.form_action_section -->
    </form>
</div>