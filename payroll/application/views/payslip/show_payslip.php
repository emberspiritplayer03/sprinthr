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

<div align="right" style="position:relative; z-index:100;">
    <div class="btn-toolbar">
      <div class="btn-group"> 
        <a class="btn btn-small" onclick="javascript:updateEmployeePayslip('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" href="javascript:void(0)"><i class="icon-refresh"></i> Refresh</a>
        <a class="btn btn-small" href="<?php echo url('payslip/payslip_preview?from=' . $from . '&to=' . $to . '&employee_id=' . $encrypted_employee_id); ?>" target="_blank"><i class="icon-zoom-in"></i> Preview (PDF format)</a>
        <a class="btn btn-small" href="#email" onclick="javascript:emailPayslip();"><i class="icon-envelope"></i> Email Payslip</a>
      </div>
    </div>
</div>
<div class="container_12">
	<div class="col_1_2">
        <div class="inner">
        	<table width="100%" class="formtable">
                <thead>
                    <tr>
                        <th width="70%"><strong>Earnings</strong></th>
                        <th width="30%">
                        	<!--<a style="float:right" title="add earning" href="javascript:addEarning('<?php //echo $encrypted_employee_id;?>', '<?php //echo $from;?>', '<?php //echo $to;?>')" class="ui-icon ui-icon-circle-plus add-earning"></a>-->
                        </th>
                    </tr>
                </thead>
               <tr><?php include 'application/views/payslip/_earnings.php';?></tr>
            </table>        	
        </div><!-- .inner -->
    </div><!-- .col_1_2 -->
    <div class="col_1_2">
        <div class="inner">
        	<table width="100%" class="formtable">
                <thead>
                    <tr>
                        <th width="70%"><strong>Deductions</strong></th>
                        <th width="30%">
                        	<a style="float:right" title="add deduction" href="javascript:addDeduction('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-plus add-deduction"></a>
                        </th>
                    </tr>
                </thead>
              	<tr><?php include 'application/views/payslip/_deductions.php';?></tr>
            </table>       	  
      </div><!-- .inner -->
    </div><!-- .col_1_2 -->
	<div class="clear"></div>
    <div class="payslip_summary_container">
        <div class="col_1_2">
            <div class="inner">&nbsp;</div>
        </div><!-- .col_1_2 -->
        <div class="col_1_2">
            <div class="inner payslip_summary">
                <table width="100%" class="formtable">
                  <thead>
                    <tr>
                        <th colspan="5"><strong>Payslip Summary</strong></th>
                    </tr>
                  </thead>
                  <tr>
                    <td width="25%">Earnings</td>
                    <td width="5%" align="center"><strong>-</strong></td>
                    <td width="25%">Deductions</td>
                    <td width="5%" align="center"><strong>=</strong></td>
                    <td class="netpay_label"><strong>Net Pay </strong></td>
                  </tr>
                  <tr class="g-d_netpay">
                    <td width="25%"><strong>P <?php echo Tools::currencyFormat($total_earnings);?></strong></td>
                    <td width="5%" align="center"><strong>-</strong></td>
                    <td width="25%"><strong>P <?php echo Tools::currencyFormat($total_deductions);?></strong></td>
                    <td width="5%" align="center"><strong>=</strong></td>
                    <td class="netpay_total"><strong>P <?php echo Tools::currencyFormat($net_pay);?></strong></td>
                    </tr>
                </table>
            </div><!-- .inner.payslip_summary -->
        </div><!-- .col_1_2 -->
        <div class="clear"></div>
	</div><!-- .payslip_summary_container -->
</div>
</div>

<script language="javascript">
$('.add-earning').tipsy({gravity: 's'});
$('.add-deduction').tipsy({gravity: 's'});
</script>