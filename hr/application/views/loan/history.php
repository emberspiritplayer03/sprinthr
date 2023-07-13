<?php include('includes/_wrappers.php'); ?>
<form name="withSelectedAction" id="withSelectedAction">
<div class="break-bottom inner_top_option">    
    <div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_employee_list_dt(this.value);">
            <option value="" selected="selected">All</option>
            <?php foreach($departments as $d){ ?>
                <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
            <?php } ?>
        </select>
    </div>    
    <div class="clear"></div>
</div>
    <div id="loan_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() { load_employee_list_dt(0); });
</script>
