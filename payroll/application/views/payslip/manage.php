<div id="payslip_manage">
<div class="payslip_period_container">
	<div class="float-right"><a class="btn btn-small btn-info" onclick="javascript:updatePayslips('<?php echo $from;?>', '<?php echo $to;?>')" href="javascript:void(0)"><i class="icon-ok icon-white"></i> Update Payslips</a></div>
	<h2>Period: <strong><?php echo Tools::dateFormat($from);?></strong> &nbsp;-&nbsp; <strong><?php echo Tools::dateFormat($to);?></strong></h2>
</div>
<?php if ($is_generated):?>
	<div align="center">
		<h3 class="payoutdate"><span class="blue">Payout Date:</span> <?php echo Tools::dateFormat($payout_date);?></h3>	
    </div>
<?php endif;?>
<?php if (!$is_generated):?>
	<div align="center">
        <h3 class="payoutdate"><span class="blue">Payout Date:</span> <?php echo Tools::dateFormat($payout_date);?></h3>
        <button class="btn btn-info btn-large" type="button" onmouseup="javascript:generatePayslip('<?php echo $from;?>', '<?php echo $to;?>', '<?php echo $salt;?>')"><i class="icon-share icon-white"></i> Generate Payslip</button>
        <br /><br />
    </div>
<?php else:?>
    	<table width="100%" class="formtable">
          <thead>
          <tr>
            <th width="150"><strong>Employee #</strong></th>
            <th><strong>Employee Name</strong></th>
            <th width="130"><strong>Action</strong></th>
          </tr>
          </thead>
	<?php foreach ($employees as $employee_id => $e):?>
          <tr>
            <td width="150"><?php echo $e->getEmployeeCode();?></td>
            <td><a title="Edit Payslip" href="<?php echo url('payslip/show_payslip?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>"><?php echo $e->getName();?></a></td>
            <td width="130">
            	<!--<a style="float:left" title="Edit Payslip" href="javascript:editPayslip(<?php echo $e->getId();?>, '<?php echo $from;?>', '<?php echo $to;?>')" class="ui-icon ui-icon-pencil edit-payslip"></a>-->
 				<div id="dropholder"><a class="dropbutton" title="Edit Payslip" href="<?php echo url('payslip/show_payslip?employee_id='. Utilities::encrypt($e->getId()) .'&hash='. $e->getHash() .'&from='. $from .'&to='. $to);?>">Edit Payslip</a></div>
            </td>
          </tr>
    <?php endforeach;?>
        </table>    
<?php endif;?>
</div>

<script language="javascript">
$('.edit-payslip').tipsy({gravity: 's'});
</script>