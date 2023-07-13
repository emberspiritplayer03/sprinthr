<script>
	$(function() {
		datatable_loader(<?php echo $sidebar; ?>);	
	});
</script>

<?php include('includes/_wrappers.php'); ?>
<div align="right" style="margin-bottom:10px;">
    Select Department : <select id="department_id" name="department_id" onchange="javascript:datatable_loader(<?php echo $sidebar; ?>);">
        <option value="" selected="selected">All</option>
        <?php foreach($departments as $d){ ?>
            <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
        <?php } ?>
    </select>
</div>

<div id="overtime_list_dt"></div>