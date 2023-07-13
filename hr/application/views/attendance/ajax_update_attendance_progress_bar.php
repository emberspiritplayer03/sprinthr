<style>
#myProgressUpdateAttendance {
  width: 100%;
  background-color: #ddd;
}

#updateAttendanceProgressBar {
  width: 5%;
  height: 30px;
  background-color: #0d76ac;
  text-align: center;
  line-height: 30px;
  color: white;
}

.ui-dialog-titlebar-close {
  /*display: none;*/
}

</style>

<script>
function move_progress_bar_update_attendance() {

  var elem     = document.getElementById("updateAttendanceProgressBar");   
  var width    = 5;
  var interval = 5000;
  //var interval = Math.floor(Math.random() * 2000) + 500; //500;

  var id    = setInterval(function(){
                frameUpdateAttendanceProgress();
              }, interval);

  function frameProgress() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
      elem.innerHTML = width * 1 + '%';
    }
  }

  function frameProgressSync() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 

      var limit_percentage = Math.floor(Math.random() * (92 - 88 + 1) ) + 83;

      if( width <= limit_percentage ) {
        elem.style.width = width + '%'; 
        elem.innerHTML = width * 1  + '%';
      } else {

      }

    }

  }

  function frameUpdateAttendanceProgress() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 

      var limit_percentage = Math.floor(Math.random() * (92 - 88 + 1) ) + 83;

      if( width <= limit_percentage ) {
        elem.style.width = width + '%'; 
        elem.innerHTML = width * 1  + '%';
      } else {

        $.get(base_url + 'attendance/_update_progress_bar_update_attendance',{}, function(o) {
          if (o.total_frame) {

            if(o.total_frame >= 100) {
              clearInterval(id);
            }

            width            = o.total_frame; 
            elem.style.width = width + '%'; 
            elem.innerHTML   = width * 1  + '%';

          }
        },'json');

      }

    }

  }

}

/*$(document).ready(function() {*/
$(function(){
  move_progress_bar_update_attendance();
});

</script>

<div id="myProgressUpdateAttendance">
  <div id="updateAttendanceProgressBar">5%</div>
</div>
<div>Processing...</div>

