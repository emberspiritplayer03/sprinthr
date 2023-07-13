<form method="post" action="<?php echo url('payslip_manager/change_period_payout_date');?>">
<input type="hidden" name="from" value="<?php echo $from;?>" />
<input type="hidden" name="to" value="<?php echo $to;?>" />

  <table width="320" border="0" cellpadding="5" cellspacing="1">
    <tr>
      <td width="85">Payout Date:</td>
      <td width="212"><input type="text" name="payout_date" id="payout_date" value="<?php echo $current_payout_date;?>" /></td>
    </tr>
  </table>
</form>