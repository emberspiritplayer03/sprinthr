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
        <?php if($permission_action == Sprint_Modules::PERMISSION_02) {   ?>
    	    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	 			   			
    			{ "bSortable": false,sWidth: '15%'},				
    		<?php } ?>		
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
    <?php if($permission_action == Sprint_Modules::PERMISSION_02) {   ?>
     	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
        <th valign="top"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
        <?php } ?>
    <?php } ?>
    <th valign="top">Applied To</th>   
    <th valign="top">Title</th>    
    <th valign="top">Status</th>    
    <th valign="top">Remarks</th>
    <th valign="top">Amount</th>
    <!--<th valign="top">Taxable</th>-->
  </tr>
</thead>
    <?php 
		foreach ($deductions as $ea){
    		
	?>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02) {   ?>
          <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
            <td valign="middle">
                <div class="i_container">
                	<ul class="dt_icons">
                    	<li>
                        	<input type="checkbox" name="dtChk[]" onclick="javascript:enableDisableWithSelected();" value="<?php echo Utilities::encrypt($ea->getId()); ?>">
                        </li>                	
                        <li>
                        	<a title="Restore Archived" id="tipsy" class="ui-icon ui-icon-refresh g_icon" href="javascript:void(0);" onclick="javascript:restoreArchivedDeduction('<?php echo Utilities::encrypt($ea->getId()); ?>','<?php echo Utilities::encrypt($pid); ?>');"></a>
                        </li>                                        
                    </ul>
                </div>
            </td>
           <?php } ?> 
        <?php } ?>
        <td valign="center" align="left" style="color:#333">
        	<?php 
                $eArray = Tools::convertStringToArray(",",unserialize($ea->getEmployeeId()));
                $counter = 1;
                $is_all_employee = false;
                foreach($eArray as $key => $value){
                    if($value == 'All Employee'){
                        $is_all_employee = true;
                    }else{
                        $e = G_Employee_Finder::findById($value);
                        if($e){
                            $arr_values[] = $e->getFirstName() ." ". $e->getLastName() ;
                        }
                    }
                }

                $dArray = Tools::convertStringToArray(",",unserialize($ea->getDepartmentSectionId()));
                foreach($dArray as $key => $value){
                    if(!$is_all_employee){
                        $d = G_Company_Structure_Finder::findById($value);
                        if($d){
                            $arr_values[] = $d->getTitle();
                        }
                    }
                }

                $esArray = Tools::convertStringToArray(",",unserialize($ea->getEmploymentStatusId()));
                foreach($esArray as $key => $value){
                    if(!$is_all_employee){
                        $es = G_Settings_Employment_Status_Finder::findById($value);
                        if($es){
                            $arr_values[] = $es->getStatus();
                        }
                    }
                }

                if($is_all_employee) {
                    echo 'All Employees';
                }else{
                    echo implode(", <br>",$arr_values);
                }
                $arr_values = array();
            ?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo $ea->getTitle(); ?>       
        </td> 
        <td valign="center" align="left" style="color:#333">
        	<?php echo $ea->getStatus(); ?>       
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