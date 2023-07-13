<?php include('includes/_wrappers.php'); ?>
<div class="break-bottom inner_top_option">
	<div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_approved_leave_list_dt(this.value);">
        <option value="" selected="selected">All</option>
        <?php foreach($departments as $d){ ?>
            <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
        <?php } ?>
    </select>
    </div>
    <div class="clear"></div>
</div>
<div id="leave_list_dt_wrapper" class="dtContainer"></div>
<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>
<script>
	$(function() { load_approved_leave_list_dt(0); });
</script>
