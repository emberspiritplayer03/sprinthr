<script>
	var jq17 = jQuery.noConflict();  
	$(function() {		
		jq17('.dropdown-toggle_multi').dropdown();	
		  var oTable = $('#ot_allowance_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},
					{sWidth: '22%',sClass:'dt_small_font'},
					{sWidth: '15%',sClass:'dt_small_font'},	
					{sWidth: '60%',sClass:'dt_small_font'},	
					{sWidth: '15%',sClass:'dt_small_font', "bVisible": false},	
					{sWidth: '22%',sClass:'dt_small_font', "bVisible": false},	
					{sWidth: '20%',sClass:'dt_small_font', "bVisible": false},				
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
			
			'sAjaxSource': base_url + 'overtime_settings/_load_overtime_allowance_list_dt',
			"fnDrawCallback": function() {
					jq17('.dropdown-toggle').dropdown();

					$(".btn-edit-ot-allowance").click(function(){
						var eid = $(this).attr("id");
						showEditOtAllowance(eid);
					});

					$(".btn-delete-ot-allowance").click(function(){
						var eid = $(this).attr("id");
						showDeleteOtAllowance(eid);
					});
				}
			});
	});
</script>
<div class="table-container">
<table id="ot_allowance_dt" class="formtable">
    <thead>
      <tr>
      	<th valign="top" ></th> 
      	<th valign="top" >Applied to</th>
      	<th valign="top" >Starts on</th>
      	<th valign="top" >Description</th>
      	<th valign="top" ></th>
      	<th valign="top" ></th>
      	<th valign="top" ></th>
        <th valign="top" ></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
