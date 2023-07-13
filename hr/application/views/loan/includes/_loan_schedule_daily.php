<script>
$(document).ready(function() {
	$('#dtLoanDailySchedule').dataTable( {
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
<h2>Loan Schedule</h2>
<table id="dtLoanDailySchedule" class="formtable">
<thead>
  <tr>
    <th valign="top">Date</th>       
    <th valign="top">Amount  Paid(with interest)</th>    
    <th valign="top">Balance</th>
  </tr>
</thead>
	<?php 				
		for($x=1;$x<=$n_installment;$x++){	
		$start_date =  strtotime(date("Y-m-d", strtotime($start_date)) . "+1 day");
		$day 		= date("l",$start_date);			
		if($day == 'Saturday'){
			$start_date = date("F d, o",$start_date);		
			$start_date =  strtotime(date("Y-m-d", strtotime($start_date)) . "+2 day");
		}elseif($day == 'Sunday'){
			$start_date = date("F d, o",$start_date);		
			$start_date =  strtotime(date("Y-m-d", strtotime($start_date)) . "+1 day");
		}	
		
		$start_date = date("F d, o",$start_date);		
			
				//Compute payment
					$payment = G_Employee_Loan_Helper::computePayment($gel);
				//
				//Compute Balance
					$balance_with_interest = $balance_with_interest - $payment;
				//					
	?>
    	<tr>
        	<td width="8%" valign="middle"><b><?php echo $start_date; ?></b></td>            
            <td width="8%" valign="middle"><?php echo number_format($payment,2,".",","); ?></td>
            <td width="8%" valign="middle"><?php echo number_format($balance_with_interest,2,".",","); ?></td>
        </tr>
	<?php
			}		
	?>
</table>
</div>
<br />
