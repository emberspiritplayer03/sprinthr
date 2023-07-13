<div class="formtable">
  <div class="inner">
    <table width="100%" class="formtable">
      <thead>
        <tr>
          <!--<th bgcolor="#cccccc">
            <div style="float:left"><strong>Groups</strong>&nbsp;&nbsp;<?php //echo $btn_add_employees; 
                                                                        ?></div>
          </th>-->
          <th valign="top" width="2%">
							<input type="checkbox" id="chkAll" name="chkAll" onchange="chkAll();" original-title="Check All">
					</th>
          <th>
            <div style="float:left"><strong>Employees</strong>&nbsp;&nbsp;</div>
          </th>
          <th>
            <div style="float:left"><strong>Schedule</strong>&nbsp;&nbsp;</div>
          </th>
          <th>
            <div style="float:left"><strong>Time IN</strong>&nbsp;&nbsp;</div>
          </th>
          <th>
            <div style="float:left"><strong>Time OUT</strong>&nbsp;&nbsp;</div>
          </th>
          <th>
            <div style="float:left"><strong>Action</strong>&nbsp;&nbsp;</div>
          </th>
        </tr>
      </thead>
      <tbody id="set_employee_staggered_schedule_dt_wrapper"></tbody>
    </table>
  </div>
</div>

<script language="javascript">
  $('.tooltip').tipsy({
    gravity: 's'
  });

  function checkUncheck() {
    if ($('input[name="dtrChk[]"]:checked').length > 0) {
      $('#chkAction').attr('disabled', false);
      if ($('input[name="dtrChk[]"]:checked').length == $('input[name="dtrChk[]"]').length) {
        $('#chkAll').attr('checked', true);
      }
    } else {
      $('#chkAction').attr('disabled', true);
    }
  }

  function chkAll() {
    if ($('#chkAll:checked').length) {
      $('#chkAction').attr('disabled', false);
      $('input[name="dtrChk[]"]').attr('checked', true);
    } else {
      $('#chkAction').attr('disabled', true);
      $('input[name="dtrChk[]"]').attr('checked', false);
    }
  }

  load_set_employee_staggered_schedule_dt();
</script>