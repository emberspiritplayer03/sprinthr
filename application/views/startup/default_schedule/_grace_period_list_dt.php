<table class="formtable">
<thead>
    <tr>
        <th width="45%">Title</th>
        <th width="30%">Number of Minutes</th>
        <th width="25%">Is Default</th>
    </tr>
</thead>
<?php foreach ($data as $d){?>
<tr>
    <td><?php echo $d->getTitle(); ?></td>
    <td>
		<?php echo $d->getNumberMinuteDefault() . ' min.';?>        
        <a class="link_option"  href="javascript:editGracePeriod('<?php echo Utilities::encrypt($d->getId()); ?>');"><i class="icon-edit"></i> Edit</a>           
    </td>
    <td>
    <?php 
		if($d->getIsDefault() == 1){
			echo "<b>Default</b>";
		}else{
	?>
    <a class="btn btn-mini link_option" href="javascript:setDefaultGracePeriod('<?php echo Utilities::encrypt($d->getId()); ?>');"><i class="icon-star-empty"></i>Set as Default</a>
    <?php } ?>    
    </td>
</tr>
<?php } ?> 
</table>