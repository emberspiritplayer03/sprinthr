<style>
label.checkbox{margin-left:10px;}
.earnings-title{padding:16px !important;background-color: #198CC9;line-height: 0 !important;color:#ffffff;margin-top:24px;}
.e-title{width:80%;}
</style>
<script>
$(function(){
  $(".chk-add-all-earnings").click(function(){
      var thisCheck = $(this);
      if( thisCheck.is(":checked") ){
        $("#earnings_list").prop("disabled",true);
        $(".btn-add-earning-title").prop("disabled",true);
        $(".earnings-selected-list").hide();
      }else{
        $("#earnings_list").prop("disabled",false);
        $(".btn-add-earning-title").prop("disabled",false);
        $(".earnings-selected-list").show();
      }
  });

  $("#other-earnings-report-year-selector").change(function(){    
     changePayPeriodByYear(this.value,'other-earnings-period-container');
  });
  changePayPeriodByYear($("#other-earnings-report-year-selector").val(),'other-earnings-period-container');

  $(".btn-add-earning-title").click(function(){
      var earnings_selected = $("#earnings_list").val();
      $('.earnings-list').append( '<tr><td class="e-title"><input type="hidden" name="earnings[]" value="' + earnings_selected +'" />' + earnings_selected + '</td><td><a class="btn btn-small btn-remove-row"><i class="icon-trash"></i></a></td></tr>' );
  });

  $(".btn-remove-row").live("click",function(){
    $(this).closest("tr").remove(); 

  });
});
</script>
<h2><?php echo $title;?></h2>
<form id="sss_form" name="sss_form" method="post" action="<?php echo url('reports/download_earnings_report'); ?>">
<div id="form_main" class="employee_form">
  <div id="form_default">
      <table width="100%">   
        <tr>
          <td style="width:17%;">Year</td>
          <td class="form-inline">:
            <select id="other-earnings-report-year-selector">
              <?php for( $start = $start_year; $start <= date("Y"); $start++ ){ ?>
                <option><?php echo $start; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>        
        <tr>
            <td>Payroll Period</td>
            <td class="form-inline">: 
                <div class="other-earnings-period-container" style="display:inline-block;"></div><br />                         
            </td>
        </tr>   
        <tr>
            <td>Select Earnings to add in the report</td>
            <td class="form-inline">: 
                <select id="earnings_list" name="earnings_list">
                    <?php foreach ($earnings_title as $ea):?>
                    <option value="<?php echo $ea['title']; ?>"><?php echo $ea['title']; ?></option>
                    <?php endforeach;?>
                </select>  
                <button type="button" class ="btn btn-small btn-add-earning-title">Add</button>                
                <br />
                <label class="checkbox">
                  <input type="checkbox" class="chk-add-all-earnings" name="chk_all_earnings" />Add all earnings
                </label>
            </td>
        </tr>                
      </table>
      <div class="earnings-selected-list">
        <h3 class="earnings-title">Earnings selected</h3>        
        <table class="earnings-list"></table>
        <hr />
      </div>
  </div><!-- #form_default -->
    <div id="form_default" class="form_action_section">
      <table width="100%">
          <tr>
            <td class="field_label">&nbsp;</td>
            <td><input class="blue_button" type="submit" value="Download Report" /></td>
          </tr>
        </table>
    </div>
</div><!-- #form_main.employee_form -->
</form>
