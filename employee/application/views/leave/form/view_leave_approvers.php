<table class="table table-hover">
<thead>
	<tr>
		<th>Approver #</th>
		<th>Approver Name</th>
		<th>Status</th>
		<th>Remarks</th>
	</tr>
</thead>
<tbody>
	<?php $counter = 0;?>
	<?php foreach($request_approvers as $approver) { ?>
		<?php 
			$counter++;

			if($approver->getStatus() == G_Request::APPROVED) { 
				$status = "success";
			}elseif($approver->getStatus() == G_Request::DISAPPROVED) {
				$status = "error";
			}else{
				$status = "warning";
			}
		?>
		<tr class="<?php echo $status;?> ">
			<td><?php echo $counter;?></td>
			<td><?php echo $approver->getApproverName();?></td>
			<td><?php echo $approver->getStatus();?></td>
			<td><?php echo $approver->getRemarks();?></td>
		</tr>
	<?php } ?>
</tbody>
</table>