<script>
	$(function() {
		  var oTable = $('#make_up_schedule_list').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '8%'},					
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'}							
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,			
			'sAjaxSource': base_url + 'make_up_schedule/_load_server_make_up_schedule_pending_list_dt',
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
<table id="make_up_schedule_list" class="display">
    <thead>
      <tr>
      	<th valign="top" width="10%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>              
        <th valign="top" width="10%">Date From</th>
        <th valign="top" width="10%">Date To</th>
        <th valign="top" width="10%">Start Time</th>
        <th valign="top" width="10%">End Time</th> 
        <th valign="top" width="10%">Comment</th>               
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
