<style>
.option-list{padding:0;}
.option-list li{list-style: none;display: inline-block;width:30%;}
.tipysy-text{text-align: left;}
</style>
<script>
$(document).ready(function() {     
  //created for inner HTML of loan for weekly conflict with monthly start_date['cutoff']
  const weekly =    "Select cutoff<br />"+
                      "<select style='width:100%;' name='start_date[cutoff]'>"+
                        "<option value='a'>A</option>"+
                        "<option value='b'>B</option>"+
                        "<option value='c'>C</option>"+
                        "<option value='d'>D</option>"+
                      "</select>";
  $('#weekly-cutoff').html('');

  function blockedDates(date){    
    var blocked_dates = "";    
    var disabledDays  = blocked_dates.split(",");

    var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
    var fix_valid_day01 = "<?php echo $cutoff_days[0]; ?>";
    var fix_valid_day02 = "<?php echo $cutoff_days[1]; ?>";
    
    if(d != fix_valid_day02 && d != fix_valid_day01) {        
        return [false];
    }

    return [true];
   
  }

  function loadLoanBreakDown(){
    $(".loading-img-container").html("<small>Recomputing....</small>");
    $.get(base_url + 'loan/ajax_loan_breakdown',$("#add_loan_form").serialize(),
    function(o){
      console.log(o);
      $(".loading-img-container").html("");
      $("#total_amount_to_pay").val(o.loan_amount_with_interest);
      $("#deduction_per_period").val(o.expected_due);
      $("#date_end").val(o.loan_end_date);
    },"json");
  }

  $('#months_to_pay').change(function(){
      loadLoanBreakDown();
  });

    $("#deduction_frequency").change(function(){
      var frequency_id = 0;

      if( $(this).val() == 'Bi-monthly' ){
        $(".label_to_pay").html("Number of cutoffs to pay:");
        $('#monthly-bi-monthly-cutoff').show();
        $('#weekly-cutoff').hide();
        $('#weekly-cutoff').html('');

        frequency_id = 1;
      }else if( $(this).val() == 'Monthly' ){
        $(".label_to_pay").html("Number of months to pay:");
        $('#monthly-bi-monthly-cutoff').show();
        $('#weekly-cutoff').hide();
        $('#weekly-cutoff').html('');
      }   
      else {
        $(".label_to_pay").html("Number of cutoffs to pay:");
        $('#monthly-bi-monthly-cutoff').hide();
        $('#weekly-cutoff').show();
        $('#weekly-cutoff').html(weekly);

        frequency_id = 2;
      }   

      changePayPeriodByYearMonth($("#year-selector").val(),'weekly-cutoff',frequency_id, $("#month-selector").val(), false);
    });

    $("#year-selector").change(function(){
      frequency_id = 2;

      if ($("#deduction_frequency").val().toLowerCase() == 'weekly') {
        changePayPeriodByYearMonth($(this).val(),'weekly-cutoff',frequency_id, $("#month-selector").val(), false);
      }
    });

    $("#month-selector").change(function(){
      frequency_id = 2;

      if ($("#deduction_frequency").val().toLowerCase() == 'weekly') {
        changePayPeriodByYearMonth($("#year-selector").val(),'weekly-cutoff',frequency_id, $(this).val(), false);
      }
    });



  $("#checkFrequency").click(function(){
    if($('#employee_id').val() == " "){
      alert("Please select employee.");
    }else{
      var decypted_employee_id = $('#employee_id').val();
      // alert(decypted_employee_id);

      $.ajax({
        type:'POST',
        url: "loan/ajax_get_frequency_id",
               
        data:  'decypted_employee_id='+decypted_employee_id,
        dataType : "text",
        success:function(result){
         
          if(result == 2){
            frequency_id = 2;
           $("#deduction_frequency").val('Weekly');
           $(".label_to_pay").html("Number of cutoffs to pay:");
           $('#monthly-bi-monthly-cutoff').hide();
           $('#weekly-cutoff').html(weekly);
           $('#weekly-cutoff').show();
            $("#deduction_frequency option[value='Weekly").show(); 
           $("#deduction_frequency option[value='Bi-monthly']").hide(); 
           $("#deduction_frequency option[value='Monthly").hide();
          }

          else if(result == 3){
              $(".label_to_pay").html("Number of months to pay:");
              $("#deduction_frequency").val('Monthly');
               $("#deduction_frequency option[value='Weekly").hide(); 
              $("#deduction_frequency option[value='Bi-monthly']").hide(); 
               $('#monthly-cutoff').show();
               $('#monthly-bi-monthly-cutoff').hide();
          }


          else{
            $("#deduction_frequency").val('Bi-monthly');
            $(".label_to_pay").html("Number of months to pay:");
            $('#monthly-bi-monthly-cutoff').show();
            $('#weekly-cutoff').html('');
             $("#deduction_frequency option[value='Bi-monthly']").show(); 
           $("#deduction_frequency option[value='Monthly").show(); 
           $("#deduction_frequency option[value='Weekly").hide(); 

          }
        }
      });
      //getFrequencyId(decypted_employee_id);
      // var result = "ddd";
      // alert(decypted_employee_id);
      
    }
 });


  /*$("#deduction_frequency").change(function(){
    var frequency_selected = $(this).val();
    if( frequency_selected == 'Monthly' ){      
      //$(".monthly-frequency-selection").show();
      $(".monthly-frequency-selection").show();
      $(".bimonthly-frequency-selection").hide();
    }else{
      $(".monthly-frequency-selection").hide();
      $(".bimonthly-frequency-selection").show();      
    }
  });*/

  $('.tipsy-deduction-frequency').tipsy({gravity: 's', html: true, fade: true });

  /*$('#interest_rate').change(function(){
      loadLoanBreakDown();
  });

  $("#deduction_frequency").change(function(){
      loadLoanBreakDown();
  });

  $("#bimonthly_frequency").change(function(){
      loadLoanBreakDown();
  });

  $('#loan_amount').change(function(){
      loadLoanBreakDown();
  });*/

  $(".btn-recompute-loan-details").click(function(){
    loadLoanBreakDown();
  });

  $('#add_loan_form').validationEngine({scroll:false}); 

  $("#submitBtn").click(function(e){
      e.preventDefault();
      processEmployeeLoan();
  });

  function processEmployeeLoan() {
    var dialog_id = '#' + DIALOG_CONTENT_HANDLER;
    var width = 350 ;
    var height = 180
    var title = 'Notice';
    var message = '<p><b>Warning : Data will be lock for editing once the loan is processed.</p><br />Proceed with processing loan?';
    
    blockPopUp();
    $(dialog_id).html(message);
    $dialog = $(dialog_id);
    $dialog.dialog({
      title: 'Notice',
      resizable: false,
      width: width,
      height: height,
      modal: true,
      close: function() {
        $dialog.dialog("destroy");
        $dialog.hide();
        disablePopUp();
      },    
      buttons: {      
        'Yes' : function(){       
          $dialog.dialog("destroy");
          $dialog.hide();
          disablePopUp();
          showLoadingDialog('Processing...');
          $('#add_loan_form').ajaxSubmit({
                  success: function(o) {
                      if(o.is_success == 1){
                        dialogOkBox(o.message,{});
                        $('#request_loan_button').show();
                        $('#request_loan_form_wrapper').hide();   
                        load_loan_list_dt();           
                      }else{
                        dialogOkBox(o.message,{});      
                      }
                      $("#token").val(o.token)
                  },
                  dataType: "json"
            }); 
        },
        'No' : function(){
          $dialog.dialog("destroy");
          $dialog.hide();
          disablePopUp();       
        }       
      }
    }).show().parent().find('.ui-dialog-titlebar-close').hide();
  }
    
  $("#end_date").datepicker({
    dateFormat:'yy-mm-dd',
    changeMonth:true,
    changeYear:true,
    showOtherMonths:true,
    onSelect  :function() {       
    }
  });
  

  $("#loan_type_id").change(function(){
    var loan_type_id = $(this).val();
    if( loan_type_id == 3 || loan_type_id == 4 || loan_type_id == 8 || loan_type_id == 10 || loan_type_id == 11 || loan_type_id == 12 || loan_type_id == 13 || loan_type_id == 16){ 
      $(".tbl-government-loans").show();
      $(".tbl-company-loans").hide();
    }else{
      $(".tbl-government-loans").hide();
      $(".tbl-company-loans").show();
    }
  });

  // $("#employee_id").change(function()){
  //   var emp_id = $(this);
  //   alert(emp_id);
  // }
  
  // $("#employee_id").change(function(){
  //   if($('#employee_id').val()){
  //      alert($('#employee_id').val());
  //   }

 // $( "#test" ).click(function() {
 //  alert( "Handler for .click() called." );
 //    });
   
 //  });



  $("#government_deduction_frequency").change(function(){
    var selected_item = $(this).val();
    var frequency_id = 0;

    if( selected_item == 'Monthly' ){
      $(".frequency-text").html("month(s)");

      $('#government-monthly-bi-monthly-cutoff').show();
      $('#government-weekly-cutoff').hide();
    }else if( selected_item == 'Bi-monthly' ){
      $(".frequency-text").html("cutoff(s)");

      $('#government-monthly-bi-monthly-cutoff').show();
      $('#government-weekly-cutoff').hide();

      frequency_id = 1;
    }
    else {
      $(".frequency-text").html("cutoff(s)");

      $('#government-monthly-bi-monthly-cutoff').hide();
      $('#government-weekly-cutoff').show();

      frequency_id = 2;


      var url = 'loan/ajax_get_employees_autocomplete?frequency_id='+frequency_id;
      var t = new $.TextboxList('#employee_id', {max:1,plugins: {
      autocomplete: {
        minLength: 2,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + url}
      
      }
    }});

    }

    changePayPeriodByYearMonth($("#year-selector").val(),'government-weekly-cutoff',frequency_id, $("#month-selector").val(), true);
  });

  $("#government-year-selector").change(function(){
    frequency_id = 2;

    if ($("#government_deduction_frequency").val().toLowerCase() == 'weekly') {
      changePayPeriodByYearMonth($(this).val(),'government-weekly-cutoff',frequency_id, $("#government-month-selector").val(), true);
    }
  });

  $("#government-month-selector").change(function(){
    frequency_id = 2;

    if ($("#government_deduction_frequency").val().toLowerCase() == 'weekly') {
      changePayPeriodByYearMonth($("#government-year-selector").val(),'government-weekly-cutoff',frequency_id, $(this).val(), true);
    }
});



  // var url = 'loan/ajax_get_employees_autocomplete?frequency_id='+frequency_id;
  // var frequency_id = 1;
  
  var url = 'loan/ajax_get_employees_autocomplete';
  var t = new $.TextboxList('#employee_id', {max:1,plugins: {
      autocomplete: {
        minLength: 2,
        onlyFromValues: true,
        queryRemote: true,
        remote: {url: base_url + url}
        
      }
    }});
  
  //$(".monthly-frequency-selection").hide();
  //$(".bimonthly-frequency-selection").show();
  $(".tbl-government-loans").hide();



});
</script>

