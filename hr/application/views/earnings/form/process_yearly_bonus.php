<style>
.yearly-bonus-import{margin-top:10px;}
</style>
<script>
$(function(){
  $('.yearly-earnings-tipsy').tipsy({html: true,gravity: 'sw' });
  $(".yearly-bonus-import").hide();

  $(".use-import-file").change(function(){
    var selected_type = $(this).val();
    if( selected_type == 'Yes' ){
      $(".yearly-bonus-import").show();
    }else{
      $(".yearly-bonus-import").hide();
    }
  });

  $("#yearly_earnings_form").validationEngine({scroll:false});
  $('#yearly_earnings_form').ajaxForm({
      success:function(o) {
          if (o.is_success) {    
              console.log(o.data);  
              dialogOkBox(o.message,{ok_url: "earnings/yearly_bonus"});                
              var $dialog = $('#action_form');                    
              $dialog.dialog("destroy");                    

          } else {                            
              dialogOkBox(o.message,{});          
          } 
          $("#token").val(o.token);                  
      },
       error: function(o) {
        console.log(o);
       },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });

  $('#deduct-tardiness').change(function(){
    if($(this).is(':checked')) {
      $('.date-tardiness-container').show();
    }else{
      $('.date-tardiness-container').hide();
      $('#start_month').val(1);
      $('#end_month').val(12);
    }
  });


   $("#frequency-selector").change(function(){  

        changePayPeriodByYear($("#current_year").val(),'pay-period-container',this.value);
  });

    changePayPeriodByYear($("#current_year").val(),'pay-period-container',1);

});
</script>
<div id="formcontainer">
<form id="yearly_earnings_form" name="yearly_earnings_form" action="<?php echo url('earnings/_process_yearly_bonus'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap"> 
  <h3 class="form_sectiontitle">Process 13th Month Bonus</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">            
            <tr class="earning-type-percentage">
               <td style="width:15%" align="left" valign="middle">Percentage</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline">: 
                   <div class="input-append">
                      <input style="width:40px;height:18px;" required='required' class="text-input" value="40" min="1" max="100" type="number" name="percentage" id="percentage" value="" />
                      <span class="add-on">%</span>     
                    </div>                                      
               </td>
             </tr> 
             <tr class="">
               <td style="width:15%" align="left" valign="middle">&nbsp;</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline"> 
                  <input id="deduct-tardiness" type="checkbox" name="deduct_tardiness" value="1" style="margin: 0px 7px 0px 7px ;"> <label for="deduct-tardiness">Deduct Tardiness</label>
               </td>
             </tr> 
             <tr class='date-tardiness-container' style="display:none;">
               <td style="width:15%" align="left" valign="middle">Start month</td>
               <td style="width:15%" align="left" valign="middle">: 
                  <select style="width:40%;" name="start_month" id="start_month">
                  <?php foreach( $months as $key => $m ){ ?>
                    <option <?= ($key ==  1 ? 'selected="selected"' : ''); ?> value="<?php echo $key; ?>"><?php echo $m; ?></option>
                  <?php } ?>
                  </select>
                  <!-- <a title="Start month where 13th month bonus will compute" href="javascript:void(0);" class="yearly-earnings-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a> -->
               </td>
            </tr>  
            <tr class='date-tardiness-container' style="display:none;">
               <td style="width:15%" align="left" valign="middle">End month</td>
               <td style="width:15%" align="left" valign="middle">: 
                  <select style="width:40%;" name="end_month" id="end_month">
                  <?php foreach( $months as $key => $m ){ ?>
                    <option <?= ($key ==  12 ? 'selected="selected"' : ''); ?> value="<?php echo $key; ?>"><?php echo $m; ?></option>
                  <?php } ?>
                  </select>
                  <!-- <a title="End month where 13th month bonus will compute" href="javascript:void(0);" class="yearly-earnings-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a> -->
               </td>
            </tr>  

            <!-- select frequency -->
             <tr>
               <td style="width:15%" align="left" valign="middle">Frequency</td>
               <td style="width:15%" align="left" valign="middle">: 
                  <select id="frequency-selector" name="frequency">
                    <option value = "1">Bi-Monthly</option>
                    <option value = "2">Weekly</option>    
                </select>

                <input type="hidden" name="current_year" id="current_year" value="<?php echo $current_year; ?>">

                <a title="Select Frequency to generate" href="javascript:void(0);" class="yearly-earnings-tipsy">
                  <i class="icon-question-sign">&nbsp;</i>
                </a>
               </td>
            </tr>

             <tr>
               <td style="width:15%" align="left" valign="middle">Cutoff start month</td>
               <td style="width:15%" align="left" valign="middle">: 
                  <select name="payroll_start_month" id="payroll_start_month">
                  <?php foreach( $months as $key => $m ){ ?>
                    <option <?= ($key ==  1 ? 'selected="selected"' : ''); ?> value="<?php echo $key; ?>"><?php echo $m; ?></option>
                  <?php } ?>
                  </select>
                   <a title="Start month where 13th month bonus will compute" href="javascript:void(0);" class="yearly-earnings-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a> 
               </td>
            </tr>  


            <tr>
               <td style="width:15%" align="left" valign="middle">Cutoff release</td>
               <td style="width:15%" align="left" valign="middle">: 
                 <div class="pay-period-container" style="display:inline-block;">
                   
                 </div>
                <a title="Cutoff date wherein 13th month will be included" href="javascript:void(0);" class="yearly-earnings-tipsy">
                  <i class="icon-question-sign">&nbsp;</i>
                </a>
               </td>
            </tr>
            <tr>
               <td style="width:15%" align="left" valign="middle">Use import file</td>
               <td style="width:15%" align="left" valign="middle">: 
                 <select name="use-import-file" class="use-import-file">
                   <option>No</option>
                   <option>Yes</option>
                 </select>
                 <a title="Yes - will use import template file <br /> No - will compute base on system payslip data" href="javascript:void(0);" class="yearly-earnings-tipsy">
                  <i class="icon-question-sign">&nbsp;</i>
                </a>
                <div class="yearly-bonus-import">
                  <input type="file" name="yearly_bonus_file" />
                  <br />
                  <small>
                    <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/earnings/import_yearly_bonus_v2.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('attendance/html_import_overtime');?>" class="btn btn-mini btn-link"></a>
                  </small>
                </div>
               </td>
            </tr>
                                
         </table>
    </div>
    <div id="form_default" class="form_action_section">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
              <td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Process" class="curve blue_button" />
                <a href="<?php echo url('earnings/yearly_bonus'); ?>">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>


