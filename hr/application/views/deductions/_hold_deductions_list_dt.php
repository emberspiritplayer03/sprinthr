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
        <?php if($permission_action == Sprint_Modules::PERMISSION_02)   {   ?>   	
    	    <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>			   			
    			{ "bSortable": false,sWidth: '5%'},				
    		<?php } ?>	
        <?php } ?>		
			{sWidth: '30%',sClass:'dt_small_font'},							
			{sWidth: '30%',sClass:'dt_small_font dt_center'},
			{sWidth: '50%',sClass:'dt_small_font dt_center'},
			//{sWidth: '50%',sClass:'dt_small_font dt_center'},
			//{sWidth: '50%',sClass:'dt_small_font dt_center'}													
	 	],
		'bProcessing':true, 		
		"bAutoWidth": true,
		"bInfo":true,
		"bJQueryUI": true,
		"aaSorting": [[ 3, "asc" ]],	
		"sPaginationType": "full_numbers",
		"bPaginate": true
	});

  $(".btn-move-deduction").click(function(){
    var id = $(this).attr("id");
    var from = $("#from").val();
    var to = $("#to").val();
    var payroll_period_id = $("#payroll_period_id").val();
    moveDeduction(id,from,to,payroll_period_id);
  })
});

function moveDeduction(id,from,to,pid) {

    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var title = 'Move Deduction';
    var width = 450;
    var height = 'auto';
        
    $.post(base_url + 'deductions/_show_move_deduction_form',{id:id,from:from,to:to,pid:pid},function(data) {
        closeDialog(dialog_id);
        $(dialog_id).html(data);
        dialogGeneric(dialog_id, {
            title: title,
            resizable: false,
            width: width,
            height: height,
            modal: true
        });
            
    }); 
}
</script>

<input type="hidden" id="from" value="<?php echo $from;?>">
<input type="hidden" id="to" value="<?php echo $to;?>">
<input type="hidden" id="payroll_period_id" value="<?php echo Utilities::encrypt($pid);?>">

<div class="table-container">
<table id="dtDeductions" class="display">
<thead>
  <tr>
    <?php if($permission_action == Sprint_Modules::PERMISSION_02)   {   ?>
     	<?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
        <th valign="top"><!-- <input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" /> --></th>
        <?php } ?>
    <?php } ?>
    <th valign="top">Employee Code</th> 
    <th valign="top">Employee Name</th>   
    <th valign="top">Deduction</th>    
    <th valign="top">Amount</th>
    <!--<th valign="top">Taxable</th>-->   
  </tr>
</thead>
    <?php 
		foreach ($hold_deductions as $ea){
    		$e = G_Employee_Finder::findById($ea->getEmployeeId());
	?>
      <tr>
      <?php if($permission_action == Sprint_Modules::PERMISSION_02) {   ?>
           <?php if($is_period_lock == G_Cutoff_Period::NO){ ?>	
            <td valign="middle">
                <div class="i_container">
                	<ul class="dt_icons">
                    	<!-- <li>
                        	<input type="checkbox" name="dtChk[]" onclick="javascript:enableDisableWithSelected();" value="<?php echo Utilities::encrypt($ea->getId()); ?>">
                        </li> -->                	                                      
                        <li>
                        	<a title="Move Deduction" id="<?php echo Utilities::encrypt($ea->getId());?>" class="btn-move-deduction" href="javascript:void(0);" ><i class="icon-share-alt"></i> </a>
                        </li>
                    </ul>
                </div>
            </td> 
           <?php } ?>
      <?php } ?>
        <td valign="center" align="left" style="color:#333">
        	<?php 		
				
				if($e){
					echo  $e->getEmployeeCode();
				}
					
			?>
        </td> 
        <td valign="center" align="left" style="color:#333">
            <?php       
                
                if($e){
                    echo  $e->getLastName() . ", " . $e->getFirstName() ;
                }
                    
            ?>
        </td>            
        <td valign="center" align="left" style="color:#333">
        	<?php echo ucfirst(str_replace("_"," ",$ea->getVariableName())); ?>       
        </td>  
        <td valign="center" align="left" style="color:#333">
        	<?php echo number_format($ea->getAmount(),2,".",","); ?>
        </td>
      </tr>
    <?php } ?>   
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>