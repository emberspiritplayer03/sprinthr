  <?php foreach($new_deductions as $deduction){ ?>
  <?php 
  	$total_hrs  = number_format($deduction['total_hours'],2);
  	//$total_days = number_format($deduction['total_days'],0);
    $total_days = $deduction['total_days'];

  	if( $total_hrs > 0 ){
  		$to_string = "<b>({$total_hrs} hrs)</b>";
  	}elseif( $total_days > 0 ){
  		$to_string = "<b>({$total_days} days)</b>";
  	}else{
  		$to_string = "";
  	}
  ?>
  <tr>
    <td><?php echo $deduction['label']; ?></td>
    <td><?php echo  $to_string; ?></td>
    <td style="text-align:right;"><?php echo Tools::currencyFormat($deduction['amount']);?></td>
  </tr>
  <?php } ?>
  
  
  <?php 
  	if ($other_deductions):
		foreach ($other_deductions as $oear):
		if (is_object($oear)):
			//if ($oear->getAmount() != 0):
	  ?>
	  <tr>
		<td><?php echo $oear->getLabel();?></td>
		<td></td>
		<td style="text-align:right;"><!--<a style="float:right" title="Remove" href="javascript:removeDeduction('<?php echo $oear->getLabel();?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-close add-earning"></a>--> <?php echo Tools::currencyFormat($oear->getAmount());?></td>
	  </tr>
	  <?php 
			//endif;
		endif;
	  endforeach;
	  ?>  
	  <?php
  endif;
  ?>  
	  <tr class="total_row">
	    <td colspan="2"><div align="left"><strong>Total:</strong></div></td>
	    <td class="total_row_value" style="text-align:right;"><strong>P <?php echo Tools::currencyFormat($total_deductions);?></strong></td>
  	  </tr>	  
