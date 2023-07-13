<script>
	$(function() {
		  var oTable = $('#employee_list_dt').dataTable({   
		   "aoColumns": [
				   	{ "bSortable": false,sWidth: '2%'},
					{ "bSortable": false,sWidth: '6%'},
					{sWidth: '15%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font',bVisible:false},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
					{sWidth: '20%',sClass:'dt_small_font'},
					{sWidth: '7%',sClass:'dt_small_font'},
			 ],
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			'sAjaxSource': base_url + 'overtime/_load_server_overtime_list_dt',
			"fnDrawCallback": function() {
					$('.i_container #add_request').tipsy({gravity: 's'});
					$('.i_container #delete').tipsy({gravity: 's'});
					$('.i_container #view').tipsy({gravity: 's'});
				}
			}).fnSetFilteringDelay();
	});
	
	function chkUnchk(){
		if($('#check_all_overtime').attr('checked')) { 
			$('#change_status_ck').val('');
			$('.overtime_action_link').show();
			status = true; 
		} else { 
			$('.overtime_action_link').hide();
			status = false;
		}

		$('.ckDt').attr("checked",status);
		$('#h_ckdt_id').val(getConcatCkvalue());
	}
	
	function getConcatCkvalue() {
		var ck_value = '';
		var e		   = document.overtime_form_dt.elements.length;
		var ckarr    = [];  
		var cnt = 0;	
		for(cnt=0;cnt<e;cnt++) {
			if(document.overtime_form_dt.elements[cnt].name=='dtChk[]'){
				if(document.overtime_form_dt.elements[cnt].checked) { ckarr[ckarr.length] = document.overtime_form_dt.elements[cnt].value; }
			}
		}
		for (var i=0; i<ckarr.length; i++) {
		if(i == (ckarr.length - 1)){ ck_value = ck_value + ckarr[i];		
		} else{ ck_value = ck_value + ckarr[i] + ','; }}
		return ck_value;  
	}
</script>

<div class="table-container">
<form id="overtime_form_dt" name="overtime_form_dt">
<textarea id="h_ckdt_id" name="h_ckdt_id" style="display:none;"></textarea>
<table id="employee_list_dt" class="display">
    <thead>
      <tr>
      	<th valign="middle" width="2%"><input type="checkbox" id="check_all_overtime" name="check_all_overtime" onclick="javascript:chkUnchk();" /></th>
      	<th valign="top" width="10%"></th>
        <th valign="top" width="10%">Employee Name</th>
        <th valign="top" width="10%">Position</th>
        <th valign="top" width="10%">Employee Id</th>
        <th valign="top" width="10%">Date of Overtime</th>
        <th valign="top" width="10%">Time In</th>
        <th valign="top" width="10%">Time Out</th>
        <th valign="top" width="10%">Comments</th>
        <th valign="top" width="10%">Status</th>
        
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</form>
</div>
