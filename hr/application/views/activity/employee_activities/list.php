<style>
.textboxlist{display:inline-block;width:76%;margin-right: 5px;}
.textboxlist-bits{height:28px;width: 100%;}
</style>

<script>
	$(function() {
		var eids = getUrlParameter('eids');
		var group_id = getUrlParameter('group_id');
		
		var oTable = $('#employee_activities_dt').dataTable({   
			"aoColumns": [
			<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
				{ "bSortable": false,sWidth: '11%'},					
			<?php } ?>

					{sWidth: '25%',sClass:'dt_small_font'},					
					{sWidth: '10%',sClass:'dt_small_font'},	
					{sWidth: '10%',sClass:'dt_small_font'},	
					{sWidth: '10%',sClass:'dt_small_font'},	
					{sWidth: '10%',sClass:'dt_small_font'},	
					{sWidth: '10%',sClass:'dt_small_font'},				
					{sWidth: '10%',sClass:'dt_small_font'}					
				],
			"bProcessing":true,
			"bServerSide":true,
			"bAutoWidth": true,
			"bStateSave": false,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,			
			'sAjaxSource': base_url + 'activity/_load_server_employee_activities_list_dt?eids=' + eids + '&group_id=' + group_id,
			"fnDrawCallback": function() {
					$('.i_container #edit').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
				}
			})//.fnSetFilteringDelay();

		var t = new $.TextboxList('#employee_id', {
				unique: true,
				plugins: {
				autocomplete: {
					minLength: 2,       
					onlyFromValues: true,
					queryRemote: true,
					remote: {url: base_url + 'autocomplete/ajax_get_active_employees'}
				}
			}});

		$(".btn-search-employee-activities").click(function(){
			var eids = $("#employee_id").val();   

			if( eids != "" ){  
				var current_url = location.href;
				var new_url     = removeParam("eids",current_url);
				window.location = new_url + "&eids=" + eids;
			}else{
				//dialogOkBox("Please enter employee name(s) to find",{});
				//Will display all records
				var current_url = location.href;
				var new_url     = removeParam("eids",current_url);
				window.location = new_url;
			}
		});
		
	});

	function removeParam(key, sourceURL) {
		var rtn = sourceURL.split("?")[0],
			param,
			params_arr = [],
			queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
		if (queryString !== "") {
			params_arr = queryString.split("&");
			for (var i = params_arr.length - 1; i >= 0; i -= 1) {
				param = params_arr[i].split("=")[0];
				if (param === key) {
					params_arr.splice(i, 1);
				}
			}
			rtn = rtn + "?" + params_arr.join("&");
		}
		return rtn;
	}
</script>

<div class="table-container">

<table id="employee_activities_dt" class="display">
    <thead>
      <tr>
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
			<th valign="middle" width="2%"></th>     
	    <?php } ?>
        <th valign="top" width="10%">Name</th>
        
        <th valign="top" width="10%">Project Site</th>       
        
        <th valign="top" width="10%">Designation</th>       
        <th valign="top" width="10%">Activity</th>       
        <th valign="top" width="10%">Date</th>       
        <th valign="top" width="10%">Time In</th>       
        <th valign="top" width="10%">Time Out</th>       
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
