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
  var oTable = $('#dtEarnings').dataTable( {
	   "aoColumns": [  
        <?php if($permission_action == Sprint_Modules::PERMISSION_02)   {   ?> 
    	    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	 			   			
    			{ "bSortable": false,sWidth: '15%'},				
    		<?php } ?>
        <?php } ?>			
			{sWidth: '30%',sClass:'dt_small_font'},							
			{sWidth: '30%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'},
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
<table id="dtEarnings" class="display">
<thead>
  <tr>
    <?php if($permission_action == Sprint_Modules::PERMISSION_02)   {   ?> 
     	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
        <th valign="top"><input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /></th>
        <?php } ?>
    <?php } ?>
    <th valign="top">Applied To</th>   
    <th valign="top">Title</th>    
    <th valign="top">Status</th>        
    <th valign="top">Amount</th>
    <th valign="top">Taxable</th>   
  </tr>
</thead>
    <?php 
		foreach ($earnings as $ea){
    		
	?>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02)   {   ?> 
          <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
            <td valign="middle">
                <div class="i_container">
                	<ul class="dt_icons">
                    	<li>
                        	<input type="checkbox" name="dtChk[]" onclick="javascript:enableDisableWithSelected();" value="<?php echo Utilities::encrypt($ea->getId()); ?>">
                        </li>                	
                        <li>
                        	<a title="Restore Archived" id="tipsy" class="ui-icon ui-icon-refresh g_icon" href="javascript:void(0);" onclick="javascript:restoreArchivedEarning('<?php echo Utilities::encrypt($ea->getId()); ?>','<?php echo Utilities::encrypt($pid); ?>');"></a>
                        </li>                                        
                    </ul>
                </div>
            </td>
           <?php } ?> 
       <?php } ?> 
        <td valign="center" align="left" style="color:#333"><?php echo $ea->getObjectDescription(); ?></td>            
        <td valign="center" align="left" style="color:#333"><?php echo $ea->getTitle(); ?></td> 
        <td valign="center" align="left" style="color:#333"><?php echo $ea->getStatus(); ?></td>          
        <td valign="center" align="left" style="color:#333"><?php echo $ea->getDescription(); ?></td>
        <td valign="center" align="left" style="color:#333"><?php echo $ea->getIsTaxable(); ?></td>                     
      </tr>
    <?php } ?>   
</table>
</div>
<script>
    $('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>