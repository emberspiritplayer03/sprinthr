<script>
$(function(){
  $('#move-deduction-form').ajaxForm({
      success:function(o) {
        //var query = window.location.search;
            //dialogOkBox(o.message,{ok_url:'deductions/hold'+ query});
            dialogOkBox(o.message,{});
            load_hold_deductions_list_dt(o.pid,o.from,o.to)
      },
      dataType:'json',
      beforeSubmit: function() {
        if ($('#action').val() == '') {         
          return false; 
        }
        showLoadingDialog('Processing...');
        return true;
      }
  }); 
});
</script>
<style>.ui-dialog .ui-dialog-content{ padding:10px 10px 0px 10px; }</style>
<style>
table tr th, table tr td { 
  border-bottom : none !important;
}
.dataTables_paginate {
  float:none !important;
}

td {
  border: none !important;
  border-top: none !important;
}
</style>
<form id="move-deduction-form" action="<?php echo url("deductions/_move_excluded_deduction");?>" method="post">
  <input type="hidden" id="eid" name="eid" value="<?php echo Utilities::encrypt($eed->getId());?>">
  <input type="hidden" id="from" name="from" value="<?php echo $from;?>">
  <input type="hidden" id="to" name="to" value="<?php echo $to;?>">
  <input type="hidden" id="pid" name="pid" value="<?php echo $pid;?>">
  <table  style="border:none; width:100%" >  
      <tr>
          <td>Cutoff Period</td>
          <td>
              <select id="select_payroll_period" name="select_payroll_period">
                <?php foreach($cutoff_arr_data as $key => $value) {  ?>
                  <option value="<?php echo $value['cutoff_id'];?>"><?php echo $value['label'];?></option>
                <?php } ?>
              </select>
          </td>             
      </tr>
      <tr>
          <td></td>
          <td>
           
            <input class="blue_button" type="submit" value="Submit">
          </td>
      </tr>
  </table>
</form>

