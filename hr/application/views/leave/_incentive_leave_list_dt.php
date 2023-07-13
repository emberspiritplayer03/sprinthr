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
  var oTable = $('#dtPerfectAttendance').dataTable( {
	   "aoColumns": [   			   		
            //{sWidth: '2%',sClass:'dt_small_font'},                         
            {sWidth: '10%',sClass:'dt_small_font'},                         
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '30%',sClass:'dt_small_font dt_center'},
            {sWidth: '30%',sClass:'dt_small_font dt_center'}
            //{sWidth: '30%',sClass:'dt_small_font dt_center'}                 
	 	],
		'bProcessing':true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 2, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});
});
</script>
<div class="table-container">
<table id="dtPerfectAttendance" class="display">
<thead>
  <tr>
  	<!-- <th valign="top"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th> -->
    <th valign="top">ID</th>   
    <th valign="top">Firstname</th>   
    <th valign="top">Lastname</th>           
    <th valign="top">Position</th>   
    <th valign="top">Department</th>   
    <th valign="top"></th>   
  </tr>
</thead>
    <?php foreach( $attendance as $a ){ ?>
      <tr>                          
        <td valign="center" align="left" style="color:#333"><?php echo $a['employee_code']; ?></td>  
        <td valign="center" align="left" style="color:#333"><?php echo $a['firstname']; ?></td>  
        <td valign="center" align="left" style="color:#333"><?php echo $a['lastname']; ?></td>        
        <td valign="center" align="left" style="color:#333"><?php echo $a['position_name']; ?></td>                   
        <td valign="center" align="left" style="color:#333"><?php echo $a['department_name']; ?></td>                                              
      </tr>
    <?php } ?>    
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>