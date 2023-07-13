<div id="payslip_manage">
<div class="payslip_period_container"><?php echo $period;?></div>
<!--<tr>
<td valign="top"><div style="float:left">
  <?php if ($next_employee_id != ''):?>
  <a title="Load previous employee" style="float:left" class="ui-icon ui-icon-circle-arrow-w" href="<?php echo url('payslip/show_payslip?employee_id='. $next_encrypted_employee_id .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>"></a>
  <?php endif;?>
  <span style="float:left">Employee</span>
  <?php if ($previous_employee_id != ''):?>
  <a title="Load next employee" style="float:left" class="ui-icon ui-icon-circle-arrow-e" href="<?php echo url('payslip/show_payslip?employee_id='. $previous_encrypted_employee_id .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>"></a>
  <?php endif;?>
</div></td>
<td align="right" valign="top"><a href="<?php echo url('payslip/manage?from='. $from .'&to='. $to);?>">Show Employee List</a></td>
</tr>-->

<div style="text-align: center"><h3>Sorry, no payslip found for this employee</h3></div>

</div>

<script language="javascript">
$('.add-earning').tipsy({gravity: 's'});
$('.add-deduction').tipsy({gravity: 's'});
</script>