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
<?php foreach($change_schedule as $ch): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
       	<td class="infodate"><?php echo date("m/d/Y",strtotime($ch->getDateStart())); ?></td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($ch->getDateEnd())); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($ch->getTimeIn()); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($ch->getTimeOut()); ?></td>
        <td align="center" class="comment"><?php echo $ch->getChangeScheduleComments(); ?></td>
        <td align="center" class="comment blue"><?php echo $ch->getIsApproved(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$change_schedule) { ?>
	<tr class="odd">
    	<td colspan="5">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>