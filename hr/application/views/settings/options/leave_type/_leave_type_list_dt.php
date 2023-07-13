<script>
	$(function() {
		  var oTable = $('#leave_list').dataTable({   
		   "aoColumns": [		   		
					{"bSortable": false,sWidth: '8%'},									
					{sWidth: '60%',sClass:'dt_small_font'},
					{"bVisible":false,sWidth: '10%'},
					{sWidth: '10%',sClass:'dt_small_font dt_center'},
					{sWidth: '10%',sClass:'dt_small_font dt_center'}						
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			//"bStateSave": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 6, "desc" ]],
			"sPaginationType": "two_button",
			"bPaginate": true,
			'sAjaxSource': base_url + 'settings/_load_server_leave_type_list_dt',
			"fnRowCallback": function(nRow, aData, iDisplayIndex) {					
				var leave_id = aData[2];
				leave_id = leave_id.replace("<span style=\"color:#21729E\">","");
				leave_id = leave_id.replace("</span>","");				
                if (leave_id == 3 || leave_id == 5 || leave_id == 4 || leave_id == 6 || leave_id == 1 || leave_id == 2) {
                	$('li.delete-btn', nRow).remove();                 
                }
                return nRow;
            },
			"fnDrawCallback": function() {					
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
</script>
<div class="table-container">
<table id="leave_list" class="display">
    <thead>
      <tr>     
      	<th valign="top" width="10%"></th>       
        <th valign="top" width="10%">Leave Type</th>  
        <th valign="top" width="10%"></th>
        <th valign="top" width="10%">Default Credit</th> 
        <th valign="top" width="10%">Is Paid</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
