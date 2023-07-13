<script>
$(document).ready(function() {
	$('#dtRequestApprovers').dataTable( {
		//"sScrollY": 200,
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": true,
		"bSort": true,
		"bInfo": false,		
		"bScrollCollapse": false
	});	
} );
</script>
<div class="table-container" id="table-container-widgets">
<table id="dtRequestApprovers" class="display">
<thead>
  <tr>
    <th valign="top">&nbsp;</th>   
    <th valign="top">&nbsp;</th>    
    <th valign="top">&nbsp;</th>    
  </tr>
</thead>
    <?php foreach ($approvers as $a){ ?>
      <tr>
        <td width="8%" valign="middle">
        	<div class="i_container">
            	<ul class="dt_icons">
            		<li>
                    	<a title="Delete" id="delete" class="ui-icon ui-icon-trash g_icon" href="javascript:void(0);" onclick="javascript:deleteRequestApprovers('<?php echo Utilities::encrypt($a->getId()); ?>');"></a>  
                    </li>
                    <li>
                    	<a title="Assign Override Level" id="override" class="ui-icon ui-icon-check g_icon" href="javascript:void(0);" onclick="javascript:assignOverrideLevel('<?php echo Utilities::encrypt($a->getId()); ?>');"></a>  
                    </li>
	            </ul>
            </div>           
        </td>            
        <td width="41%" valign="center" align="left">
        	<?php 
				if($a->getType() == Settings_Request_Approver::POSITION_ID){
					if($a->getPositionEmployeeId() == Settings_Request::APPLY_TO_ALL){
						echo 'All Positions';
					}else{
						$p = G_Job_Finder::findById($a->getPositionEmployeeId());
						if($p){
							echo $p->getTitle();
						}
					}
				}else{
					if($a->getPositionEmployeeId() == Settings_Request::APPLY_TO_ALL){
						echo 'All Employees';
					}else{
						$e = G_Employee_Finder::findById($a->getPositionEmployeeId());
						if($e){
							echo $e->getFirstname() . " " . $e->getLastname();
						}
					}
				}
			?> 
        </td>      
         <td width="11%" valign="center" align="center">
         	<?php if($a->getOverrideLevel() == Settings_Request_Approver::GRANTED){ ?>
            	<span class="red" style="font-size:11px;">With Override Level</span>
            <?php } ?>
        </td>                  
      </tr>
    <?php } ?>

</table>
</div>
<br />
<script>
$('.dt_icons #delete').tipsy({gravity: 's'});
$('.dt_icons #override').tipsy({gravity: 's'});
</script>