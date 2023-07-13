<script>
function checkAction()
{		
	var chkAction = $("#chkAction").val();	
	if(chkAction == ''){
		return false;
	}else{
		return true;
	}	
}

$(document).ready(function(){ 
  var oTable = $('#dtRequests').dataTable({   
   "aoColumns": [   			   			
			{ "bSortable": false,sWidth: '25%'},							
			{sWidth: '20%',sClass:'dt_small_font'},							
			{sWidth: '20%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'}													
	 ],
    'bProcessing':true, 
    'bServerSide':true, 
	"bAutoWidth": true,
	"bInfo":false,
	"bJQueryUI": true,
	"aaSorting": [[ 3, "asc" ]],	
	"sPaginationType": "full_numbers",
	"bPaginate": true,	
	'sAjaxSource': base_url + 'settings/_request_dt',	
	"fnDrawCallback": function() {		
            $('.i_container #edit').tipsy({gravity: 's'});					
			$('#otable_filter_all').tipsy({gravity: 'e'});						
			$('.i_container #delete').tipsy({gravity: 's'});					
        }
	}).fnSetFilteringDelay(); 
});
</script>
<br />
<div class="table-container">
<table id="dtRequests" class="display">
<thead>
  <tr>      
    <th valign="top" width="10%">&nbsp;</th>       
    <th valign="top" width="10%">Title</th>    
    <th valign="top" width="10%">Type</th>      
    <th valign="top" width="10%">Applied To</th>                  
  </tr>
</thead>
<tbody>   
</tbody>	
</table>
</div>