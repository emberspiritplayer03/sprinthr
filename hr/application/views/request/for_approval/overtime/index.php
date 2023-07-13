<script>
	$(function() {
		$("#tabs").tabs();
		load_fa_overtime_list_dt();
	});
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1" onclick="javascript:load_fa_overtime_list_dt();">Pending</a></li>
        <!--<li><a href="#tabs-2" onclick="javascript:load_fa_overtime_approved_list_dt();">Approved</a></li>-->
		
	</ul>    
	<div id="tabs-1">	
        <div align="right">
        Select Department : <select id="department" name="department" style="width:220px;" onchange="javascript:load_fa_overtime_list_dt();">
        <option value="">All</option>
            <?php foreach($department as $d): ?>
                <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
            <?php endforeach; ?>
        </select>
        </div>
        <div id="fa_overtime_list_dt_wrapper" class="dtContainer"></div>
	</div>  
    
    <div id="tabs-2" style="display:none;">
     	<div align="right">
            Select Department : <select id="department_2" name="department_2" style="width:220px;" onchange="javascript:load_fa_overtime_list_dt();">
            <option value="">All</option>
                <?php foreach($department as $d): ?>
                    <option value="<?php echo Utilities::encrypt($d->getId()); ?>"><?php echo $d->getTitle(); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
		<div id="fa_overtime_approved_list_dt_wrapper" class="dtContainer"></div>
	</div>
</div>


<div id="view_fa_request_form_wrapper"></div>
<div id="view_fa_request_approvers_wrapper"></div>