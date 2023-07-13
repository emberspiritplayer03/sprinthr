<style>
.yearly-bonus-import{margin-top:10px;}
</style>
<script>
$(function(){
  $('.leave-conversion-tipsy').tipsy({html: true,gravity: 'sw' });  

  $("#leave_conversion_form").validationEngine({scroll:false});
  $('#leave_conversion_form').ajaxForm({
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
<form id="leave_conversion_form" name="leave_conversion_form" action="<?php echo url('annualize_tax/_process_tax'); ?>" method="post"> 
<input type="hidden" id="token" name="token" value="<?php echo $token; ?>" />
<div id="formwrap">	
	<h3 class="form_sectiontitle">Annualize Tax</h3>
<div id="form_main">     
  
    <div id="form_default">      
        <table width="100%" border="0" cellspacing="1" cellpadding="2">        	            
            <tr>
               <td style="width:15%" align="left" valign="middle">Cutoff release</td>
               <td style="width:15%" align="left" valign="middle">: 
                  <select id="cutoff" name="cutoff_period">
                    <?php foreach ($cutoff_periods as $c):?>
                    <option value="<?php echo $c->getStartDate();?>/<?php echo $c->getEndDate();?>"><?php echo $c->getYearTag();?> - <?php echo $c->getMonth();?> - <?php echo $c->getCutoffCharacter();?></option>
                    <?php endforeach;?>
                  </select>
                <a title="Cutoff date wherein tax refund will be given" href="javascript:void(0);" class="leave-conversion-tipsy">
                  <i class="icon-question-sign">&nbsp;</i>
                </a>
               </td>
            </tr>  
            <tr>
               <td style="width:15%" align="left" valign="middle">Import File</td>
               <td style="width:15%" align="left" valign="middle">: 
                 <select name="use-import-file" class="use-import-file">
                   <option>No</option>
                   <option>Yes</option>
                 </select>
                <div class="yearly-hmo-import">
                  <input type="file" name="yearly_hmo_file" />
                  <br />
                  <small>
                    <a href="<?php echo MAIN_FOLDER ."files/sample_import_files/annual_tax/import_yearly_earnings.xlsx"; ?>" class="btn btn-mini btn-link"><i class="icon-excel icon-custom icon-fade"></i> Download template</a>&nbsp;<a target="_blank" href="<?php echo url('attendance/html_import_overtime');?>" class="btn btn-mini btn-link"></a>
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
                <a href="<?php echo url('annualize_tax'); ?>">Cancel</a>
                </td>
            </tr>
        </table>
    </div>
</div><!-- #form_main -->
</div>
</form>
</div>

<script>
  
$(function(){
  $(".yearly-hmo-import").hide();

  $(".use-import-file").change(function(){
    var selected_type = $(this).val();
    if( selected_type == 'Yes' ){
      $(".yearly-hmo-import").show();
    }else{
      $(".yearly-hmo-import").hide();
    }
  });

});

</script>


