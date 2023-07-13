<script>
	$(function() {		
		jq17('.dropdown-toggle_multi').dropdown();	
		  var oTable = $('#overtime_disapproved_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},					
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{ "bSortable": false,sWidth: '3%'}		
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
			
			'sAjaxSource': base_url + 'overtime/_load_overtime_disapproved_dt',
			"fnDrawCallback": function() {
					jq17('.dropdown-toggle').dropdown();

					$(".btn-view-approver").click(function(){
						var request_id = $(this).attr("id");
						viewOvertimeApprovers(request_id);
					});

				}
			});
	});
</script>
<div class="table-container">
<h2>Disapproved Overtime</h2>
<table id="overtime_disapproved_dt" class="formtable">
    <thead>
      <tr>
      	<th valign="top" ></th>       
        <th valign="top" >Date Filed</th>        
        <th valign="top" >Time Filed</th>
        <th valign="top" >Date of Overtime</th>
        <th valign="top" >OT In</th>   
        <th valign="top" >OT Out</th>   
        <th valign="top" >Reason</th>  
        <th valign="top" >Date Disapproved</th>  
        <th valign="top" >Reason for Disapproval</th>  
        <th valign="top" ></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
