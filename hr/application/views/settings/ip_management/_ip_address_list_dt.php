<script>
	$(function() {		
		jq17('.dropdown-toggle_multi').dropdown();	
		  var oTable = $('#ip_address_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},	
					{sWidth: '20%',sClass:'dt_small_font'},				
					{sWidth: '25%',sClass:'dt_small_font'},
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '25%',sClass:'dt_small_font'},
					{ "bSortable": false,sWidth: '10%'}		
			 ],
			"bStateSave": false,
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": false,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			
			'sAjaxSource': base_url + 'settings/_load_ip_address_list_dt',
			"fnDrawCallback": function() {
					jq17('.dropdown-toggle').dropdown();

					$(".btn-edit-ip-address").click(function(){
						var eid = $(this).attr("id");
						showEditIpAddress(eid);
					});

					$(".btn-delete-ip-address").click(function(){
						var eid = $(this).attr("id");
						showDeleteIpAddress(eid);
					});
					
				}
			});
	});
</script>
<div class="table-container">
<table id="ip_address_dt" class="formtable">
    <thead>
      <tr>
      	<th valign="top" ></th> 
      	<th valign="top" >Employee Code</th> 
      	<th valign="top" >Employee Name</th>     
        <th valign="top" >IP Address</th>        
        <th valign="top" >Date Created</th>  
        <th valign="top" ></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
