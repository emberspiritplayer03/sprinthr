<script>
$(function(){
  $("#year-selector").change(function(){    
     changePayPeriodByYear(this.value,'pay-period-container',$("#frequency-selector").val());
  });

  $("#frequency-selector").change(function(){    
     changePayPeriodByYear($("#year-selector").val(),'pay-period-container',this.value);
  });

  changePayPeriodByYear($("#year-selector").val(),'pay-period-container',$("#frequency-selector").val());
});
</script>


<form method="post" name="generate_activity_form" id="generate_activity_form" action="<?php echo url('activity/generate_employee_activities');?>"> 	
<div id="form_main" class="inner_form popup_form wider">
	<div id="form_default">
    	<table width="100%" border="0" cellspacing="1" cellpadding="2">
          <tr>
            <td style="width:25%" align="left" valign="middle">Frequency :</td>
            <td style="width:85%" align="left" valign="middle">
                <select class="select_option validate[required] " id="frequency-selector" name="frequency_id">
                    <option value="">- select frequency -</option>
                    <?php
                        foreach($frequency as $f){ ?>

                             <option value="<?php echo $f->getId(); ?>"><?php echo $f->getFrequencyType(); ?></option>

                    <?php
                        }
                    ?>
                </select>
            </td>
          </tr>

              <tr>
              <td style="width:25%;" align="left" valign="middle">Year :</td>
              <td style="width:85%" align="left" valign="middle">
                <select id="year-selector" name="year">
                  <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                    <option><?php echo $start; ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>     
            <tr>
                <td style="width:25%;" align="left" valign="middle">Payroll Period</td>
                <td style="width:85%" align="left" valign="middle">
                    <div class="pay-period-container" style="display:inline-block;"></div><br />                         
                </td>
            </tr>

         </table>
    </div>   

    <div id="form_default" class="form_action_section">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td>
            	<input type="submit" value="Generate" class="curve blue_button" />            	
            	<a href="javascript:void(0);" onclick="javascript:closeDialogBox('#_dialog-box_','#generate_activity_form');">Cancel</a>
            </td>
          </tr>
        </table>		
    </div>
</div>
</form>