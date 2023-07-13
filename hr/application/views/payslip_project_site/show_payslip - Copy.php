<div id="show_payslip">
<h3><?php echo $period;?></h3>
<table width="603" border="0" cellpadding="5" cellspacing="1">
  <tr>
    <td valign="top"><div style="float:left">
      <?php if ($next_employee_id != ''):?>
      <a title="Load previous employee" style="float:left" class="ui-icon ui-icon-circle-arrow-w" href="<?php echo url('payslip/show_payslip?employee_id='. $next_encrypted_employee_id .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>"></a>
      <?php endif;?>
      <span style="float:left">Employee</span>
      <?php if ($previous_employee_id != ''):?>
      <a title="Load next employee" style="float:left" class="ui-icon ui-icon-circle-arrow-e" href="<?php echo url('payslip/show_payslip?employee_id='. $previous_encrypted_employee_id .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>"></a>
      <?php endif;?>
    </div></td>
    <td align="right" valign="top"><a href="<?php echo url('payslip/manage?from='. $from .'&to='. $to);?>">Show Employee List</a> | <a href="<?php echo url('payslip/export_payslip?employee_id='. $employee_id .'&from='. $from .'&to='. $to);?>">Export Payslip (PDF)</a></td>
  </tr>
  <tr>
    <td width="290" valign="top">
    <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#cccccc">
      <tr>
        <td bgcolor="#efefef"><table width="100%" border="0">
          <tr>
              <td width="83%"><strong>Transactions/Earnings</strong></td>
              <td width="17%"><a style="float:right" title="add earning" href="javascript:addEarning('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-plus add-earning"></a></td>
            </tr>
        </table></td>
        </tr>
      <tr>
        <td bgcolor="#ffffff">
        	<?php include 'application/views/payslip/_earnings.php';?>		</td>
      </tr>
    </table></td>
    <td width="290" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#cccccc">
      <tr>
        <td bgcolor="#efefef"><table width="100%" border="0">
          <tr>
            <td width="83%"><strong>Deductions</strong></td>
            <td width="17%"><a style="float:right" title="add deduction" href="javascript:addDeduction('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-plus add-deduction"></a></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#ffffff">
			<?php include 'application/views/payslip/_deductions.php';?>		</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#cccccc">
      <tr>
        <td bgcolor="#efefef"><table width="100%" border="0">
            <tr>
              <td width="83%"><strong>Payslip Summary </strong></td>
              </tr>
        </table></td>
      </tr>
      <tr>
        <td bgcolor="#ffffff"><table width="100%" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td width="15%">Gross Pay </td>
            <td width="5%">+</td>
            <td width="5%">Allowance</td>
            <td width="3%">-</td>
            <td width="13%">Deductions</td>
            <td width="3%">=</td>
            <td width="61%"><strong>Net Pay </strong></td>
          </tr>
          <tr>
            <td><?php echo Tools::currencyFormat($gross_pay);?></td>
            <td>&nbsp;</td>
            <td><?php echo Tools::currencyFormat($total_allowance);?></td>
            <td>&nbsp;</td>
            <td><?php echo Tools::currencyFormat($total_deductions);?></td>
            <td>&nbsp;</td>
            <td><strong><?php echo Tools::currencyFormat($net_pay);?></strong></td>
            </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</div>

<script language="javascript">
$('.add-earning').tipsy({gravity: 's'});
$('.add-deduction').tipsy({gravity: 's'});
</script>