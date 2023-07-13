<form name="leaveRequestWithSelectedAction" id="leaveRequestWithSelectedAction">  
<div class="break-bottom inner_top_option">    
    <div class="select_dept display-inline-block right-space">
        <strong>Select Department:</strong> <select id="cmb_dept_id" onchange="javascript:load_leave_list_archives_dt(this.value);">
        <option value="" selected="selected">All</option>
        <?php foreach($departments as $d){ ?>
            <option value="<?php echo $d->getId(); ?>"><?php echo $d->getTitle(); ?></option>
        <?php } ?>
    </select>
    </div>
    <div class="clear"></div>
</div>     
<div class="break-bottom">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkActionSub" id="chkActionSub" onchange="javascript:archiveWithSelectedAction(this.value);">
        <option value="">With Selected:</option>
        <option value="restore_leave_request">Restore Archived</option>                        
    </select>
    </div>    
    <div class="clear"></div>
</div>  
    <div id="leave_list_dt_wrapper" class="dtContainer"></div>    
</form>
<div id="import_leave_wrapper" style="display:none">
<?php include 'form/import_leave.php'; ?>
</div>