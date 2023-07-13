<?php if($_POST['hide_show'] == 1){ ?>
<div style="position:relative;left:450px;top:14px;"><a class="btn btn-small" id="close" title="Close" href="javascript:void(0);" onclick="javascript:hide_loan_payment_breakdown();"><i class="icon-remove"></i></a></div>
<?php } ?>
<table class="table table-bordered">
    <tr class="success">
    	<td></td>
        <td><b>Date of payment</b></td>
        <td><b>Amount Paid</b></td>
        <td><b>Reference Number</b></td>
        <td><b>Remarks</b></td>
        
    </tr>
        
	<?php foreach($breakdown as $b){ ?>
    	<tr class="info">
        	<td><a id="delete_payment" title="Delete" class="btn btn-small" onclick="javascript:deleteLoanPaymentBreakdown('<?php echo Utilities::encrypt($b->getId()); ?>',<?php echo $_POST['hide_show']; ?>);" href="javascript:void(0);"><i class="icon-trash"></i></a></td>
        	<td><?php echo $b->getDatePaid(); ?></td>
            <td><?php echo number_format($b->getAmountPaid(),2,".",","); ?></td>
            <td><?php echo $b->getReferenceNumber(); ?></td>
            <td><?php echo $b->getRemarks(); ?></td>
        </tr>
    <?php } ?>
</table>
<script>
$('#close').tipsy({gravity: 's'});
$('.table #delete_payment').tipsy({gravity: 's'});
</script>