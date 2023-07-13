<script>
$(function() {
	  var oTable = $('#payroll_period_list').dataTable({   
	   "aoColumns": [
				{ "bSortable": false,sWidth: '13%'},					
				{sWidth: '60%',sClass:'dt_small_font'},
				{sWidth: '8%',sClass:'dt_small_font'}												
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,		
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "desc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'settings/_load_server_payroll_period_list_dt?selected_year=<?php echo $selected_year; ?>',
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" name="selected_year" id="selected_year" value="<?php echo $selected_year ?>" />
<div class="break-bottom inner_top_option">    
    <div class="datatable_withselect display-inline-block right-space">
        <select disabled="disabled" name="chkAction" id="chkAction" onchange="javascript:payrollPeriodWithSelectedAction(this.value);">
            <option value="">With Selected:</option>            
            <option value="lock_period">Lock Period</option>                     
            <option value="unlock_period">Unlock Period</option>                     
        </select>
    </div>    
    <div class="pull-right">
    	<a class="btn btn-small" href="javascript:addPayrollPeriod('<?php echo $selected_year; ?>');"><i class="icon-plus"></i><b>Add Payroll Period</b></a>
    	<a class="btn btn-small" href="javascript:load_payroll_year();"><i class="icon-arrow-left"></i><b>Back</b></a>
    </div>
    <div class="clear"></div>
</div>
<div class="table-container">
<table id="payroll_period_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
        <th valign="top" width="10%" style="font-size:12px;">Payroll Period</th>
        <th valign="top" width="10%" style="font-size:12px;">Is Lock</th>         
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
</form>
