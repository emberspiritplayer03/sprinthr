<script>
$(function(){
  $('#payslip_form').validationEngine({scroll:false});   
  $("#year").change(function(){
    var year = $(this).val();
    loadCutoffPeriodByYear(year);
  });

  loadCutoffPeriodByYear($("#year").val());
});

function loadCutoffPeriodByYear(year) {
  $("#cutoff_period_wrapper").html(loading_image);
  $.get(base_url + 'reports/_ajax_load_cutoff_period_by_year',{year:year},function(o) {
    $("#cutoff_period_wrapper").html(o).hide(); 
    $("#cutoff_period_wrapper").fadeIn(1000);
  }); 
}
</script>
<h2>Payslip</h2>
<form id="payslip_form" name="form1" method="post" action="<?php echo url('reports/download_payslip');?>">
<div id="form_main" class="employee_form">
	<div id="form_default">
      <table width="100%">
        <tr>
          <td class="field_label">Year:</td>
          <td>
              <select id="year" name="year" style="width:15%">
                <?php foreach($cutoff_year as $cy) { ?>
                    <option <?php echo (date("Y") == $cy->getYearTag() ? "selected='selected'" : "");?> value="<?php echo $cy->getYearTag();?>" ><?php echo $cy->getYearTag();?></option>
                <?php } ?>
                <?php if(!$cutoff_year) { ?>
                    <option value="<?php echo date('Y');?>"><?php echo date('Y');?></option>
                <?php }?>
              </select>
          </td>
        </tr>

        <tr>
            <td class="field_label">Period:</td>
            <td>
                <div id="cutoff_period_wrapper"> </div>
            </td>
        </tr>

      </table>
	</div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
    	<table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div>
</form>