<?php
  $loan_type_group = array();
  foreach($loan_type as $ltkey => $ltd) {
    if( ($ltd->getLoanType() == 'Pagibig Loan') || ($ltd->getLoanType() == 'Pagibig Calamity Loan') || ($ltd->getLoanType() == 'Pagibig Salary Loan') ) {
      $loan_type_group['Pagibig Loan'][] = $ltd;
    }elseif( ($ltd->getLoanType() == 'SSS Loan') || ($ltd->getLoanType() == 'SSS Calamity Loan') || ($ltd->getLoanType() == 'SSS Salary Loan') ) {
      $loan_type_group['SSS Loan'][] = $ltd;
    } else {
      $loan_type_group['Others'][] = $ltd;
    }
  }
?>

<div id="formcontainer">
<form id="add_loan_form" name="add_loan_form" action="<?php echo url('loan/_insert_new_loan'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap"> 
  <h3 class="form_sectiontitle"><?php echo $page_title; ?></h3>
  
<div id="form_main">   
     
    <div id="form_default">      
        <table width="100%">
             <tr>
               <td class="field_label">Employee:</td>
               <td><input class="validate[required]" type="text" name="employee_id" id="employee_id" value="" /></td>
             </tr>
             <tr>
               <td class="field_label">Check Frequency</td>
               <td><input type="button" value="Check" name="checkFrequency" id="checkFrequency" /></td>
             </tr>
             <tr>
               <td class="field_label">Type of Loan:</td>
               <td>
                  <select class="validate[required] select_option" name="loan_type_id" id="loan_type_id">        
                  <?php 
                    $count = 0;
                    foreach($loan_type_group as $ltg_key => $ltg){
                      $count++; 
                      if( $count == 1 ){ 
                        $selected = "selected='selected'"; 
                      }else{ 
                        $selected =''; 
                      }
                  ?>
                    <?php if($ltg_key == 'Others') { ?>
                            <?php foreach($ltg as $lt) { ?>
                                    <option value="<?php echo $lt->getId(); ?>" <?php echo $selected; ?>><?php echo $lt->getLoanType(); ?></option>
                            <?php } ?>
                    <?php }elseif($ltg_key == 'Pagibig Loan') { ?>

                            <optgroup label="<?php echo $ltg_key; ?>">
                              <?php foreach($ltg as $lt) { ?>
                                      <?php if($lt->getLoanType() != 'Pagibig Loan') { ?>
                                      <option value="<?php echo $lt->getId(); ?>" <?php echo $selected; ?>><?php echo str_replace('Pagibig', '', $lt->getLoanType()); ?></option>
                                      <?php } ?>
                              <?php } ?>
                            </optgroup>

                    <?php }elseif($ltg_key == 'SSS Loan') { ?>

                            <optgroup label="<?php echo $ltg_key; ?>">
                              <?php foreach($ltg as $lt) { ?>
                                      <?php if($lt->getLoanType() != 'SSS Loan') { ?>
                                      <option value="<?php echo $lt->getId(); ?>" <?php echo $selected; ?>><?php echo str_replace('SSS', '', $lt->getLoanType()); ?></option>
                                      <?php } ?>
                              <?php } ?>
                            </optgroup>

                    <?php } ?>
                      
                  <?php } ?>                
                  </select>
               </td>
             </tr>               
        </table>
        <hr />
        <table width="100%" class="tbl-government-loans">
              <tr>
               <td class="field_label">Total loan amount:</td>
               <td>
                   <div class="input-append">
                      <input style="width:254px;height:18px;" class="validate[required,custom[money]]" type="text" name="loan_amount" id="loan_amount" value="" />
                      <span class="add-on">Php</span>
                    </div>                  
               </td>
             </tr>
             <tr>
               <td class="field_label">Deduction Frequency:</td>
               <td class="inline">
                  <select class="validate[required] select_option" name="government_deduction_frequency" id="government_deduction_frequency">        
                    <?php foreach($options_deduction_type as $dt){ ?>
                      <option value="<?php echo $dt; ?>"><?php echo $dt; ?></option>
                    <?php } ?>
                  </select>
                  <a class="tipsy-deduction-frequency" title="<p class='tipysy-text'><b>Cutoff</b> - payment will be deduct twice a month or by every cutoff.</p><p class='tipysy-text'><b>Monthly</b> - payment will be deduct once a month. You can select if it is first cutoff or second cutoff.</p>" href="javascript:void(0);">
                    <i class="icon icon-question-sign">&nbsp;</i>
                  </a>
               </td>
             </tr> 
             <tr>
                <td class="field_label">Start Date:</td>
                <td>
                  <div>
                    <ul class="option-list">
                      <li>
                        Select Year<br />
                        <select style="width:93%;" name="government_start_date[year]" id="government-year-selector">
                          <?php for($x = $start_year;$x<=$max_year;$x++){ ?>
                          <option><?php echo $x; ?></option>
                          <?php } ?>  
                        </select>
                      </li>
                      <li>
                        Select Month<br />
                        <select style="width:100%;" name="government_start_date[month]" id="government-month-selector">
                          <?php foreach($months_tags as $tag){ ?>
                          <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
                          <?php } ?>
                        </select>
                      </li>
                      <li id="government-monthly-bi-monthly-cutoff">
                        Select cutoff<br />
                        <select style="width:100%;" name="government_start_date[cutoff]">
                          <option value="a">A</option>
                          <option value="b">B</option>
                        </select>
                      </li>
                      <li id="government-weekly-cutoff" style="display:none;">
                        <select style="width:100%;display:none;" name="government_start_date[cutoff]">
                          <option value="a">A</option>
                          <option value="b">B</option>
                          <option value="c">C</option>
                          <option value="d">D</option>
                        </select>
                      </li>
                    </ul>
                  </div>                 
                </td>
             </tr>  
             <tr>
                <td></td>
                <td class="inline">
                  Amount of <input type="text" name="government_deduction_amount" style="width:18%;margin-left:2px;margin-right:2px;text-align:center;" /> peso(s) in <input type="text" name="government_months_to_pay" style="width:16%;margin-left:2px;margin-right:2px;text-align:center;" /><span class="frequency-text">cutoff(s)</span>
                </td>
             </tr>
        </table>
        <table width="100%" class="tbl-company-loans">
             <tr>
               <td class="field_label">Amount:</td>
               <td>
                   <div class="input-append">
                      <input style="width:254px;height:18px;" class="validate[required,custom[money]]" type="text" name="company_loan_amount" id="loan_amount" value="" />
                      <span class="add-on">Php</span>
                    </div>                  
               </td>
             </tr>
             <tr>
               <td class="field_label">Interest Rate:</td>
               <td>
                    <div class="input-append">
                      <input style="width:45px;height:18px;" class="validate[required,custom[integer]] input-mini" type="text" name="interest_rate" id="interest_rate" value="<?php echo G_Employee_Loan::DEFAULT_INTEREST; ?>" /><span class="add-on">%</span>
                    </div>
                   
               </td>
             </tr>
             <tr>
               <td class="field_label">Deduction Frequency:</td>
               <td class="inline">
                  <select class="validate[required] select_option" name="deduction_frequency" id="deduction_frequency">        
                    <?php foreach($options_deduction_type as $dt){ ?>
                      <option value="<?php echo $dt; ?>"><?php echo $dt; ?></option>
                    <?php } ?>
                  </select>
                  <a class="tipsy-deduction-frequency" title="<p class='tipysy-text'><b>Cutoff</b> - payment will be deducted twice a month or by every cutoff.</p><p class='tipysy-text'><b>Monthly</b> - payment will be deducted once a month. You can select if it is first cutoff or second cutoff.</p>" href="javascript:void(0);">
                    <i class="icon icon-question-sign">&nbsp;</i>
                  </a>
               </td>
             </tr> 
             <tr class="frequency-selection">
                <td class="field_label">Start Date:</td>
                <td>
                  <div>
                    <ul class="option-list">
                      <li>
                        Select Year<br />
                        <select style="width:93%;" name="start_date[year]" id="year-selector">
                          <?php for($x = $start_year;$x<=$max_year;$x++){ ?>
                          <option><?php echo $x; ?></option>
                          <?php } ?>  
                        </select>
                      </li>
                      <li>
                        Select Month<br />
                        <select style="width:100%;" name="start_date[month]" id="month-selector">
                          <?php foreach($months_tags as $tag){ ?>
                          <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
                          <?php } ?>
                        </select>
                      </li>
                      <li id="monthly-bi-monthly-cutoff">
                        Select cutoff<br />
                        <select style="width:100%;" name="start_date[cutoff]">
                          <option value="a">A</option>
                          <option value="b">B</option>
                        </select>
                      </li>
                      <li id="weekly-cutoff" style="display:none;">
                        Select cutoff<br />
                        <select style="width:100%;" name="start_date[cutoff]">
                          <option value="a">A</option>
                          <option value="b">B</option>
                          <option value="c">C</option>
                          <option value="d">D</option>
                        </select>
                      </li>
                    </ul>
                  </div>                 
                </td>
             </tr>                          
             <tr>
               <td class="field_label label_to_pay">Number of cutoffs to pay:</td>
               <td>
                  <input style="width:55px;margin-right:6px;" class="validate[required,custom[integer]]" type="text" name="months_to_pay" id="months_to_pay" value="" /><a class="btn btn-small btn-recompute-loan-details" href="javascript:void(0);">Recompute loan details</a>
               </td>
             </tr>                         
             <tr>
               <td class="field_label" colspan="2"><hr /></td>               
             </tr>              
             <tr>
               <td class="field_label">Total Amount to Pay</td>
               <td>
                  <input readonly="readonly" style="width:205px;margin-right:6px;" type="text" name="total_amount_to_pay" id="total_amount_to_pay" value="" />
               </td>
             </tr>
             <tr>
               <td class="field_label">Deduction per Period</td>
               <td>
                  <input readonly="readonly" style="width:205px;margin-right:6px;" type="text" name="deduction_per_period" id="deduction_per_period" value="" />
               </td>
             </tr>
             <tr>
               <td class="field_label">Date End</td>
               <td>
                  <input readonly="readonly" style="width:205px;margin-right:6px;" type="text" name="date_end" id="date_end" value="" />
               </td>
             </tr>  
             <tr><td></td><td class="loading-img-container"></td></tr>                                                          
         </table>
    </div>
    <div id="form_default" class="form_action_section">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
              <td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" id="submitBtn" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_show_loan_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

