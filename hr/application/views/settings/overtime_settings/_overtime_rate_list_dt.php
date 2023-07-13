<script>
	var jq17 = jQuery.noConflict();  
	$(function() {		
		jq17('.dropdown-toggle_multi').dropdown();	
		  var oTable = $('#ot_rate_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},
					{sWidth: '55%',sClass:'dt_small_font'},
					{sWidth: '15%',sClass:'dt_small_font'},								
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
			
			'sAjaxSource': base_url + 'overtime_settings/_load_overtime_rate_list_dt',
			"fnDrawCallback": function() {
					jq17('.dropdown-toggle').dropdown();

					$(".btn-edit-ot-rate").click(function(){
						var eid = $(this).attr("id");
						showEditOtRate(eid);
					});

					$(".btn-delete-ot-rate").click(function(){
						var eid = $(this).attr("id");
						showDeleteOtRate(eid);
					});
				}
			});
	});
</script>
<div class="table-container">
<table id="ot_rate_dt" class="formtable">
    <thead>
      <tr>
      	<th valign="top" ></th> 
      	<th valign="top" >Employee Name</th>
      	<th valign="top" >OT Rate</th>      	
        <th valign="top" ></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
