<?php include('includes/_wrappers.php'); ?>
<div class="break-bottom inner_top_option">
	<div id="detailscontainer" class="detailscontainer_blue details_highlights">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><small><?php echo $sub_page_title;?></small></div>
        </div>
    </div>
    <div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_employee_leave_credit_dt(this.value);">
        <option value="" selected="selected">All</option>
        <?php foreach($departments as $d){ ?>
            <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
        <?php } ?>
    </select>
    </div>
    <div style="float:right">
        <?php echo $btn_import_leave_credits; ?>
        <!-- <a class="add_button pull-right" onclick="javascript:importLeaveCredits();" href="#">Import Leave Credits</a> -->
    </div>
    <div class="clear"></div>
</div>
<div id="leave_list_dt_wrapper" class="dtContainer"></div>
<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>
<script>
	$(function() { load_employee_leave_credit_dt(0); });
</script>
