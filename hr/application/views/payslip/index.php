<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="160"><strong>Period</strong></th>
    <th><strong>Action</strong></th>
    <th></th>
    <th></th>
  </tr>
</thead>
  <?php foreach ($periods as $period):?>
  <tr>
    <td width="160" class="payslip_period"><strong><a href="<?php echo url('payslip/manage?from='. $period['start'] .'&to='. $period['end']);?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>
    <td><div id="dropholder"><a class="dropbutton" href="<?php echo url('payslip/manage?from='. $period['start'] .'&to='. $period['end']);?>">View List</a></div></td>
    <td><div id="dropholder"><a class="dropbutton" href="<?php echo url('payslip/download_payslips?from='. $period['start'] .'&to='. $period['end']);?>">Download Payslips</a></div></td>
	<td><div id="dropholder"><a class="dropbutton" href="<?php echo url('payroll/download_payroll_register?from='. $period['start'] .'&to='. $period['end']);?>">Download Payroll Register</a></div></td>
</tr>
  <?php endforeach;?>
</table>
