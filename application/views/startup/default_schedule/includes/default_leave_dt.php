<table class="formtable">
<thead>
    <tr>
        <th width="50%">Leave Type</th>
        <th width="50%">Default number of credits</th>
    </tr>
</thead>
<?php if($default_leave){ foreach($default_leave as $default):?>
<tr>
    <td><?php echo $default->getName(); ?></td>
    <td>
    	<?php echo $default->getDefaultCredit(); ?>	
	    <a class="link_option" href="javascript:editDefaultLeaveCredits('<?php echo Utilities::encrypt($default->getId()); ?>');"><i class="icon-edit"></i> Edit</a>
    </td>
</tr>
<?php endforeach;}?>
</table>