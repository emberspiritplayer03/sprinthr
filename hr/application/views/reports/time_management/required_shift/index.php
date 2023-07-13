<h2><?php echo $title; ?></h2>
<script>
$("#birthdate_required_shift").datepicker({dateFormat:'yy-mm-dd',changeMonth:true,changeYear:true,showOtherMonths:true});	

$(function(){
    $("#frm-report-required-shift").validationEngine({scroll:false}); 

    $('#month').change(function(){
        var month = $(this).val();
        var year = $('#year').val();
        loadScheduleSelector(month, year); 
    });

    $('#year').change(function(){
        var year = $(this).val();
        var month = $('#month').val();
        loadScheduleSelector(month, year); 
    });

    loadScheduleSelector($('#month').val(), $('#year').val());

});

function loadScheduleSelector(month, year) {
    $.post(base_url+'reports/ajax_load_schedule_selector',{month:month,year:year},
    function(o){
        $("#schedule-selector-container").html(o);   
    }); 
} 


</script>
<div id="form_main" class="employee_form">
<form id="frm-report-required-shift" name="form1" method="post" action="<?php echo url('reports/download_required_shift_data'); ?>">
     <div id="form_default">
        <table width="100%">
            <tr>
                <td class="field_label">Period:</td>
                <td>
                    <select id="month" style="display: inline-flex; width: 20%;" name="month">
                        <?php foreach ($months as $key => $month):?>
                        <option <?php echo (($key+1) == $show_month) ? "selected='selected'" : '' ;?> value="<?php echo ($key+1);?>"><?php echo $month;?></option>
                        <?php endforeach;?>
                    </select>
                    <select id="year" style="display: inline-flex; width: 20%;"  name="year">
                        <?php foreach ($years as $year):?>
                        <option <?php echo ($year == $show_year) ? "selected='selected'" : '' ;?>><?php echo $year;?></option>
                        <?php endforeach;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field_label">Schedule</td>
                <td>
                    <div id="schedule-selector-container"></div>
                </td>
            </tr>
    	</table>
    </div>
    <div id="form_default" class="form_action_section">
        <table width="100%">
            <tr>
                <td class="field_label">&nbsp;</td>
                <td><input class="blue_button" type="submit" name="button" id="button"  value="Search"></td>
            </tr>
        </table>
    </div>
</form>
</div>
<div class="yui-skin-sam">
  <div id="applicant_list_datatable"></div>
</div>
