<table width="100%" class="formtable">
<thead>
    <tr>
    	<th class="bold" width="150">Employee Name</th>
        <th class="bold" width="50">From</th>
        <th class="bold" width="50">To</th>
        <th class="bold" width="100">Type</th>
        <th class="bold" width="120">Reason</th>
    </tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach($leave as $l): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
    	<td class="comment">
			<?php
				$e = G_Employee_Finder::findById($l->getEmployeeId());
				if($e) {echo $e->getName();	}
			 ?>
        </td>
       	<td class="infodate"><?php echo date("m/d/Y",strtotime($l->getDateStart())); ?></td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($l->getDateEnd())); ?></td>
        <td align="center" class="comment">
			<?php 
				$lt = G_Leave_Finder::findById($l->getLeaveId());
				if($lt) {
					echo $lt->getName();	
				}
			?>
        </td>
        <td align="center" class="comment"><?php echo $l->getLeaveComments(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$leave) { ?>
	<tr class="odd">
    	<td colspan="5">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>