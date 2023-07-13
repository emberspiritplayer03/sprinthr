<div id="payslip_manage">
<div class="payslip_period_container"><?php echo $period;?></div>

<div align="right">
    <!--<a href="<?php echo url("payslip/payslip_preview?eid={$encrypted_employee_id}&from={$from}&to={$to}"); ?>" target="_blank">Download (Excel format)</a> -->
    <!--| <a href="<?php echo url('payslip/payslip_preview'); ?>" target="_blank">Preview (PDF format)</a>-->
    <!--| <a href="#email" onclick="javascript:emailPayslip();">Email Payslip</a>-->            
</div><br />
<div><h3>Salary Type : <?php echo $payslip_info['salary_type']; ?> / Monthly Rate : <?php echo $payslip_info['monthly_rate']; ?> / Daily Rate : <?php echo $payslip_info['daily_rate']; ?> / Hourly Rate : <?php echo $payslip_info['hourly_rate']; ?></h3></div>
<div class="container_12">
	<div class="col_1_2">
        <div class="inner">        
        	<table width="100%" class="formtable">
                <thead>
                    <tr>
                        <th width="50%"><strong>Earnings</strong></th>
                        <th width="20%"></th>
                        <th width="40%"><!--<a style="float:right" title="add earning" href="javascript:addEarning('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-plus add-earning"></a>--></th>
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
                        <th width="50%"><strong>Deductions</strong></th>
                        <th width="20%"></th>
                        <th width="40%"><!--<a style="float:right" title="add deduction" href="javascript:addDeduction('<?php echo $encrypted_employee_id;?>', '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-circle-plus add-deduction"></a>--></th>
                    </tr>
                </thead>
              	<tr><?php include_once('_deductions.php');?></tr>
            </table>       	  
      </div><!-- .inner -->
    </div><!-- .col_1_2 -->
	<div class="clear"></div>
    <div class="payslip_summary_container">
        <div class="col_1_2">
            <div class="inner">
                <table>
                    <thead>
                    <tr>
                        <th colspan="5"><strong>Loan Balance</strong></th>
                    </tr>
                  </thead>
                     <tr>
               </tr>
                <?php

                 $count = 0;

                 foreach($loan_balance_container as $l){ ?> 
                  <?php if($l['value'] != 0){ $count++;?>
                  <tr>
                    <td><?php echo $l['label']; ?></td>
                    <td></td>
                    <td style="text-align: right;"><?php echo number_format($l['value'],2); ?></td>
                    
                  </tr>


                <?php 
                        }
                    }

                    if($count == 0){


                   ?>
                    
                    <tr>
                        <td colspan="3">- No loan Balance found -</td>
                    </tr>

                   <?php     
                    }

                 ?>
                </table>
            </div>
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