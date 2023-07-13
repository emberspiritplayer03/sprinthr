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
$(function() {
	  jq17('.dropdown-toggle').dropdown();
	  var oTable = $('#request-approvers').dataTable({   
	   "aoColumns": [		   		
				{ "bSortable": false,sWidth: '13%'},									
				{sWidth: '20%',sClass:'dt_small_font'},		
				{sWidth: '70%',sClass:'dt_small_font'}					
		 ],
		'bProcessing':true,
		'bServerSide':true,
		"bAutoWidth": true,
		"bStateSave": true,
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,
		'sAjaxSource': base_url + 'settings/_load_server_company_benefits_list_dt',
		"fnDrawCallback": function() {					
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
    	<!--<li><a onclick="javascript:company_benefits_with_selected_confirmation('archive');" href="javascript:void(0);"><i class="icon-user"></i> Assign to all Employees</a></li>  -->  
	    <li><a onclick="javascript:company_benefits_with_selected_confirmation('archive');" href="javascript:void(0);"><i class="icon-trash"></i> Archive</a></li>        
    </ul>
</div>
<div class="clear"></div>
<br />
<div class="table-container">
<table id="request-approvers" class="display">
    <thead>
      <tr>     
        <th valign="top" width="10%"><input type="checkbox" title="Check All" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>       
        <th valign="top" width="10%">Code</th>
        <th valign="top" width="10%">Name</th>
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
