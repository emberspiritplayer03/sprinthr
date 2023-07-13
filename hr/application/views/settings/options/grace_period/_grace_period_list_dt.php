<table class="formtable">
<thead>
    <tr>
        <th width="45%">Title</th>
        <th width="30%">Number of Minutes</th>
        <!--<th width="25%">Is Default</th>-->
    </tr>
</thead>
<?php foreach ($data as $d){?>
<tr>
    <td><?php echo $d->getTitle(); ?></td>
    <td>
		<?php echo $d->getNumberMinuteDefault() . ' min.';?>        
        <a class="link_option"  href="javascript:editGracePeriod('<?php echo Utilities::encrypt($d->getId()); ?>');"><i class="icon-edit"></i> Edit</a>           
        <!--<a class="link_option"  href="javascript:archiveGracePeriod('<?php echo Utilities::encrypt($d->getId()); ?>');"><i class="icon-edit"></i> Delete</a>-->
    </td>
    <!--<td>-->
    <?php 
	//	if($d->getIsDefault() == 1){
	//		echo "<b>Default</b>";
	//	}else{
	?>
    <!--<a class="btn btn-mini link_option" href="javascript:setDefaultGracePeriod('<?php echo Utilities::encrypt($d->getId()); ?>');"><i class="icon-star-empty"></i>Set as Default</a>-->
    <?php //} ?>
    <!--</td>-->
</tr>
<?php } ?> 
</table>

<br>

<h3 style="margin-bottom: 2px;">Exempted employees for grace period.</h3>
<div class=""><a href="javascript:;;" class="btn btn-primary" onclick="addGPExmptedEmployees()">Add Exempted Employees</a></div>
<div id="exempted_wrapper"></div>


<script type="text/javascript">
    load_gp_excempted_employees();
</script>