 <?php 
	$b = explode(':',$deduction->getBreakdown());
?>
<script>
	var old_1stcutoff = '<?php echo $b[0]; ?>';
	var old_2ndcutoff = '<?php echo $b[1]; ?>';
	function getPercentage(cut_off) {
		var a = parseFloat($('#1st_cutoff').val());
		var b = parseFloat($('#2nd_cutoff').val());
		
		var total;
		
		if(isNaN(a) || isNaN(b) || a < 0 || a > 100 || b < 0 || b > 100) {
			$('#1st_cutoff').val(old_1stcutoff);
			$('#2nd_cutoff').val(old_2ndcutoff);
		} else {
		
			if(cut_off == 1) {
				total = 100-a;
				$('#2nd_cutoff').val(total);
			} else if(cut_off == 2) {
				total = 100-b;
				$('#1st_cutoff').val(total);
			}
		}
	}
</script>
<div id="form_main" class="inner_form popup_form">
<form id="edit_deduction_breakdown_form" method="post" action="<?php echo url('startup/_update_deduction_breakdown');?>">
<input type="hidden" name="h_id" value="<?php echo Utilities::encrypt($deduction->getId());?>" />
    <div id="form_default">
      <table>
        <tr>
          <td class="field_label">Name: </td>
          <td><strong><?php echo $deduction->getName();?></strong></td>
      </tr>
      <tr>
        <td class="field_label">Breakdown :</td>
        <td><input type="text" class="validate[required]" onchange="javascript:getPercentage(1);" id="1st_cutoff" name="1st_cutoff" style="width:15%" value="<?php echo $b[0]; ?>" /> % : <input type="text" class="validate[required]" onchange="javascript:getPercentage(2);" id="2nd_cutoff" name="2nd_cutoff" style="width:15%" value="<?php echo $b[1]; ?>" /> %</td>
      </tr>
    </table>
    </div><!-- #form_default -->
    <div class="form_action_section" id="form_default">
        <table>
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input value="Save" class="curve blue_button" type="submit">&nbsp;<a href="javascript:void(0);" onclick="clodeEditDeductionBreakdown()">Cancel</a></td>
            </tr>
        </table>
    </div><!-- #form_default.form_action_section -->
</form>
</div>