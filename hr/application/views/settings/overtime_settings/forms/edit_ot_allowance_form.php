<style>
.ot-allowance-header{padding:8px;background-color: #D6D6D6;font-weight: bold;margin-top:14px;}
.list-day-type li{list-style: none; display: inline-block; margin:13px; }
</style>
<form id="edit_ot_allowance_form" method="post" action="<?php echo url('overtime_settings/update_ot_allowance'); ?>">	
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($oa->getId());?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">	    	
	    	<p class="ot-allowance-header">Applied to : <b><?php echo $oa->getDescription();?></b></p>
	    	<input class="" readonly="readonly" type="hidden" name="description" id="description" value="<?php echo $oa->getDescription();?>" style="width:97%;">		    		   
		    <div class="form-error"></div>
          	<!-- <ul class="list-day-type">
            	<?php 
            		foreach( $day_types as $key => $type ){ 
            			$checked = "";
            			if( in_array($key, $data_day_types) ){
            				$checked  = 'checked="checked"';
            			}
            	?>
              		<li><label class="checkbox"><input type="checkbox" <?php echo $checked; ?> class="chk_day_type" name="day_type[<?php echo $key; ?>]" value="1" /><?php echo $type; ?></label></li>
            	<?php } ?>
          	</ul> -->
          	<hr style="display:block !important;" />

		    <table class="no_border" width="100%">	
		    	<tr>
	                   <td style="width:24%" align="left" valign="middle">Apply to </td>
	                   <td style="width:76%" align="left" valign="middle"> 
	                      <select name="day_type" class="validate[required]" id="day_type">	                   	  	
	                   	  	<?php foreach( $day_types as $key => $type ){ echo $key; ?>
	                   	  		<option value="<?php echo $key; ?>" <?php echo($data_day_types[0] == $key ? 'selected="selected"' : ''); ?>><?php echo $type; ?></option>
	                   	  	<?php } ?>
	                   	  </select> 
	                   </td>
	            </tr>
		        <tr>
	                   <td style="width:24%" align="left" valign="middle">Add amount of </td>
	                   <td style="width:76%" align="left" valign="middle"> 
	                      <div class="input-append">
	                        <input class="validate[required,custom[number]] text-input" style="width:18%;height:20px;z-index:99999 !important;text-align:center;" type="text" name="ot_allowance" id="ot_allowance" value="<?php echo $oa->getOtAllowance();?>" />                         
	                        <span class="add-on" style="width:66px;">Pesos</span>
	                      </div>
	                   </td>
	            </tr>
	            <tr>
	                <td style="width:24%" align="left" valign="middle">For every  </td>
	                <td style="width:76%" align="left" valign="middle"> 
	                	<div class="input-append">
                            <input class="validate[required,custom[number]] text-input" type="text" style="width:18%;height:20px;z-index:99999 !important;text-align:center;" name="multiplier" id="multiplier" value="<?php echo $oa->getMultiplier();?>" />                         
                            <span class="add-on" style="width:66px;">OT hours</span>
                        </div>	                     
	                </td>
	            </tr>
	            <tr>
	                <td style="width:24%" align="left" valign="middle">Maximum of </td>
	                <td style="width:76%" align="left" valign="middle"> 
	                	<div class="input-append">
                            <input class="validate[required,custom[number]] text-input" type="text" style="width:18%;height:20px;z-index:99999 !important;text-align:center;" name="max_ot_allowance" id="max_ot_allowance" value="<?php echo $oa->getMaxOtAllowance();?>" />                   
                            <span class="add-on" style="width:66px;">Pesos a day</span>
                        </div>	                     
	                </td>
	            </tr>
	            <tr>
	                <td style="width:24%" align="left" valign="middle">Starts on </td>
	                <td style="width:76%" align="left" valign="middle"> 
	                   <input class="validate[required] text-input" type="text" style="padding-left:7px;width:41%;" name="date_start" id="date_start" value="<?php echo $oa->getDateStart();?>" />
	                </td>
	            </tr>		        
		    </table>
	    </div>

	    <span id="schedule_message"></span>
	    <div id="form_default" class="form_action_section">
	        <table class="no_border" width="100%">
	            <tbody>
		            <tr>
		                <td class="field_label">&nbsp;</td>
		                <td>
		                    <input value="Save" id="add_schedule_submit" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeDialog('#overtime-allowance-edit-container');">Cancel</a>
		                </td>
		            </tr>
	        	</tbody>
	        </table>            
	    </div>
	</div>
</form>