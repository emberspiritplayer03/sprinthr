<style>
#loading {
   background-color: #3A87AD;
    border-radius: 10px;
    bottom: -53px;
    color: #fff;
    font-size: 14px;
    left: 305px;
    padding: 12px;
    position: relative;
    top: 275px;
    width: 17%;
    z-index: 9999;
}
</style>
<script>
var jq17 = jQuery.noConflict();
$(function(){

  jq17('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      defaultDate: '<?php echo date($year."-01-01");?>',
      editable: false,
      eventLimit: true, // allow "more" link when too many events
      /*viewDisplay: function getDate(date) {
                var lammCurrentDate = new Date();
                var lammMinDate = new Date(lammCurrentDate.getFullYear(), lammCurrentDate.getMonth(), 1, 0, 0, 0, 0);

                if (date.start <= lammMinDate) {
                    $(".fc-button-prev").css("display", "none");
                }
                else {
                    $(".fc-button-prev").css("display", "inline-block");
                }
            },*/
      viewRender: function(view){
        var minDate = '<?php echo date($year."-01-01");?>';

        var calendar_date = jq17("#calendar").fullCalendar('getDate');
        var calendar_year = calendar_date._d.getFullYear();
        var selected_year = jq17("#selected_year").val();
      
        if (calendar_year != selected_year){
            jq17('#calendar').fullCalendar('gotoDate', minDate);
        }

      },

      events: {
        url: base_url+'holiday/_load_holiday_data?year=<?php echo $year;?>',
        error: function() {
          jq17('#script-warning').show();
        }
      },
      loading: function(bool) {
         jq17('#loading').html(loading_image + " Loading Holiday");
         jq17('#loading').toggle(bool);
      }
  }); 
})
</script>
<br><h2><?php echo $year;?> Holidays</h2>
<div class="container_12" style="padding:0; margin-top:0; display:block;">
  <div style="height:0px !important;" class="calendar-loading-container">
      <div id='loading'></div>
  </div>
  <div id='calendar'></div>
</div>

