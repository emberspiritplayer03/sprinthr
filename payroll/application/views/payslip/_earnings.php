  <?php foreach ($earnings as $ear):
  	if (is_object($ear)):
  		if ($ear->getAmount() != 0):
  ?>
  <tr>
    <td><?php echo $ear->getLabel();?>
	</td>
    <td><?php echo Tools::currencyFormat($ear->getAmount());?></td>
  </tr>
  <?php 
 		endif;
	endif;
  endforeach;?>
  
  <?php 
  	if ($other_earnings):
		foreach ($other_earnings as $oear):
		if (is_object($oear)):
			if ($oear->getAmount() != 0):
	  ?>
	  <tr>
		<td><?php echo $oear->getLabel();?></td>
		<td><a style="float:right" title="Remove" href="javascript:removeEarning('<?php echo $oear->getLabel();?>', '<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-close add-earning"></a> <?php echo Tools::currencyFormat($oear->getAmount());?></td>
	  </tr>
	  <?php 
			endif;
		endif;
	  endforeach;
	  ?>  
	  <?php
  endif;
  ?>  
      <tr class="total_row">
	    <td><div align="right"><strong>Total:</strong></div></td>
	    <td class="total_row_value"><strong>P <?php echo Tools::currencyFormat($total_earnings);?></strong></td>
  	  </tr>
