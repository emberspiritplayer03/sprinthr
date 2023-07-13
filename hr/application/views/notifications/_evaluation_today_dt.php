<script>
	$(function() {		
		 var oTable = $('#employee_eval_datatable').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": false},					
					{sWidth: '5%',sClass:'dt_small_font'},
					{sWidth: '5%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					
					{ "bSortable": false,sWidth: '10%'}		
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			//"bStateSave": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 3, "asc" ]],
			"sPaginationType": "two_button",
		"bPaginate": true,
			"bFilter": false,
			
			'sAjaxSource': base_url + 'notifications/_ajax_load_employee_eval_dt',
			"fnDrawCallback": function() {
					
					

				}
			});
	});
</script>
<div class="table-container">
 <table id="employee_eval_datatable" class="display">
    <thead>
      <tr>
        <th valign="top" ></th>       
        <th valign="top" >Branch</th>        
        <th valign="top" >Department</th>   
        <th valign="top" >Section</th>
        <th valign="top" >Employee ID</th>    
        <th valign="top" >Employee Name</th> 
        <th valign="top" >Position</th>
         <th valign="top" >Evaluation Date</th> 
          
        <th valign="top"></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>  
</table>
</div>