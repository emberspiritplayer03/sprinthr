 <div class="container_12">
<div class="col_1_2">
	<div class="inner">
    <table width="100%" class="formtable">
      <thead>
          <tr>
            <th><strong>Groups or Department</strong> <a class="ui-icon ui-icon-circle-plus tooltip" href="javascript:void(0)" onclick="javascript:assignScheduleGroups('<?php echo $schedule_id;?>')" style="float:right" title="Add Groups or Department"></a></th>
          </tr>
      </thead>        
		<?php if (empty($groups)):?>
        <tr>
            <td>
            <center><i>- no record - </i></center>
            </td>
        </tr>
        <?php else:?>
        <?php foreach ($groups as $g):?>
          <tr id="<?php echo $g->getId();?>-<?php echo $schedule_id;?>-group">
          <td style="border-bottom:1px solid #cccccc"><?php echo $g->getName();?> <a class="ui-icon ui-icon-close tooltip" href="javascript:void(0)" onclick="javascript:removeScheduleMember(<?php echo $g->getId();?>, '<?php echo $schedule_id;?>', 'group')" style="float:right" title="Remove"></a></td>
          </tr>
        <?php endforeach;?>
      	<?php endif;?>
      	<tr id="tr_autocomplete" style="display:none;"><td width="100%"><input type="text" id="tr_groups_autocomplete" /></td></tr>
    </table>
    </div>
</div>
<div class="col_1_2">
	<div class="inner">    
    <table width="100%" class="formtable">
      <thead>
      	<tr>
            <th bgcolor="#cccccc"><strong>Employees</strong> <a class="ui-icon ui-icon-circle-plus tooltip" href="javascript:void(0)" onclick="javascript:assignScheduleEmployees('<?php echo $schedule_id;?>')" style="float:right" title="Add Employees"></a></th>
      	</tr>
      </thead>      
		<?php if (empty($employees)):?>
        <tr>
        	<td>
         	<center><i>- no record - </i></center>
            </td>
        </tr>
        <?php else:?>
		<?php foreach ($employees as $e):?>
        <tr id="<?php echo $e->getId();?>-<?php echo $schedule_id;?>-employee">
          <td style="border-bottom:1px solid #cccccc"><?php echo $e->getLastname();?>, <?php echo $e->getFirstname();?> <a class="ui-icon ui-icon-close tooltip" href="javascript:void(0)" onclick="javascript:removeScheduleMember(<?php echo $e->getId();?>, '<?php echo $schedule_id;?>', 'employee')" style="float:right" title="Remove"></a></td>
          </tr>
        <?php endforeach;?>
        <?php endif;?>
    </table>
    </div>
</div>
</div>
<script language="javascript">		
$('.tooltip').tipsy({gravity: 's'});
</script>