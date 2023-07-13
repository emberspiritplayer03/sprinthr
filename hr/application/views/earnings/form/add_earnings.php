<style>
.textboxlist{display:inline-block;}
.apply-to-container p{ font-size:12px;padding:8px;display: block;width:97%;height:20px;background-color:#E3E3E3;font-weight: bold}
.apply-to-container table{margin-left:37px;}
.hdr-apply-to{background-color: #e3e3e3; padding: 10px;font-weight: bold;}
.textboxlist-bits{height:49px;width: 96%;}
.textboxlist{width:343px;}
</style>
<script>
$(function(){
  $("#add_earnings_form").validationEngine({scroll:false});
  $('#add_earnings_form').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
            dialogOkBox(o.message,{});           
            hide_add_earnings_form();                                   
            load_approved_earnings_list_dt('"' + o.eid + '"');
            var $dialog = $('#action_form');                    
            $dialog.dialog("destroy");        
          }else{  
            //hide_add_earnings_form();                          
            dialogOkBox(o.message,{});          
          }   
          $("#token").val(o.token);                
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });

  var t = new $.TextboxList('#employee_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'autocomplete/ajax_get_employees'}     
        }
    }});

  var t2 = new $.TextboxList('#department_section_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'autocomplete/ajax_get_all_department_type_autocomplete'}     
        }
    }});

  var t3 = new $.TextboxList('#employment_status_id', {
      unique: true,
      plugins: {
        autocomplete: {
          minLength: 2,       
          onlyFromValues: true,
          queryRemote: true,
          remote: {url: base_url + 'autocomplete/ajax_get_employment_status_autocomplete'}     
        }
    }});

  $(".chk-earnings-apply-to-all").click(function(){
    if( $(this).prop("checked") ){
      $(".apply-to-tr").hide();
    }else{
      $(".apply-to-tr").show();
    }
  });

  $(".cmb-earning-type").change(function(){
    var selected = $(this).val();
    if( selected == 2 ){
      $(".earning-type-amount").show();
      $(".earning-type-percentage").hide();

      //Add class
      $("#amount").addClass("validate[required,custom[money]]");
      //Remove required class
      $("#percentage").removeClass("validate[required,custom[integer]]");

    }else{
      $(".earning-type-percentage").show();
      $(".earning-type-amount").hide();

      //Add class
      $("#percentage").addClass("validate[required,custom[integer]]");
      //Remove class
      $("#amount").removeClass("validate[required,custom[money]]");
    }
  });


  $(".earning-type-percentage").hide();
});
</script>
<div id="formcontainer">
<form id="add_earnings_form" name="add_earnings_form" action="<?php echo url('earnings/_save_earning'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<input type="hidden" id="frequency_id" name="frequency_id" value="<?php echo $frequency_id; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Add New Earning</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">
        	 <tr>
               <td style="width:15%" align="left" valign="middle">Title</td>
               <td style="width:15%" align="left" valign="middle">: 
               		<input class="validate[required] input-large" type="text" name="e_title" id="e_title" value="" style="width:96%;" />
               </td>
             </tr>     
             <tr>
               <td style="width:15%" align="left" valign="middle" colspan="2">
                 <p class="hdr-apply-to">Apply earnings to : <label class="checkbox pull-right"><input class="chk-earnings-apply-to-all" name="e_apply_to_all" value="1" type="checkbox" />Apply to all employees</label></p>
                 <table style="width:90%">
                      <tr class="apply-to-tr">
                           <td style="width:15%" align="left" valign="middle">Employee </td>
                           <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="e_employee_id" id="employee_id" value="" />                        
                           </td>
                      </tr>
                     <!--  <tr class="apply-to-tr">
                           <td style="width:15%" align="left" valign="middle">Department / Section </td>
                           <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="e_department_section_id" id="department_section_id" value="" />                        
                           </td>
                      </tr>
                      <tr class="apply-to-tr">
                           <td style="width:15%" align="left" valign="middle">Employment status </td>
                           <td style="width:15%" align="left" valign="middle">: <input class="validate[required] text-input" type="text" name="e_employment_status_id" id="employment_status_id" value="" />                        
                           </td>
                      </tr>   -->                    
                    </table>
               </td> 
             </tr>                                           
             <tr><td style="width:15%" align="left" valign="middle" colspan="2"><hr /></td></tr>
             <tr>
               <td style="width:15%" align="left" valign="middle">Earning Type</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline">: 
                 <select class="cmb-earning-type" name="e_earning_type" style="width:51%;">
                    <?php foreach($earning_type_selections as $key => $value){ ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>   
                    <?php } ?>
                 </select>
               </td>
             </tr>  
             <tr class="earning-type-amount">
               <td style="width:15%" align="left" valign="middle">Amount</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline">: 
               		 <div class="input-append">
                     	<input style="width:109%;height:18px;" class="validate[required,custom[money]] text-input" type="text" name="e_amount" id="amount" value="" />
                    	<span class="add-on">Php</span>
                    </div>               		                    
               </td>
             </tr>
             <tr class="earning-type-percentage">
               <td style="width:15%" align="left" valign="middle">Percentage</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline">: 
                   <div class="input-append">
                      <input style="width:7%;height:18px;" class="text-input" type="text" name="e_percentage" id="percentage" value="" />
                      <span class="add-on">%</span>
                      <span class="add-on" style="margin-left:5px;">of</span>
                      <select style="width:44%;" name="e_percentage_selection">
                        <?php foreach($percentage_selections as $key => $value){ ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                      </select>
                    </div>                                      
               </td>
             </tr>             
             <tr>
               <td></td>
               <td>   
                  <label class="checkbox" style="margin-left:7px;">
                    <input type="checkbox" id="is_taxable" name="e_is_taxable" value="1" />Taxable
                  </label>
               </td>
             </tr>
             <tr>
               <td style="width:15%" align="left" valign="middle">Add to Payroll Period</td>
               <td style="width:15%" align="left" valign="middle">
               		: <select style="width:40%;" name="e_cutoff_period">
                   <?php foreach($cutoff_periods as $cp){ ?>
                      <option <?php echo($cp['id'] == $current_cutoff_id ? 'selected="selected"' : '') ?> value="<?php echo $cp['id']; ?>"><?php echo $cp['cutoff']; ?></option>
                   <?php } ?>

                  </select>
               </td>
             </tr>                                                  
             <tr>
               <td style="width:15%" align="left" valign="middle">Remarks</td>
               <td style="width:15%" align="left" valign="middle" class="form-inline">
               		: <textarea class="input-large" rows="3" id="remarks" name="e_remarks"></textarea>               		
               </td>
             </tr>                                  
         </table>
    </div>
    <div id="form_default" class="form_action_section">
    	<table width="100%" border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td class="field_label">&nbsp;</td>
                <td>
                <input type="submit" value="Save" class="curve blue_button" />
                <a href="javascript:void(0)" onclick="javascript:hide_add_earnings_form();">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

