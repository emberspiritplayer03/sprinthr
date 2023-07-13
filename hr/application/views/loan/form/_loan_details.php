<div id="formcontainer">
<div id="formwrap">	
	<h3 class="form_sectiontitle">Loan Details</h3>
<div id="form_main">    
    <div id="form_default">      
         <?php foreach($data as $d){ ?>
         <h3 style="background-color:#0f7bb4;color:#ffffff;padding:6px;line-height:24px;">Borrower Name : <?php echo $d['details']['employee_name']; ?></h3>
         <table width="100%">             
              <tr>
                <td style="width:28%;">Loan Type</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['loan_title']; ?>" /></td>
              </tr>
              <tr>
                <td>Interest Rate</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['interest_rate'] . "%"; ?>" /></td>
              </tr>
              <tr>
                <td>Loan Amount</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['loan_amount']; ?>" /></td>
              </tr>
              <tr>
                <td>Amount Paid</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['amount_paid']; ?>" /></td>
              </tr>
              <tr>
                <td>Months to Pay</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['months_to_pay']; ?>" /></td>
              </tr>
              <tr>
                <td>Deduction Type</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['deduction_type']; ?>" /></td>
              </tr>
              <tr>
                <td>Start Date of Loan</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['start_date']; ?>" /></td>
              </tr>
              <tr>
                <td>End Date of Loan</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['end_date']; ?>" /></td>
              </tr>
              <tr>
                <td>Total Amount to Pay</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['total_amount_to_pay']; ?>" /></td>
              </tr>
              <tr>
                <td>Deduction per Period</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['deduction_per_period']; ?>" /></td>
              </tr>
              <tr>
                <td>Status</td>
                <td>: <input type="text" style="width:92%;" disabled="disabled" value="<?php echo $d['details']['status']; ?>" /></td>
              </tr>
         </table>
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

