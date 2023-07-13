<?php 
	$date_requested = date("F d, Y",strtotime($request_data['date_applied']));
	$leave_type     = $request_data['leave_type'];
	$from_date      = date("F d, Y",strtotime($request_data['date_start']));
	$to_date        = date("F d, Y",strtotime($request_data['date_end']));	
	$request_status = $request_data['is_approved'];
	$leave_comments = $request_data['leave_comments'];	
	$show_update_btn = false;
	$read_only       = "readonly='readonly'";
	$show_approvers_remarks = false;
	if( $a_status == G_Employee_Leave_Request::PENDING ){
		$show_update_btn = true;
		$read_only 		 = '';
	}else{
		$show_approvers_remarks = true;
	}
?>
<p>Note : Request will be lock for editing once approved or disapproved.</p>
<h4 class="approver-name">Requestor : Leave Request Details</h4>
<div id="form_default">      
<table>
	 <tr>
	       <td class="field_label">Requested By </td>
	       <td>
	       		: <input class="input-large" type="text" readonly="readonly" value="<?php echo $request_data['requested_by']; ?>" style="width:292px;" />
	       </td>
	     </tr>   	
	<tr>
		<td class="field_label">Status </td>
		<td>
			: <input class="input-large" type="text" readonly="readonly" value="<?php echo $request_status; ?>" style="width:292px;" />
		</td>
	</tr> 
	<tr>
		<td class="field_label">Leave Type </td>
		<td>
			: <input class="input-large" type="text" readonly="readonly" value="<?php echo $leave_type; ?>" style="width:292px;" />
		</td>
	</tr>
	<tr>
		<td class="field_label">From </td>
		<td>
			: <input class="input-large" type="text" readonly="readonly" value="<?php echo $from_date; ?>" style="width:292px;" />
		</td>
	</tr>
	<tr>
		<td class="field_label">To </td>
		<td>
			: <input class="input-large" type="text" readonly="readonly" value="<?php echo $to_date; ?>" style="width:292px;" />
		</td>
	</tr>
	<tr>
		<td class="field_label">Comment / Remarks </td>
		<td>
			: <textarea style="height:80px;" readonly="readonly"><?php echo $leave_comments;  ?></textarea>
		</td>
	</tr>
	<?php if( $show_update_btn ){ ?>
	<tr>
		<td class="field_label">Add Comment / Remarks </td>
		<td>
			: <textarea style="height:110px;" name="approver-remarks" id="approver-remarks" <?php echo $read_only; ?>></textarea>
		</td>
	</tr>  
	<?php } ?>
</table>
<?php 
	if( !$show_update_btn ){ 
		include_once('_approver_remarks.php');
	}
?>
</div>
<?php if( $show_update_btn ){ ?>
<div class="errContainer"></div>
<div id="form_default" class="form_action_section">
	<br />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
    	<tr>
        	<td class="field_label">&nbsp;</td>
            <td>
            <input type="submit" name="status" class="btn-approve-disapprove curve blue_button" value="Approve" />         
            <input type="submit" name="status" class="btn-approve-disapprove curve blue_button" value="Disapprove" />                
            </td>
        </tr>
    </table>
</div>
<?php } ?>
