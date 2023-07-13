<div class="container_12">
  <!--<div class="col_1_2">
    <div class="inner">
      <table width="100%" class="formtable">
        <thead>
          <!--<tr>
            <th><strong>Groups or Department</strong>&nbsp;&nbsp;<?php echo $btn_add_department; ?></th>
          </tr>
        </thead>
        <?php if (empty($groups)) : ?>
          <tr>
            <td>
              <center><i>- no record - </i></center>
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($groups as $g) : ?>
            <tr id="<?php echo $g->getId(); ?>-<?php echo $schedule_id; ?>-group">
              <td style="border-bottom:1px solid #cccccc">
                <?php if ($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                  <a class="" href="javascript:void(0)" onclick="javascript:removeStaggeredScheduleMember(<?php echo $g->getId(); ?>, '<?php echo $schedule_id; ?>', 'group')" style="float:right;" title="Remove"><i class="icon-remove"><span class="tooltip" title="Remove"></span></i></a>
                <?php } ?>
                <?php echo $g->getName(); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        <tr id="tr_autocomplete" style="display:none;">
          <td width="100%"><input type="text" id="tr_groups_autocomplete" /></td>
        </tr>
      </table>
    </div>
  </div>-->
  <div class="col_1_2">
    <div class="inner">
      <table width="100%" class="formtable">
        <thead>
          <tr>
            <th bgcolor="#cccccc">
              <div style="float:left"><strong>Employees</strong>&nbsp;&nbsp;<?php echo $btn_add_employees; ?></div>
              <?php if (!empty($employees)) : ?>
                <div class="remove_all_employees_link" style="float:right; font-weight:normal">
                  <?php echo $btn_remove_all_employees; ?>
                </div>
              <?php endif; ?>
            </th>
          </tr>
        </thead>
        <?php if (empty($employees)) : ?>
          <tr>
            <td>
              <center><i>- no record - </i></center>
            </td>
          </tr>
        <?php else : ?>
          <?php foreach ($employees as $e) : ?>
            <tr id="<?php echo $e->getId(); ?>-<?php echo $schedule_id; ?>-employee">
              <td style="border-bottom:1px solid #cccccc">
                <?php if ($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                  <a class="" href="javascript:void(0)" onclick="javascript:removeStaggeredScheduleMember(<?php echo $e->getId(); ?>, '<?php echo $schedule_id; ?>', 'employee')" style="float:right;" title="Remove"><i class="icon-remove"><span class="tooltip" title="Remove"></span></i></a>
                <?php } ?>
                <?php echo $e->getLastname(); ?>, <?php echo $e->getFirstname(); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </table>
    </div>
  </div>
</div>
<script language="javascript">
  $('.tooltip').tipsy({
    gravity: 's'
  });
</script>