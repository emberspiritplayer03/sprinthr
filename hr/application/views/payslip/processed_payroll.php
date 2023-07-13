<style>
table tr th, table tr td { 
  border-bottom : none !important;
}
.dataTables_paginate {
  float:none !important;
}

td {
  border: none !important;
  border-top: none !important;
}
</style>

<script>
var jqAction = jQuery.noConflict(); 
$(function(){

  processedPayrollBasicFormScripts();

  var from = $("#from").val();
  var to = $("#to").val();
  var q = $("#q").val();
  loadProcessedPayrollListDt(from,to,q);

  jqAction('.btn-group-action').dropdown();  
});

</script>
<div class="additional_info_container" style="overflow:visible !important;">
  <div class="btn-group pull-right">
    <a class="btn btn-group-action dropdown-toggle" href="#" style="padding-bottom: 4px; padding-top: 4px;"> With Selected <span class="caret"></span></a>
    <ul class="dropdown-menu">
         <li><a id="<?php echo G_Excluded_Employee_Deduction::HOLD; ?>" href="javascript:void(0);" class="btn-hold-deduction"><i class="icon-ban-circle"></i> Hold Deduction</a></li>
         <li><a id="<?php echo G_Excluded_Employee_Deduction::MOVE; ?>" href="javascript:void(0);" class="btn-move-deduction"><i class="icon-share-alt"></i> Move Deduction</a></li>
    </ul>
  </div>
  <h2>Period: <?php echo date('M j', strtotime($start_date));?> - <?php echo date('M j, Y', strtotime($end_date));?></h2>
</div>
<div class="clear"></div>

<div>
  <div style=" display:none;" class="filter-option-wrapper">
    <h4>Filter Option</h4><br/>
    <select id="filter_field">
      <option value="net_pay">Net Pay</option>
       <option value="basic_pay">Basic Pay</option>
       <option value="gross_pay">Gross Pay</option>
    </select> &nbsp;
    <select id="filter_operator">
      <option value=">=">greater than</option>
       <option value="<=">less than</option>
    </select> &nbsp;
    amount of &nbsp;<input id="filter_amount" type="number" min="0" style="padding:7px; width:10%">&nbsp;
    <a href="javascript:void(0);" class="btn btn-filter"><i class="icon-filter"></i> Filter </a>&nbsp;
    <a href="javascript:void(0);" class="btn btn-hide-filter-form"> Cancel </a>
  </div>
  <a href="javascript:void(0);" class="btn btn-show-filter-form"><i class="icon-filter"></i> Show Filter Option </a> &nbsp;
  <a href="javascript:void(0);" class="btn btn-refresh-form"><i class="icon-refresh"></i> Refresh </a> <br/>
</div>
<hr/>
<form id="frm-processed-payroll" action="<?php echo url('payslip/hold_move_processed_payroll'); ?>" method="post">
  <input type="hidden" id="action" name="action" value="">
  <input type="hidden" id="new_payroll_period_id" name="new_payroll_period_id" value="">
  <input type="hidden" id="from" name="from" value="<?php echo $start_date;?>">
  <input type="hidden" id="to" name="to" value="<?php echo $end_date;?>">
  <input type="hidden" id="q" name="q" value="<?php echo $q;?>">
  <div id="processed-payroll-wrapper"></div>
</form>
<div id="dialog-all"></div>