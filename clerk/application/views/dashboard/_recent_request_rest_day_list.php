<table width="100%" class="formtable">
<thead>
    <tr>
        <th class="bold" width="50">From</th>
        <th class="bold" width="50">To</th>
        <th class="bold" width="150">Reason</th>
        <th class="bold" width="50">Status</th>
    </tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach($rest_day as $rd): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
       	<td class="infodate"><?php echo date("m/d/Y",strtotime($rd->getDateStart())); ?></td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($rd->getDateEnd())); ?></td>
        <td align="center" class="comment"><?php echo $rd->getRestDayComments(); ?></td>
        <td align="center" class="comment blue"><?php echo $rd->getIsApproved(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$rest_day) { ?>
	<tr class="odd">
    	<td colspan="4">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>