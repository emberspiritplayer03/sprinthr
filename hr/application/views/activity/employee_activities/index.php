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

function showPageByDepartment(group_id) {
	var query = window.location.search;
	
	if (query == '') {
		query = '?group_id=' + group_id;
	}
	else {
		query = query.replace('?', '');
		var explode_query = query.split('&');
		query = '?';

		for (i = 0; i < explode_query.length; i++) {
			var explode_value = explode_query[i].split('=');

			if (explode_value[0].toLowerCase() != 'group_id') {
				query = query + (query != '?' ? '&' + explode_query[i] : explode_query[i]);
			}
		}
		
		query = query + (query != '?' ? '&group_id=' + group_id : 'group_id=' +group_id);
	}

    window.location.href = base_url + 'activity/employee_activities'+ query;
}

</script>

<?php include('includes/_wrappers.php'); ?>

<style type="text/css">
	#generate_activity{
		background: #3498db !important;
		color: #fff !important;
		text-shadow: none;
		border:1px solid #2980b9;
	}

</style>

<form name="withSelectedAction" id="withSelectedAction">
    <div class="break-bottom inner_top_option"> 

    	<h2><?php echo $sub_title;?></h2><br>
   
		<div class="select_dept display-inline-block right-space">
			<strong>Show by department:</strong>
				<select class="select_option_sched" id="department_id" name="department_id" onchange="javascript:showPageByDepartment(this.value)">
				<option value="">All</option>
				<?php foreach($departments as $d){ ?>
					<option <?php echo ($group_id == $d->getId()) ? 'selected="selected"' : ''  ;?> value="<?php echo $d->getId(); ?>"><?php echo $d->getName(); ?></option>
				<?php } ?>
			</select>
			<br/>
		</div>

        
        <div class="clear"></div>
    </div>
    <div id="employee_activities_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() {
		load_employee_activities_list_dt();       
	});
</script>