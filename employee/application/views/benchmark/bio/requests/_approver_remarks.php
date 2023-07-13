<h4 class="approver-name">Approver : Approved / Disapproved Details</h4>
<table width="100%">
	 <tr>
	       <td class="field_label">Date approved / disapproved </td>
	       <td>
	       		: <input class="input-large" type="text" readonly="readonly" value="<?php echo $action_date; ?>" style="width:292px;" />
	       </td>
	 </tr> 
	 <tr>
	       <td class="field_label">Your action made </td>
	       <td>
	       		: <input class="input-large" type="text" readonly="readonly" value="<?php echo $a_status; ?>" style="width:292px;" />
	       </td>
	 </tr>	 
	 <tr>
	       <td class="field_label">You Remarks </td>
	       <td>
	       		: <textarea style="height:110px;" readonly="readonly"><?php echo $approver_remarks; ?></textarea>	       			
	       </td>
	</tr>   	
</table>
