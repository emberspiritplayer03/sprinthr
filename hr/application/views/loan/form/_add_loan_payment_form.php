<input type="hidden" id="employee_loan_payment_id" name="employee_loan_payment_id" value="<?php echo Utilities::encrypt($geld->getId()); ?>" />
<table width="100%">
     <tr>
       <td class="field_label">Amount Due:</td>
       <td><input disabled="disabled" class="validate[required] text-input" type="text" name="amount" id="amount" value="<?php echo number_format($geld->getAmount(),2,".",""); ?>" /></td>
     </tr>
     <tr>
       <td class="field_label">Amount Paid:</td>
       <td>
           <!--<div class="control-group">--><div>            
                <div class="controls">                   
                    <input style="width:170px;z-index:9999;" class="validate[required,custom[money]]" type="text" name="amount_paid" id="amount_paid" value="0.00" />                    
                    <span class="" id="payment_breakdown" title="View Payment Breakdown" style="height:18px;">&nbsp;<a class="btn" href="javascript:void(0);" onclick="javascript:load_loan_payment_breakdown('<?php echo Utilities::encrypt($geld->getId()); ?>',1);"><i class="icon-chevron-down"></i></a></span>
                </div>
            </div>
       </td>
     </tr>
     <tr id="wrapper_breakdown" style="display:none;">
      
       <td class="field_label" colspan="2">
          <div id="loan_payment_breakdown_wrapper"></div>
       </td>
     </tr>
     <tr>
       <td class="field_label">Reference Number:</td>
       <td><input title="Cheque or OR Number" class="validate[optional] text-input" type="text" name="reference_number" id="reference_number" value="" /></td>
     </tr>       
     <tr>     
       <td class="field_label">Remarks:</td>
       <td>
            <textarea name="remarks" id="remarks" style="width:287px;min-width:287px;"></textarea>
       </td>
     </tr>                         
 </table>

<script>
$('#reference_number').tipsy({gravity: 's'});
$('#payment_breakdown').tipsy({gravity: 's'});
</script>