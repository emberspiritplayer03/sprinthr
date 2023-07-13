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
<?php foreach($ob as $o): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
       	<td class="infodate"><?php echo date("m/d/Y",strtotime($o->getDateStart())); ?></td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($o->getDateEnd())); ?></td>
        <td align="center" class="comment"><?php echo $o->getComments(); ?></td>
        <td align="center" class="comment blue"><?php echo $o->getIsApproved(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$ob) { ?>
	<tr class="odd">
    	<td colspan="4">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>