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
  var oTable = $('#dtDeductions').dataTable( {
	   "aoColumns": [   			   		
	   <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
			{ "bSortable": false,sWidth: '28%'},		
	   <?php } ?>					
			{sWidth: '30%',sClass:'dt_small_font'},							
			{sWidth: '30%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'},
			//{sWidth: '50%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'}														
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
<div class="table-container">
<table id="dtDeductions" class="display">
<thead>
  <tr>
  	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
    	<th valign="top"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
    <?php } ?>
    <th valign="top">Applied To</th>   
    <th valign="top">Title</th>    
    <th valign="top">Remarks</th>
    <th valign="top">Amount</th>   
    <!--<th valign="top">Taxable</th>-->   
  </tr>
</thead>
    <?php 
		foreach ($deductions as $ea){
    		
	?>
      <tr>
      <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
        <td valign="middle">
            <div class="i_container">
            	<ul class="dt_icons">
                	<li>
                    	<input type="checkbox" name="dtChk[]" onclick="javascript:enableDisableWithSelected();" value="<?php echo Utilities::encrypt($ea->getId()); ?>">
                    </li>
                	<li>
                    	<a title="Edit" id="tipsy" class="ui-icon ui-icon-pencil g_icon" href="javascript:void(0);" onclick="javascript:editDeduction('<?php echo Utilities::encrypt($ea->getId()); ?>');"></a>
                    </li>
                    <li>
                    	<a title="Approve (Include to payroll)" id="tipsy" class="ui-icon ui-icon-check  g_icon" href="javascript:void(0);" onclick="javascript:approveDeduction('<?php echo Utilities::encrypt($ea->getId()); ?>','<?php echo Utilities::encrypt($pid); ?>');"></a>
                    </li>                    
                    <li>
                    	<a title="Send to Archive" id="tipsy" class="ui-icon ui-icon-trash g_icon" href="javascript:void(0);" onclick="javascript:archivePendingDeduction('<?php echo Utilities::encrypt($ea->getId()); ?>','<?php echo Utilities::encrypt($pid); ?>');"></a>
                    </li>
                </ul>
            </div>
         </td> 
        <?php } ?>
        <td valign="center" align="left" style="color:#333">
        	<?php 
				$eArray = Tools::convertStringToArray(",",unserialize($ea->getEmployeeId()));
				$counter = 1;
				foreach($eArray as $key => $value){
					if($value == 'All Employee'){
						echo "All Employee";
					}else{
						$e = G_Employee_Finder::findById($value);
						if($e){
							echo $counter . ". " . $e->getLastName() . ", " . $e->getFirstName() . '<br>';
							$counter++;
						}
					}
				}
			?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo $ea->getTitle(); ?>       
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo $ea->getRemarks(); ?>
        </td>
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($ea->getAmount(),2,".",","); ?>        	
        </td>   
        <!--<td valign="center" align="left" style="color:#333">
        	<?php //echo $ea->getTaxable(); ?>       
        </td>-->
      </tr>
    <?php } ?>    
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>