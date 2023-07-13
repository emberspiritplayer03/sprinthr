<?php echo $btn_add_employees; ?>
<script>
  load_employee_list_dt();
</script>

<div class="dt_top_nav">
  <!--
					<select name="chkAction" id="chkAction" onchange="javascript:withSelectedLogs(this.value);" disabled="disabled">
						<option value="">With Selected:</option>
						<option value="update">Batch Update</option>
						<option value="delete">Delete</option>
					</select>-->
</div>
<div class="formtable">
  <div class="inner">
    <table width="100%" class="formtable">
      <thead>
        <tr>
          <!--<th bgcolor="#cccccc">
            <div style="float:left"><strong>Groups</strong>&nbsp;&nbsp;<?php //echo $btn_add_employees; 
                                                                        ?></div>
          </th>-->
          <th valign="top" width="20%">
            <strong>Employees</strong>&nbsp;&nbsp;
          </th>
          <th valign="top" width="20%">
            <strong>Schedule</strong>&nbsp;&nbsp;
          </th>
          <th valign="top" width="20%">
            <strong>Position</strong>&nbsp;&nbsp;
          </th>
          <th valign="top" width="20%">
            <strong>Department</strong>&nbsp;&nbsp;
          </th>
          <th valign="top" width="20%">
            <strong>Project Site</strong>&nbsp;&nbsp;
          </th>
          <th>
            <strong>Action</strong>&nbsp;&nbsp;
          </th>
        </tr>
      </thead>
      <tbody id="employee_list_dt_wrapper">
        
      </tbody>
    </table>
  </div>
</div>
<div id="status_message"></div>
<div id="schedule_members_list"></div>
<script language="javascript">
  $('.tooltip').tipsy({
    gravity: 's'
  });
</script>