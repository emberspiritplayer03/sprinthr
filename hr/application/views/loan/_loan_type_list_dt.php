<script>
$(function() {
	  var oTable = $('#loan_type_list').dataTable({   
	   "aoColumns": [
	   		<?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>
				{"bSortable": false,sWidth:'8%'},	
			<?php } ?>
				{sWidth:'55%',sClass:'dt_small_font'},		
				{"bVisible":false,sWidth:'20%',sClass:'dt_small_font'}									
		 ],
		"bProcessing":true,
		"bServerSide":true,
		"bAutoWidth": true,		
		"bInfo":false,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],
		"sPaginationType": "full_numbers",
		"bPaginate": true,			
		'sAjaxSource': base_url + 'loan/_load_server_loan_type_list_dt',	
		"fnRowCallback": function(nRow, aData, iDisplayIndex) {				
            if (aData[2] == '<span style="color:#21729E">1</span>' || aData[2] == '<span style="color:#21729E">2</span>' || aData[2] == '<span style="color:#21729E">3</span>' || aData[2] == '<span style="color:#21729E">4</span>' || aData[2] == '<span style="color:#21729E">5</span>' || aData[2] == '<span style="color:#21729E">6</span>' || aData[2] == '<span style="color:#21729E">7</span>' || aData[2] == '<span style="color:#21729E">8</span>' || aData[2] == '<span style="color:#21729E">9</span>' || aData[2] == '<span style="color:#21729E">10</span>') {
               $('div.i_container', nRow).remove();   	                 	           
            }
            	
            return nRow;
        },	
		"fnDrawCallback": function() {
				$('input#check_uncheck').tipsy({gravity: 's', live: true});	
				$('.i_container #edit').tipsy({gravity: 's'});
				$('.i_container #delete').tipsy({gravity: 's'});
				$('.i_container #view').tipsy({gravity: 's'});
			}
		}).fnSetFilteringDelay();
});
</script>
<div class="table-container">
<table id="loan_type_list" class="formtable">
    <thead>
      <tr>      
      	<?php if($permission_action == Sprint_Modules::PERMISSION_02)	{	?>	
      		<th valign="middle" width="2%"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>                 	
        <?php } ?>
        <th valign="top" width="10%">Loan Type</th>       
        <th valign="top" width="10%"></th>         
      </tr>
    </thead>
    <tbody>   
    </tbody>	
</table>
</div>
<script type="text/javascript">

function DropDown(el) {
	this.dd = el;
	this.initEvents();
}
DropDown.prototype = {
	initEvents : function() {
		var obj = this;

		obj.dd.on('click', function(event){
			$(this).toggleClass('active');
			event.stopPropagation();
		});	
	}
}

$(function() {

	var dd = new DropDown( $('#dd') );

	$(document).click(function() {
		// all dropdowns
		$('.wrapper-dropdown-5').removeClass('active');
	});

});

</script>
