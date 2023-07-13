<table width="100%" class="formtable">
<thead>
    <tr>
        <th class="bold" width="50">Date</th>
        <th class="bold" width="100">Time-Out</th>
        <th class="bold" width="150">Reason</th>
        <th class="bold" width="50">Status</th>
    </tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach($undertime as $u): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($u->getDateOfUndertime())); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($u->getTimeOut()); ?></td>
        <td class="comment"><?php echo $u->getReason(); ?></td>
        <td align="center" class="comment blue"><?php echo $u->getIsApproved(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$undertime) { ?>
	<tr class="odd">
    	<td colspan="4">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>