<style>
.label-success{display: inline-block;text-align: center; height: 21px; width: 42%; line-height: 18px;}
</style>
<script>
$(function(){
  $(".btn-grp-edit").live("click",function(){   
    var btn_id = $(this).attr("data-index");
    $(".input-" + btn_id).prop("disabled",false);   
    //$(".datepicker-" + btn_id).addClass("loan-date-payment");
    $(".sub-action-grp-" + btn_id).show();
    $(".action-grp-" + btn_id).hide();
  });

  $(".btn-cancel").live("click",function(){   
    var btn_id = $(this).attr("data-index");
    $(".sub-action-grp-" + btn_id).hide();
    $(".action-grp-" + btn_id).show();
    $(".input-" + btn_id).prop("disabled",true);   
  });

  $(".btn-grp-remove").live("click",function(){   
    var btn_id  = $(this).attr("data-index");    
    deleteLoanPaymentSchedule(btn_id,this);   
  });

  $(".btn-grp-update").live("click",function(){   
    var btn_id  = $(this).attr("data-index");
    var loan_id = $(this).attr("data-key"); 
    var amount_paid = $(".loan-amount-paid-" + btn_id).val();
    var date_paid   = $(".loan-date-payment-" + btn_id).val();
    var amount_to_pay = $(".loan-amount-to-pay-" + btn_id).val();
    var payment_schedule = $(".loan-payment-schedule-" + btn_id).val();
    var employee_id = $(".employee_id").val();
    var dynamicData = {};
      dynamicData['id'] = btn_id;
      dynamicData['payment_schedule'] = payment_schedule;
      dynamicData['employee_id'] = employee_id;
      dynamicData['amount_paid'] = amount_paid;
      dynamicData['date_paid']   = date_paid;
      dynamicData['amount_to_pay'] = amount_to_pay;
      dynamicData['loan_id']     = loan_id;
      updatePaymentSchedule(dynamicData);
  });

  $(".btn-grp-set-paid").live("click",function(){   
    var loan_schedule_id = $(this).attr("data-index");
    var loan_id          = $(this).attr("data-key");
    setAsPaidLoanSchedule(loan_schedule_id, loan_id);
  });

  $(".btn-cancel-paid").live("click",function(){
    var loan_schedule_id  = $(this).attr("data-index");
    var loan_id           = $(this).attr("data-key");
    setAsUnPaidLoanSchedule(loan_schedule_id,loan_id);
  });

  $(".btn-add-new-row").live("click",function(){   
    var row_id = $(this).attr("data-index");
    var total_rows  = $('.tbl-' + row_id + ' tr').length; 
    var new_row_id  = total_rows + 1;
    $(".tbl-" + row_id).append('<tr class="loan-payment-row"><td><input class="loan-date-payment loan-expected-date-' + new_row_id + '" type="text" style="width:85%;" value="" /></td><td><input type="text" class="loan-expected-amount-' + new_row_id + '" style="width:53%;" value="0.00" /></td><td><input type="text" style="width:53%;" class="loan-paid-' + new_row_id + '" value="0.00" /></td><td><input class="loan-date-payment loan-date-paid-' + new_row_id + '" type="text" style="width:85%;" value="" /></td><td class="temp-btn-grp" style="width:139px"><div class="sub-action-grp"><a href="javascript:void(0);" data-index="' + row_id + '" data-key="' + new_row_id + '" class="btn btn-small btn-grp-save" style="margin-right:5px;">Save</a><a href="javascript:void(0);" class="btn btn-small temp-btn-cancel">Cancel</a></div></td></tr>');        

    //invoke jquery
    $(".loan-date-payment").datepicker({
      dateFormat:'M dd, yy',
      changeMonth:true,
      changeYear:true,
      showOtherMonths:true   
    });

    $(".loan-payment-schedule").datepicker({
      dateFormat:'M dd, yy',
      changeMonth:true,
      changeYear:true,
      showOtherMonths:true   
    });
    
  });
  
  $(".temp-btn-cancel").live("click",function(){
    $(this).closest("tr.loan-payment-row").remove();     
  });

  $(".btn-grp-save").live("click",function(){    
    var loan_id = $(this).attr("data-index");
    var row_id  = $(this).attr("data-key");      
    var expected_date   = $(".loan-expected-date-" + row_id).val();
    var expected_amount = $(".loan-expected-amount-" + row_id).val();    
    var amount_paid     = $(".loan-paid-" + row_id).val();
    var date_paid       = $(".loan-date-paid-" + row_id).val();
    var payment_schedule = $(".loan-payment-schedule-" + row_id).val();
    var employee_id = $(".employee_id").val();
    var dynamicData = {};
      dynamicData['loan_id'] = loan_id;
      dynamicData['row_id']  = row_id;
      dynamicData['payment_schedule']  = payment_schedule;
      dynamicData['employee_id']  = employee_id;
      dynamicData['expected_date']   = expected_date;
      dynamicData['expected_amount'] = expected_amount;
      dynamicData['amount_paid']     = amount_paid;
      dynamicData['date_paid']       = date_paid;
      addPaymentSchedule(dynamicData,this);           
  });

  $(".loan-date-payment").datepicker({
    dateFormat:'M dd, yy',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true   
  });

  $(".loan-payment-schedule").datepicker({
    dateFormat:'M dd, yy',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true   
  });

  $(".sub-action-grp").hide();

  $("#edit_total_amount_to_pay").click(function(){
      if($('#edit_total_amount_to_pay').text() == "Edit") {
        $('#total_amount_to_pay').removeAttr('disabled');
        $('#edit_total_amount_to_pay').text("Cancel");
        $('#update_total_amount_to_pay').removeAttr('disabled');
        $('#deduction_per_period').removeAttr('disabled');
      } else {
        $('#total_amount_to_pay').attr('disabled','disabled');
        $('#edit_total_amount_to_pay').text("Edit");
        $('#update_total_amount_to_pay').attr('disabled','disabled');
         $('#deduction_per_period').attr('disabled','disabled');
      }
  });

  $("#update_total_amount_to_pay").live("click",function(){   
    var loan_id = $('#loan_id').val(); 
    var total_amount_to_pay = $('#total_amount_to_pay').val();
    var deduction_per_period = $("#deduction_per_period").val();
    var employee_id = $(".employee_id").val();
    _updateLoanAmount(loan_id,employee_id,total_amount_to_pay,deduction_per_period);
  });

  $("#update_loan_status_to_stop").live("click",function(){   
    var loan_id = $('#loan_id').val(); 
    var employee_id = $(".employee_id").val();
    _updateLoanStatus(loan_id,employee_id);
  });



});
</script>
<div id="formcontainer">
<div id="formwrap">	
	<h3 class="form_sectiontitle">Loan Payment History</h3>
