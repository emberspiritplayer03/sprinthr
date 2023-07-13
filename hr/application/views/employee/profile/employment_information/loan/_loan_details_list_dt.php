<script>
$(document).ready(function() {
	$('#loan_details_list').dataTable( {
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bPaginate": true,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": false,
		"bInfo": false,		
		"bScrollCollapse": false
	});	
} );
</script>
<div class="table-container" id="table-container-widgets">
<table id="loan_details_list" class="display">
<thead>
  <tr>
  	<th valign="top"><!--<input title="Check All" type="checkbox" id="check_uncheck" name="check_uncheck" onclick="chkUnchk();" />--></th> 
    <th valign="top">Date of Payment</th>       
    <th valign="top">Amount Due</th>    
    <th valign="top">Amount Paid</th>
    <th valign="top">Remarks</th>
  </tr>
</thead>
  <?php foreach($details as $d){ ?>
  <tr>
  	<td style="width:5%;">
    <div class="i_container">
    	<ul class="dt_icons">
        	<!--<li><input type="checkbox" name="dtChk[]" onclick="javascript:enableDisableWithSelected();" value="<?php //echo $d->getId(); ?>">
            </li>-->
            <li><a title="Edit" id="edit" class="ui-icon ui-icon-pencil g_icon" href="javascript:void(0);" onclick="javascript:editLoanPaymentForm('<?php echo Utilities::encrypt($d->getId()); ?>');"></a>
            </li>
            <!--<li><a title="Delete" id="delete" class="ui-icon ui-icon-trash g_icon" href="javascript:void(0);" onclick="javascript:deleteLoanPayment('<?php //echo Utilities::encrypt($d->getId()); ?>')">
            </a>
            </li>-->
         </ul>
    </div>
    </td>
    <td width="15%" valign="middle"><b><?php echo date("F d, o",strtotime($d->getDateOfPayment())); ?></b></td>            
    <td width="8%" valign="middle" align="right"><?php echo number_format($d->getAmount(),2,".",","); ?></td>
    <td width="8%" valign="middle" align="right"><?php echo number_format($d->getAmountPaid(),2,".",","); ?></td>
    <td width="15%" valign="middle"><?php echo $d->getRemarks(); ?></td>
  </tr>
  <?php } ?>

</table>
</div>
<br />
<script>
$('.dt_icons #edit').tipsy({gravity: 's'});
$('.dt_icons #delete').tipsy({gravity: 's'});
</script>
