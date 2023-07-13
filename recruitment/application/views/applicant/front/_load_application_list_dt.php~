<script>
$(function() {
	  var oTable = $('#payroll_period_list').dataTable({   
	   "aoColumns": [
	   		{ "bSortable": false,sWidth: '8%'},
				{sWidth: '25%',sClass:'dt_small_font'},
				{sWidth: '20%',sClass:'dt_small_font'},
				{sWidth: '15%',sClass:'dt_small_font'},												
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,		
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "desc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'applicant/_load_server_application_list_dt',
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="table-container">
<table id="payroll_period_list" class="display">
    <thead>
      <tr>     
        <th valign="middle" width="5%">Action</th>	
        <th valign="top" width="10%" style="font-size:12px;">Job Title</th>
        <th valign="top" width="10%" style="font-size:12px;">Date of Application</th>
        <th valign="top" width="10%" style="font-size:12px;">Status</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>