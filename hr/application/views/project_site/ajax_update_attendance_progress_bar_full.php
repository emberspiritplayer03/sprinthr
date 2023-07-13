<style>
#myProgressUpdateAttendance {
  width: 100%;
  background-color: #ddd;
}

#updateAttendanceProgressBar {
  width: 99%;
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

<div id="myProgressUpdateAttendance">
  <div id="updateAttendanceProgressBar">5%</div>
</div>
<div>Processing...</div>
<script>
function move_progress_bar_update_attendance() {
  var elem  = document.getElementById("updateAttendanceProgressBar");   
  var width = 99;
  var interval = 500;

  var id    = setInterval(function(){
                frameProgress();
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

}

$(document).ready(function() {
  move_progress_bar_update_attendance();
});

</script>

