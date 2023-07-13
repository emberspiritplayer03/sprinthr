<style>
span.add-on{height:17px !important; margin-right: 6px;}
div.input-append small{font-size:11px !important;line-height: 23px;}
.input-append input{position: static;}
</style>
<?php 
	$b = explode(':',$deduction->getBreakdown());
?>
<script>
	var old_1stcutoff = '<?php echo $b[0]; ?>';
	var old_2ndcutoff = '<?php echo $b[1]; ?>';
  var old_3rdcutoff = '<?php echo $b[2]; ?>';
  var old_4thcutoff = '<?php echo $b[3]; ?>';
	function getPercentage(cut_off) {
		var a = parseFloat($('#1st_cutoff').val());
		var b = parseFloat($('#2nd_cutoff').val());
    var c = parseFloat($('#3rd_cutoff').val());
    var d = parseFloat($('#4th_cutoff').val());
		
		var total;
		
		if(isNaN(a) || isNaN(b) || isNaN(c) || isNaN(d) || a < 0 || a > 100 || b < 0 || b > 100 || c < 0 || c > 100 || d < 0 || d > 100) {
			// $('#1st_cutoff').val(old_1stcutoff);
			// $('#2nd_cutoff').val(old_2ndcutoff);
   //    $('#3rd_cutoff').val(old_3rdcutoff);
   //    $('#4th_cutoff').val(old_4thcutoff);
		} else {
		
			if(cut_off == 1) {
        total = 100-a;
    //     $('#2nd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
    //     $('#3rd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
				// $('#4th_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
			} else if(cut_off == 2) {
        total = 100-b;
				// $('#1st_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
    //     $('#3rd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
    //     $('#4th_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
			} else if(cut_off == 3) {
        total = 100-c;
        // $('#1st_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
        // $('#2nd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
        // $('#4th_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
      } else if(cut_off == 4) {
        total = 100-d;
        // $('#1st_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
        // $('#2nd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
        // $('#3rd_cutoff').val(parseFloat((total/3).toFixed(3).slice(0,-1)));
      }
		}
	}
</script>
<div id="form_main" class="inner_form popup_form">
<form id="edit_weekly_deduction_breakdown_form" method="post" action="<?php echo url('settings/_update_weekly_deduction_breakdown');?>">
<input type="hidden" name="h_id" value="<?php echo Utilities::encrypt($deduction->getId());?>" />
    <div id="form_default">
      <table>
        <tr>
          <td class="field_label">Name: </td>
          <td><input type="text" readonly="readonly" value="<?php echo $deduction->getName();?>" /></td>
      </tr>
      <tr>
        <td class="field_label">Breakdown :</td>
        <td>
        	<div class="input-append">
        		<input type="text" class="validate[required]" onchange="javascript:getPercentage(1);" id="1st_cutoff" name="1st_cutoff" style="width:15%" value="<?php echo $b[0]; ?>" />
        		<span class="add-on">%</span> <small class="red">(1st Cut-off)</small>
        	</div>        	
        	<div class="input-append">
        		<input type="text" class="validate[required]" onchange="javascript:getPercentage(2);" id="2nd_cutoff" name="2nd_cutoff" style="width:15%" value="<?php echo $b[1]; ?>" />
        		<span class="add-on">%</span> <small class="red">(2nd Cut-off)</small>
        	</div>     
          <div class="input-append">
            <input type="text" class="validate[required]" onchange="javascript:getPercentage(3);" id="3rd_cutoff" name="3rd_cutoff" style="width:15%" value="<?php echo $b[2]; ?>" />
            <span class="add-on">%</span> <small class="red">(3rd Cut-off)</small>
          </div>     
          <div class="input-append">
            <input type="text" class="validate[required]" onchange="javascript:getPercentage(4);" id="4th_cutoff" name="4th_cutoff" style="width:15%" value="<?php echo $b[3]; ?>" />
            <span class="add-on">%</span> <small class="red">(4th Cut-off)</small>
          </div>        
      </tr>
      <tr>
        <td class="field_label">Base salary credit :</td>
        <td>
        	<select name="salary_credit" id="salary_credit" style="width:47%;">
        		<?php foreach($salary_credit as $key => $value){ ?>	
        			<option <?php echo( $deduction->getSalaryCredit() == $key ? 'selected="selected"' : ''); ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
        		<?php } ?>
        	</select>
        </td>
      </tr>
      <tr>
        <td class="field_label">Is taxable :</td>
        <td>
        	<select name="is_taxable" id="is_taxable" style="width:47%;">
        		<?php foreach($yes_no as $value){ ?>	
        			<option <?php echo( $deduction->getIsTaxable() == $value ? 'selected="selected"' : ''); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
        		<?php } ?>
        	</select>
        </td>
      </tr>
    </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
        <table>
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="clodeEditWeeklyDeductionBreakdown()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</form>
</div>
