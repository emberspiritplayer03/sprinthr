<style>
.yearly-bonus-import{margin-top:10px;}
</style>
<script>
$(function(){
  $('.annualize-tax-tipsy').tipsy({html: true,gravity: 'sw' });
  $("#annualize_tax_form").validationEngine({scroll:false});
  $('#annualize_tax_form').ajaxForm({
      success:function(o) {
          if (o.is_success) {        
              dialogOkBox(o.message,{ok_url: "annualize_tax"});                
              var $dialog = $('#action_form');                    
              $dialog.dialog("destroy");                    

          } else {                            
              dialogOkBox(o.message,{});          
          } 
          $("#token").val(o.token);                  
      },
      dataType:'json',
      beforeSubmit: function() {
              showLoadingDialog('Saving...');
      }
  });
});
</script>
<div id="formcontainer">
<form id="annualize_taxform" name="annualize_tax_form" action="<?php echo url('annualize_tax/_process_annual_tax'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Process Annual Tax</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">        	 
            <tr>
               <td style="width:15%" align="left" valign="middle">Start</td>
               <td style="width:80%" align="left" valign="middle">: 
                  <select style="width:40%;" name="start_month" id="start_month">
                  <?php foreach( $months as $key => $m ){ ?>
                    <option value="<?php echo $key; ?>"><?php echo $m; ?></option>
                  <?php } ?>
                  </select>
                  <select style="width:20%;" name="start_year" id="start_year">
                  <?php for($x = $start_year; $x <= $end_year; $x++){ ?>
                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                  <?php } ?>                                      
                  </select>
                  <a title="Start date wherein the tax will compute" href="javascript:void(0);" class="annualize-tax-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a>
               </td>
            </tr>  
            <tr>
               <td style="width:15%" align="left" valign="middle">End</td>
               <td style="width:80%" align="left" valign="middle">: 
                  <select style="width:40%;" name="end_month" id="end_month">
                  <?php foreach( $months as $key => $m ){ ?>
                    <option value="<?php echo $key; ?>"><?php echo $m; ?></option>
                  <?php } ?>
                  </select>
                  <select style="width:20%;" name="end_year" id="end_year">
                  <?php for($x = $start_year; $x <= $end_year; $x++){ ?>
                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                  <?php } ?>                                      
                  </select>
                  <a title="End date wherein tax will compute" href="javascript:void(0);" class="annualize-tax-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a>
               </td>
            </tr>    
            <tr>
               <td style="width:15%" align="left" valign="middle">Annualized tax for the year</td>
               <td style="width:80%" align="left" valign="middle">:                  
                  <select style="width:20%;" name="start_year" id="start_year">
                  <?php for($x = $start_year; $x <= $end_year; $x++){ ?>
                    <option value="<?php echo $x; ?>"><?php echo $x; ?></option>
                  <?php } ?>                                      
                  </select>
                  <a title="Annualize tax year" href="javascript:void(0);" class="annualize-tax-tipsy">
                    <i class="icon-question-sign">&nbsp;</i>
                  </a>
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


