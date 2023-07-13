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
  var oTable = $('#dtRequests').dataTable( {
	   "aoColumns": [   			   			
			{ "bSortable": false,sWidth: '20%'},							
			{sWidth: '10%',sClass:'dt_small_font'},							
			{sWidth: '10%',sClass:'dt_small_font dt_center'},
			{sWidth: '30%',sClass:'dt_small_font dt_center'},
			{sWidth: '60%',sClass:'dt_small_font dt_center'}													
	 	],
		'bProcessing':true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 3, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});
});
</script>
<br />
<div class="table-container">
<table id="dtRequests" class="display">
<thead>
  <tr>
    <th valign="top">&nbsp;</th>
    <th valign="top">Title</th>   
    <th valign="top">Type</th>
    <th valign="top">Requestor(s)</th>
    <th valign="top">Approver(s)</th>
  </tr>
</thead>
    <?php 
		foreach ($requests as $r){
    		$hash =  Utilities::createHash($r->getId());
			$id   =  Utilities::encrypt($r->getId());
	?>
      <tr>
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">
                	<li>
                    	<a title="Edit" id="tipsy" class="ui-icon ui-icon-pencil g_icon" href="javascript:void(0);" onclick="javascript:editRequest(<?php echo $r->getId(); ?>);"></a>
                    </li>
                    <li>
                    	<a title="Copy Settings" id="tipsy" class="ui-icon ui-icon-copy  g_icon" href="javascript:void(0);" onclick="javascript:copyRequestSettings(<?php echo $r->getId(); ?>);"></a>
                    </li>
                    <li>
                    	<a title="Approvers" id="tipsy" class="ui-icon ui-icon-person g_icon" href="<?php echo url('settings/approvers?hid=' . $id); ?>"></a>
                    </li>
                    <li>
                    	<a title="Send to Archive" id="tipsy" class="ui-icon ui-icon-trash g_icon" href="javascript:void(0);" onclick="javascript:archiveRequestSettings(<?php echo $r->getId(); ?>);"></a>
                    </li>
                </ul>
            </div>
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo $r->getTitle(); ?>       
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo $r->getType(); ?>       
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo str_replace(",","",$r->getDescription()); ?>       
        </td>     
        <td valign="center" align="left" style="color:#333">
        	<?php
				$approvers = G_Settings_Request_Approver_Finder::findAllBySettingsRequestId($r->getId(),"level ASC");
				foreach($approvers as $a){
					if($a->getOverrideLevel() == Settings_Request_Approver::GRANTED){
						$override = ' (<span class="red" style="font-size:11px;"> With Override Level</span>)';
					}else{$override = '';}
					if($a->getType() == Settings_Request_Approver::POSITION_ID){
						$p = G_Job_Finder::findById($a->getPositionEmployeeId());
						if($p){
							echo $a->getLevel() . '. ' . $p->getTitle() . $override .'<br>';
							
						}
					}elseif($a->getType() == Settings_Request_Approver::EMPLOYEE_ID){
						$e = G_Employee_Finder::findById($a->getPositionEmployeeId());
						if($e){
							echo $a->getLevel() . '. ' . $e->getName() . $override .'<br>';
						}	
					}
				}
			?>
        </td>           
      </tr>
    <?php } ?>

</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>