<div id="form_main">    
    <div id="form_default">          
         <?php foreach($data as $key => $d){ ?>         
         <h3 style="background-color:#0f7bb4;color:#ffffff;padding:6px;line-height:24px;">Loan Details</h3>
         <table width="100%">
              <input type="hidden" class="employee_id" value="<?php echo $d['details']['employee_id']; ?>" />
              <input type="hidden" id="loan_id" value="<?php echo $key; ?>" />
              <tr>
                <td style="width:27%;">Borrower Name </td>
                <td>: <input type="text" style="width:80%" disabled="disabled" value="<?php echo $d['details']['employee_name']; ?>" /></td>
              </tr>
              <tr>
                <td>Loan Type</td>
                <td>: <input type="text" style="width:80%" disabled="disabled" value="<?php echo $d['details']['loan_title']; ?>" /></td>
              </tr>
              <tr>
                <td>Total Amount to Pay</td>
                <td>: <input type="text" style="width:80%" disabled="disabled" id="total_amount_to_pay" value="<?php echo $d['details']['total_amount_to_pay']; ?>" /></td>
              </tr>
              <tr>
                <td>Deduction per Period</td>
                <td>: <input type="text" style="width:80%" disabled="disabled" id="deduction_per_period" value="<?php echo $d['details']['deduction_per_period']; ?>" /></td>
              </tr>
              <tr>
                <td>Status</td>
                <td>: <input type="text" style="width:80%" disabled="disabled" value="<?php echo $d['details']['status']; ?>" /></td>
              </tr>
              <tr>
                <td><a href="javascript:void(0);" class="btn btn-small" id="update_loan_status_to_stop" margin-right:5px;">Request To Stop</a></td>
              </tr>
         </table>
         <br>
           <div id="form_default" class="form_action_section">
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                      <td class="field_label">&nbsp;</td>
                        <td>
                         <a class="btn btn-small" id="edit_total_amount_to_pay" href="javascript:void(0);">Edit</a>
                          <a href="javascript:void(0);" disabled="disabled" class="btn btn-small" id="update_total_amount_to_pay" margin-right:5px;">Update</a>
                        </td>
                    </tr>
                </table>
            </div> 
         <br><br>
         <h3 style="background-color:#0f7bb4;color:#ffffff;padding:6px;line-height:24px;">
          Payment History
          <a class="btn btn-small pull-right btn-add-new-row" data-index="<?php echo $key; ?>" href="javascript:void(0);"><i class="icon icon-plus"></i>Add New</a>
         </h3>        

         <!-- Notification -->         
          <?php
            $is_with_notification = $d['notification_payment']['is_with_notification'];
            $notification_message = $d['notification_payment']['message'];
          ?>
          <div class="loan-notification-<?php echo $key; ?> <?php echo ($is_with_notification ? "alert alert-error" : ''); ?>">
          <?php
            if( $is_with_notification ){
              echo $notification_message;
            }
         ?>           
         </div>

         <!-- Payment Breakdown -->
         <table class="tbl-<?php echo $key; ?>">
            <tr>
              <td style="width:127px;">Payment Schedule</td>
              <td>Amount to Pay</td>
              <td>Amount Paid</td>
              <td>Date Paid</td>
              <td></td>
            </tr>
            <?php 
              $history_data = $d['history'];
              foreach($history_data as $h){
            ?>
              <tr class="loan-payment-row">
                <td><input type="text" class="input-<?php echo $h['id']; ?> loan-payment-schedule loan-payment-schedule-<?php echo $h['id']; ?>" disabled="disabled" value="<?php echo date("M d, Y",strtotime($h['loan_payment_scheduled_date'])); ?>" style="width:85%;" /></td>
                <td><input class="input-<?php echo $h['id']; ?> loan-amount-to-pay-<?php echo $h['id']; ?>" type="text" disabled="disabled" style="width:53%;" value="<?php echo $h['amount_to_pay']; ?>" /></td>
                <td><input class="input-<?php echo $h['id']; ?> loan-amount-paid-<?php echo $h['id']; ?>" type="text" disabled="disabled" style="width:53%;" value="<?php echo $h['amount_paid']; ?>" /></td>
                <td><input class="input-<?php echo $h['id']; ?> loan-date-payment loan-date-payment-<?php echo $h['id']; ?>" type="text" disabled="disabled" style="width:85%;" value="<?php echo ($h['date_paid'] != '' ? date("M d, Y",strtotime($h['date_paid'])) : ''); ?>" /></td>
                <td style="width:139px" class="loan-col-<?php echo $h['id']; ?>">
                  <?php if( $h['is_lock'] == G_Employee_Loan_Payment_Schedule::YES ){ ?>
                      <div class="action-paid-grp-<?php echo $h['id']; ?>">
                        <span class="label label-success">Paid</span>
                        <a class="btn btn-small btn-cancel-paid" href="javascript:void(0);" data-index="<?php echo $h['id']; ?>" data-key="<?php echo $key; ?>">Cancel</a>
                      </div>
                  <?php }else{ ?>
                    <div class="btn-group action-grp action-grp-<?php echo $h['id']; ?>">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        Action
                        <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="javascript:void(0);" data-key="<?php echo $key; ?>" class="btn-grp-edit" data-index="<?php echo $h['id']; ?>"><i class="icon-pencil"></i> Edit</a></li>
                        <li><a href="javascript:void(0);" data-key="<?php echo $key; ?>" class="btn-grp-remove" data-index="<?php echo $h['id']; ?>"><i class="icon-trash"></i> Remove</a></li>
                        <li><a href="javascript:void(0);" class="btn-grp-set-paid" data-index="<?php echo $h['id']; ?>" data-key="<?php echo $key; ?>"><i class="icon-check"></i> Set as paid</a></li>
                      </ul>
                    </div>
                    <div class="sub-action-grp sub-action-grp-<?php echo $h['id']; ?>">
                      <a href="javascript:void(0);" class="btn btn-small btn-grp-update" data-key="<?php echo $key; ?>" data-index="<?php echo $h['id']; ?>" style="margin-right:5px;">Update</a><a href="javascript:void(0);" data-index="<?php echo $h['id']; ?>" class="btn btn-small btn-cancel">Cancel</a>
                    </div>
                  <?php } ?>
                 </td>
              </tr>
            <?php } ?>                     
         </table>  

         <table>
            <tr>
              <td colspan="5"><hr style="margin-top:3px;margin-bottom:3px;" /></td>
            </tr>           
            <tr>
              <td colspan="5" style="background-color:#E3E3E3;"><b>Current Balance</b></td>
              <td class="row-loan-balance-<?php echo $key; ?>" style="text-align:right;background-color:#E3E3E3;"><b><?php echo number_format($d['details']['loan_balance'],2); ?></b></td>
            </tr>  
         </table>  
         <br />             
         <?php } ?>               
    </div>   
    <div id="form_default" class="form_action_section">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
              <td class="field_label">&nbsp;</td>
                <td>
                <a href="javascript:void(0)" onclick="javascript:hide_show_loan_form();">Close</a>
                </td>
            </tr>
        </table>
    </div> 
</div><!-- #form_main -->
</div>
</div>

