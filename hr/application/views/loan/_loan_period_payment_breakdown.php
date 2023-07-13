<div class="div_table_border">
<?php if($_POST['hide_show'] == 1){ ?>
<a class="btn btn-mini float-right" style="position:relative; top:-22px;" id="close" title="Close" href="javascript:void(0);" onclick="javascript:hide_loan_payment_breakdown();"><i class="icon-remove"></i> Close</a>
<?php } ?>
<table class="formtable">
	<thead>
        <tr>
            <th></th>
            <th>Date of payment</th>
            <th>Amount Paid</th>
            <th>Reference Number</th>
            <th>Remarks</th>        
        </tr>
    </thead>
    <tbody>
	<?php foreach($breakdown as $b){ ?>
    	<tr>
        	<td><a id="delete_payment" title="Delete" class="btn btn-small" onclick="javascript:deleteLoanPaymentBreakdown('<?php echo Utilities::encrypt($b->getId()); ?>',<?php echo $_POST['hide_show']; ?>);" href="javascript:void(0);"><i class="icon-trash"></i></a></td>
        	<td><?php echo $b->getDatePaid(); ?></td>
            <td><?php echo number_format($b->getAmountPaid(),2,".",","); ?></td>
            <td><?php echo $b->getReferenceNumber(); ?></td>
            <td><?php echo $b->getRemarks(); ?></td>
        </tr>
    <?php } ?>
	<?php if(!$breakdown) { ?>
    	<tr>
        	<td colspan="5">No record(s) found!</td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
<script>
$('#close').tipsy({gravity: 's'});
$('.table #delete_payment').tipsy({gravity: 's'});
</script>