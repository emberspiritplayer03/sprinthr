<script>
$(document).ready(function() {	
	$('#withSelectedAction').validationEngine({scroll:false});	
});

function countChecked()
{		
	var inputs     = document.withSelectedAction.elements['dtChk[]'];
	var is_checked = false;
	var cnt        = 0;
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
			if(is_checked){								 
			 	cnt++;
			}
    }
	
	return cnt;

}

function chkUnchk()
{
	var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {
		$('#check_uncheck').attr('title', 'Uncheck All');							
		$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 		
		$('#check_uncheck').attr('title', 'Check All');					
		$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

</script>
<div id="main">

<?php include('includes/_wrappers.php'); ?>
<form name="withSelectedAction" id="withSelectedAction">
<div class="break-bottom inner_top_option">
	<div id="detailscontainer" class="detailscontainer_blue details_highlights">
        <div class="earnings_period_selected">
            <div class="overtime_title_period"><small><?php echo $sub_page_title;?></small></div>
        </div>
    </div>
<!--    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:leaveTypeWithSelectedAction(this.value);">
            <option value="">With Selected:</option>        
            <option value="archive">Send to Archive</option>                    
        </select>
    </div>-->
    <div class="clear"></div>
</div>    
    <div id="leave_list_dt_wrapper" class="dtContainer"></div>    
</form>

<table id="box-table-a" class="formtable" summary="Schedule">
    <thead>
    <tr>
        <th width="20" scope="col">Leave Type</th>
        <th width="20" scope="col">Default Credit</th>
        <th width="20" scope="col">Is Paid</th>
        <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
            <th width="50" scope="col">Action</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($leaves as $l):?>
            <tr>
                <td><?php echo $l->getName();?></td>
                <td><?php echo $l->getDefaultCredit();?></td>
                <td><?php echo $l->getIsPaid();?></td>
                <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                    <td><?php echo G_Leave_Helper::getActionLinks($l);?></td>
                <?php } ?>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<br>
<div style="text-align: center"><?php echo $pager_links;?></div>

</div>
</div>

<script language="javascript">
    $('.g_icon').tipsy({gravity: 's'});
    //$('.info').tipsy({gravity: 's'});
</script>

<script>
	//$(function() { load_leave_type_list_dt(); });
</script>
