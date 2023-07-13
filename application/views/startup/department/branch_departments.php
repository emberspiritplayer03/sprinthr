<script>
$(document).ready(function(){ 
  var oTable = $("#dtDepartments").dataTable( {
	   "aoColumns": [   			   			  				
			{"bSortable": false,sWidth: "5%"},								
			{sWidth: "95%",sClass:"dt_small_font dt_center"}													
	 	],
		"bProcessing":true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 1, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});
});
</script>
<div align="left">
	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:addNewDepartment('<?php echo $eid; ?>',2);"><strong>+</strong><b>Add New Department</b></a>
</div>
<br />
<div>
	<?php include_once('includes/bread_crumbs.php'); ?>
</div>
<div class="table-container">
<table id="dtDepartments" class="display">
<thead>
  <tr>
	<th valign="top"></th>
    <th valign="top">Department Name</th>       
  </tr>
</thead>	
    <?php foreach ($departments as $d){?>
      <tr>
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">                	
                	<li>
                    	<a title="Edit" id="tipsy" class="btn btn-mini" href="javascript:editDepartment('<?php echo Utilities::encrypt($d->getId()); ?>');">
                        	<i class="icon-pencil"></i>
                        </a>
                    </li>                    
                </ul>
            </div>
         </td>         
        <td valign="center" align="left" style="color:#333"><?php echo $d->getTitle();?></td>                                           
      </tr>
    <?php } ?>    
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
$('.display #btn_dept').tipsy({gravity: 's'});
$('.display #btn_groups').tipsy({gravity: 's'});
$('.display #btn_teams').tipsy({gravity: 's'});
</script>