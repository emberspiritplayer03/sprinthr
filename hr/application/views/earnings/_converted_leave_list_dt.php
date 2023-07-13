<script>
$(document).ready(function(){ 
  var oTable = $('#dtLeaveConverted').dataTable( {
	   "aoColumns": [   			   		                        
            {sWidth: '10%',sClass:'dt_small_font dt_center'},
            {sWidth: '25%',sClass:'dt_small_font dt_center'},
            {sWidth: '15%',sClass:'dt_small_font dt_center'},
            {sWidth: '15%',sClass:'dt_small_font dt_center'},
            {sWidth: '10%',sClass:'dt_small_font dt_center'},                       
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
<table id="dtLeaveConverted" class="display">
<thead>
  <tr>  	
    <th valign="top">ID</th>   
    <th valign="top">Name</th>       
    <th valign="top">General</th>  
    <th valign="top">Incentive</th>  
    <th valign="top">Total Converted</th>   
    <th valign="top">Amount</th>   
  </tr>
</thead>
    <!-- 
    <?php foreach( $leave_data as $key => $d ){ ?>
        <tr>                  
            <?php 
                //$lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
                //$firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
            ?>
            <td valign="center" align="left" style="color:#333"><?php //echo $d['employee_code']; ?></td>              
            <td valign="center" align="left" style="color:#333"><?php //echo mb_convert_case($firstname . ' ' . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>              
            <td valign="center" align="left" style="color:#333"><?php //echo $d['leave_type']; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php //echo $d['total_leave_converted']; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php //echo $d['amount']; ?></td>  
        </tr>
    <?php } ?>
    -->   

    <?php foreach( $leave_data_group as $key => $d ){ ?>
        <tr>                  
            <?php 
                $lastname  = strtr(utf8_decode($d['lastname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
                $firstname = strtr(utf8_decode($d['firstname']), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');               
            ?>
            <td valign="center" align="left" style="color:#333"><?php echo $key; ?></td>              
            <td valign="center" align="left" style="color:#333"><?php echo mb_convert_case($firstname . ' ' . $lastname,  MB_CASE_TITLE, "UTF-8"); ?></td>              
            <td valign="center" align="left" style="color:#333"><?php echo isset($d['general']) ? $d['general'] : 0; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo isset($d['incentive']) ? $d['incentive'] : 0; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $d['total_leave_converted']; ?></td>  
            <td valign="center" align="left" style="color:#333"><?php echo $d['amount']; ?></td>  
        </tr>
    <?php } ?>   
</table>
</div>