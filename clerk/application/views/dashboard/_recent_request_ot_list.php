<table width="100%" class="formtable">
<thead>
    <tr>
    	<th class="bold" width="150">Employee Name</th>
        <th class="bold" width="50">Date</th>
        <th class="bold" width="60">Time-In</th>
        <th class="bold" width="60">Time-Out</th>
        <th class="bold" width="120">Reason</th>
    </tr>
</thead>
<tbody>
<?php $i = 0; ?>
<?php foreach($ot as $o): ?>
<?php $i++; ?>
    <tr <?php echo ($i % 2 == 0 ? 'class="even"' : 'class="odd"'); ?>>
	    <td class="comment">
			<?php
				$e = G_Employee_Finder::findById($o->getEmployeeId());
				if($e) {echo $e->getName();	}
			 ?>
        </td>
        <td class="infodate"><?php echo date("m/d/Y",strtotime($o->getDateStart())); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($o->getTimeIn()); ?></td>
        <td class="infodate"><?php echo Tools::convert24To12Hour($o->getTimeOut()); ?></td>
        <td class="comment"><?php echo $o->getOvertimeComments(); ?></td>
    </tr>
<?php endforeach; ?>
<?php if(!$ot) { ?>
	<tr class="odd">
    	<td colspan="4">No result(s) found.</td>
    </tr>
<?php } ?>
</tbody>
</table>