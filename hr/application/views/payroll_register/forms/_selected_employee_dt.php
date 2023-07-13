<style>
#msg-cont {
   background-color: #3A87AD;
    border-radius: 10px;
    bottom: -53px;
    color: #fff;
    font-size: 14px;
    left: 368px;
    padding: 10px;
    position: relative;
    top: 100px;
    width: 20%;
    z-index: 9999;
}
</style>
<script>
	$(function() {	
		$(".btn-remove").click(function(){
			var employee_id = $(this).attr("id");
			var selected_employee_id = $("#selected_employee_id").val();
			$("#"+employee_id).fadeOut(1000);
			$("#loading-msg").html("<div id='msg-cont'>"+loading_image + " Removing Employee..</div>");
			$.post(base_url + 'payroll_register/_remove_from_selected_employee',{employee_id:employee_id,selected_employee_id:selected_employee_id},function(o) {
                $("#selected_employee_id").val(o.new_selected_employee_id);
                loadSelectedEmployees(o.new_selected_employee_id);
            },"json"); 
		});

		$(".hover-link").hover(
	        function(){
	            $(this).find(".overlay").show();
	            $(this).find(".overlay").css('display','inline-block');
	        },
	        function(){
	            $(this).find(".overlay").hide();
	        }
	    );	
		//jq17('.dropdown-toggle_multi').dropdown();
		  var oTable = $('#ob_approved_dt').dataTable({   
		   "aoColumns": [			
					{sWidth: '90%',sClass:'dt_small_font'},
					{ "bSortable": false,sWidth: '10%'}		
			 ],
			"bStateSave": false,
			'bProcessing':true,
			'bServerSide':false,
			"bAutoWidth": false,
			"bInfo":false,
			"bJQueryUI": true,
			"aaSorting": [[ 1, "asc" ]],
			"sPaginationType": "full_numbers",
			"bPaginate": true,

			"fnDrawCallback": function() {
					//jq17('.dropdown-toggle').dropdown();

				}
			});
	});
</script>
<div style="display:absolute;" class="table-container">
<h4 style="text-align:center;"><?php echo ($employees ? count($employees) : 0);?> SELECTED EMPLOYEES to Generate</h4>
<hr/>
<div style="height:0px !important;" id='loading-msg'></div>
<table id="ob_approved_dt" class="formtable">
    <thead>
      <tr>     
        <th valign="top" >Employee Name</th>        
        <th valign="top" >Action</th>    
      </tr>
    </thead>
    <tbody>
    <?php foreach($employees as $key => $value) { ?>
    	<tr id="<?php echo Utilities::encrypt($value['id']);?>" class="hover-link">
    		<td><?php echo $value['fullname'];?></td>
    		<td>
    			<div class="overlay" style="height: 10px; display: none;">
                	<a href="javascript:void(0);" id="<?php echo Utilities::encrypt($value['id']);?>" style="padding:0px 7px 0px 5px" class="btn btn-remove" ><i class="icon-trash"> </i> Remove</a>
                </div>
    		</td>
    	</tr>
    <?php } ?>
    </tbody>	
</table>
</div>

