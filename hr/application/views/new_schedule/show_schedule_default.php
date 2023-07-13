<style>
.cal-today {
    font-weight:bold;
    //background-color:#00FFFF;
    text-align:center;
}
.cal-table {
    width:100%;
    background-color:#EFEFEF;
    border:1px solid #CCCCCC;
    border-collapse:collapse;
}
.cal-table .cal-month, .cal-year {
    color:black;
}
.cal-table tbody {
    background-color:#FFFFFF;
}
.cal-table td {
    border:1px solid #CCCCCC;
}
.cal-day {
    color:black;
    text-align:center;
}
.cal-table thead {
    text-align:center;
    background-color:#EFEFEF;
}
.cal-day-title {
   text-align:center;
}
.cal-table .cal-title {
   text-align:center;
}
.cal-week {
}
.cal-has-event {
    background-color:black;
    text-align:center;
    color:white;
}
ul.rd-calendar-ul{list-style: none;}
ul.rd-calendar-ul li{display: inline-block;width: 45%;margin:10px;height:288px;}
</style>

<script>
  $(function(){   
    $(".btn-copy-default-restday-employee").click(function(){
      var eid = $("#eid").val();      
      copyDefaultRestdayToAllEmployees(eid);
    });
  });
</script>

<script>
  $(function(){
    $(".btn-remove-group-schedule").click(function(){
      var eid  = $(this).attr("data-index");
      var geid = $("#geid").val();
      removeGroupSchedule(geid, eid);
    });
  });
</script>
<div id="detailscontainer" class="detailscontainer_blue view_schedule_holder">
    <div id="applicant_details">
    	<div id="applicant_details">    
            <div id="form_main">
                <h2 class="field_title blue" style="font-size:22px;"><i class="icon-list-alt icon-fade vertical-middle"></i> <?php echo $schedule_name; ?></h2>
                <div class="form_separator"></div>
                <div class="ui-state-highlight ui-corner-all" style="font-weight:normal; margin-bottom:10px;">
                    <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                    <i>This is the default schedule. All employees without assigned schedule will be using this default schedule.</i>
                </div>    
                <div id="status_message"></div>
                <div class="view_schedule"><?php echo $schedule_date_time; ?></div>
                <div id="form_default" class="yellow_form_action_section form_action_section yellow_section" align="center">
                    <?php echo $btn_edit_schedule;?>
                </div>
            </div>
        </div>                
    </div>    
</div>
<div>
    <span style="font-size:18px; font-weight:bold">Rest Days</span> (Click day to add/remove Rest Day)    
    <a class="pull-right btn btn-copy-default-restday-employee" href="javascript:void(0);">Copy restdays to all employees</a>

    <div class="ui-state-highlight ui-corner-all" style="font-weight:normal; margin-bottom:10px;margin-top:10px;">
        <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        <i>Note : Below will be the default Restday which will be applied to all employees without schedules.</i>
    </div>  

    <ul class="rd-calendar-ul">
        <?php echo $calendar;?>
    </ul>
</div>

<script>	
//$('.tooltip').tipsy({gravity: 's'});
</script>