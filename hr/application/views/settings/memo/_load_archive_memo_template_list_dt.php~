<script>
$(function() {
	  jq17('.dropdown-toggle').dropdown();
	  var oTable = $('#payroll_period_list').dataTable({   
	   "aoColumns": [
				{ "bSortable": false,sWidth: '7%'},					
				{sWidth: '55%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'}												
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,		
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "desc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'settings/_load_server_archive_memo_template_list_dt',
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="btn-group pull-right">
    <a class="btn dropdown-toggle" href="#">Action <span class="caret"></span></a>
    <ul class="dropdown-menu">		    
	    <li><a onclick="javascript:memo_with_selected_confirmation('restore');" href="javascript:void(0);"><i class="icon-refresh"></i> Restore</a></li>
    </ul>
</div>
<div class="clear"></div>
<br />
<div class="table-container">
<table id="payroll_period_list" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input type="checkbox" title="Check All" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>     
        <th valign="top" width="10%" style="font-size:12px;">Title</th>
        <th valign="top" width="10%" style="font-size:12px;">Created By</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
