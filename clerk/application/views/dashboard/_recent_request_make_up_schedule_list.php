<table width="100%" class="formtable">
<thead>
    <tr>
        <th class="bold" width="50">From</th>
        <th class="bold" width="50">To</th>
        <th class="bold" width="50">Time-In</th>
        <th class="bold" width="50">Time-Out</th>
        <th class="bold" width="150">Reason</th>
        <th class="bold" width="50">Status</th>
    </tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach($make_up as $mk): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
       	<td class="infodate"><?php echo date("m/d/Y",strtotime($mk->getDateFrom())); ?></td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($mk->getDateTo())); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($mk->getStartTime()); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($mk->getEndTime()); ?></td>
        <td align="center" class="comment"><?php echo $mk->getComment(); ?></td>
        <td align="center" class="comment blue"><?php echo $mk->getIsApproved(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$make_up) { ?>
	<tr class="odd">
    	<td colspan="6">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>