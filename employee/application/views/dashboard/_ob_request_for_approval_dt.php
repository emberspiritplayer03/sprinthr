<script>

function countCheckedOb()
{       
    var inputs     = document.obWithSelectedAction.elements['dtChk[]'];
    var is_checked = false;
    var cnt        = 0;
    var theForm = document.obWithSelectedAction;
    for (i=0; i<theForm.elements.length; i++) {         
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
            if(is_checked){                              
                cnt++;
            }
    }
    
    return cnt;

}

function chkUnchkOb()
{
	var check_uncheck_ob = document.obWithSelectedAction.elements['check_uncheck_ob'];
    if(check_uncheck_ob.checked == 1) {    
        $('#check_uncheck_ob').attr('title', 'Uncheck All');                                   
        //$("#chkAction").removeAttr('disabled');
        var status = 1; 
    } else { 
        $('#check_uncheck_ob').attr('title', 'Check All');                                 
        //$("#chkAction").attr('disabled',true);
        var status = 0;
    }
    
    var c = 0;
    var theForm = document.obWithSelectedAction;
    for (i=0; i<theForm.elements.length; i++) {         
        if (theForm.elements[i].name=='dtChk[]') {
            theForm.elements[i].checked = status;
            c++;
        }
    }

    if(c > 0 && status == 1) {
        $("#chkActionOb").removeAttr('disabled');
    }else{
        $("#chkActionOb").attr('disabled',true);
    }
}

function enableDisableWithSelectedOb(form){
	var check = countCheckedOb();		
	if(check > 0){
		$("#chkActionOb").removeAttr('disabled');
	}else{
		$("#chkActionOb").attr('disabled',true);
	}
}

	$(function() {	

	    $("#chkActionOb").change(function(){
	        withSelectedAction($(this).val(),"obWithSelectedAction");
	    });

		jq17('.dropdown-toggle_multi').dropdown();	
		  var oTable = $('#ob_dt').dataTable({   
		   "aoColumns": [
					{ "bSortable": false,sWidth: '3%', "bVisible": true},					
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{sWidth: '8%',sClass:'dt_small_font'},
					{sWidth: '10%',sClass:'dt_small_font'},
					{ "bSortable": false,sWidth: '3%'}		
			 ],
			"bStateSave": false,
			'bProcessing':true,
			'bServerSide':true,
			"bAutoWidth": true,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,
			
			'sAjaxSource': base_url + 'dashboard/_load_ob_request_for_approval_dt',
			"fnDrawCallback": function() {
					jq17('.dropdown-toggle').dropdown();

					$(".btn-ot-approve-request").click(function(){
						var r_eid = $(this).attr("id");
						approveRequest(r_eid);
					});

					$(".btn-ot-disapprove-request").click(function(){
						var r_eid = $(this).attr("id");
						disapproveRequest(r_eid);
					});

				}
			});
	});
</script>
<form id="obWithSelectedAction" name="obWithSelectedAction" method="post">
<div style="float:right;margin-left:273px;">
    <select style="width:138px;" disabled="disabled" name="chkAction" id="chkActionOb" >
        <option value="">With Selected:</option> 
        <option value="approve">Approve</option>      
        <option value="disapprove">Disapprove</option>                                          
    </select>
</div>
<div class="table-container">
<!-- <h2>Approved Leave</h2> -->
<table id="ob_dt" class="formtable">
    <thead>
      <tr>
      	<th valign="top" ><input id="check_uncheck_ob" onclick="chkUnchkOb();" type="checkbox"></th>       
        <th valign="top" >Employee ID</th>        
        <th valign="top" >Employee Name</th>
        <th valign="top" >Date Filed</th>
        <th valign="top" >Time Filed</th>   
        <th valign="top" >Date Start</th>   
        <th valign="top" >Date End</th>   
        <th valign="top" >Reason</th>
        <th valign="top" ></th>    
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
</form>
