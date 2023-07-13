<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="200"><strong>Period</strong></th>
    <th width="180"><strong>Action</strong></th>
    <th></th>
    <th></th>
  </tr>
</thead>
  <?php foreach ($periods as $period):?>
  <tr>
    <td class="payslip_period"><strong><a href="<?php echo url('payslip/manage?from='. $period['start'] .'&to='. $period['end']);?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>
    <td class="vertical-middle"><div id="dropholder"><a class="dropbutton" href="<?php echo url('payslip/manage?from='. $period['start'] .'&to='. $period['end']);?>"><i class="icon-zoom-in icon-fade"></i> View List</a></div></td>
    <td class="vertical-middle"><i class="icon-download-alt icon-fade"></i> Download:&nbsp;&nbsp;&nbsp;<a class="btn btn-mini" href="<?php echo url('payslip/download_payslips?from='. $period['start'] .'&to='. $period['end']);?>">Payslips</a>&nbsp;&nbsp;<a class="btn btn-mini" href="<?php echo url('payroll/download_payroll_register?from='. $period['start'] .'&to='. $period['end']);?>">Payroll Register</a></td>
    <td class="vertical-middle">&nbsp;</td>
</tr>
  <?php endforeach;?>
</table>
