
<form id="disapprove_request_form" method="post" action="<?php echo url('dashboard/_disapprove_request'); ?>">
	<input type="hidden" id="token" name="token" value="<?php echo $token; ?>">
	<input type="hidden" id="eid" name="eid" value="<?php echo $eid;?>" >
	<div id="form_main" class="inner_form popup_form wider">
	    <div id="form_default">
		    <table class="no_border" width="100%">
		    	<tbody> 
		    		<tr>
		    			<td class="field_label" colspan="2">
		    				<div class="alert alert-warning"><i class="icon icon-exclamation-sign"></i> Are you sure you want to <b>Disapprove</b></div>
		    			</td>
		    		</tr>
			        <tr>
						<td align="right" class="field_label">Remarks</td>
						<td><textarea id="remarks" name="remarks" style="min-width:290px !important;"></textarea></td>
			        </tr>  
		        </tbody>
		    </table>
	    </div>

	    <span id="schedule_message"></span>
	    <div id="form_default" class="form_action_section">
	        <table class="no_border" width="100%">
	            <tbody>
		            <tr>
		                <td class="field_label">&nbsp;</td>
		                <td>
		                    <input value="Submit" id="" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="closeTheDialog()">Cancel</a>
		                </td>
		            </tr>
	        	</tbody>
	        </table>            
	    </div>
	</div>
</form>