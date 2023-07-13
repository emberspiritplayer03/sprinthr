<style>
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#updateGeneratePayslipProgressBar {
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

<div id="myProgress">
  <div id="updateGeneratePayslipProgressBar">5%</div>
</div>
<div>Processing...</div>
<script>
function move_progress_bar_payslip() {
  var elem  = document.getElementById("updateGeneratePayslipProgressBar");   
  var width = 5;
  var interval = 600;

  var id    = setInterval(function(){
                framePayslipProgress();
                //var interval = Math.floor(Math.random() * 2000) + 500;
              }, interval);

  function framePayslip() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 
      elem.style.width = width + '%'; 
      elem.innerHTML = width * 1  + '%';
    }
  }

  function framePayslipProgress() {
    if (width >= 100) {
      clearInterval(id);
    } else {
      width++; 

      var limit_percentage = Math.floor(Math.random() * (98 - 88 + 1) ) + 88;

      if( width <= limit_percentage ) {
        elem.style.width = width + '%'; 
        elem.innerHTML = width * 1  + '%';
      } else {

        $.post(base_url + 'payroll_register/_update_progress_bar_generate_payslip',{}, function(o) {
          if (o.total_frame) {

            if(o.total_frame == 100) {
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

$(document).ready(function() {
  move_progress_bar_payslip();
});

</script>

