<div class="div_table_border section_container">
<table class="formtable" width="100%" border="0">
<thead>
	<th width="35%">Name</th>
    <th width="45%">Payday Breakdown</th>
    <th width="20%">Status</th>
</thead>
<?php foreach($deductions as $d): ?>
<?php $b = explode(':',$d->getBreakdown()); ?>
  <tr>
    <td><strong><?php echo $d->getName(); ?></strong></td>
    <td><?php echo $b[0] . '% <small class="dark-gray">(1st Cut-Off)</small> : ' . $b[1] . '% <small class="dark-gray">(2nd Cut-Off)</small>'; ?>&nbsp;<a class="link_option" href="javascript:void(0)" onclick="javascript:editDeductionBreakdown(<?php echo "'".Utilities::encrypt($d->getId())."'";?>);" title="Edit"><i class="icon-edit"></i> Edit</a></td>
    <td><?php echo ($d->getIsActive() == G_Settings_Deduction_Breakdown::YES ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Inactive</span>'); ?>
    <?php if($d->getIsActive() == G_Settings_Deduction_Breakdown::YES) { ?>
    <a class="link_option" href="javascript:void(0)" onclick="javascript:_deactivateDeductionBreakdown('<?php echo Utilities::encrypt($d->getId());?>')" title="Deactivate"><i class="icon-minus-sign"></i> Deactivate</a>
    <?php } else { ?>
    <a class="link_option" href="javascript:void(0)" onclick="javascript:_activateDeductionBreakdown('<?php echo Utilities::encrypt($d->getId());?>')" title="Activate"><i class="icon-ok-sign"></i> Activate</a>
    <?php } ?>
    </td>
    <!--<td valign="middle">
    	<div class="i_container">
            <ul class="dt_icons" style="margin:0 42px;"> 
            	<li>
                	<a id="tipsy" class="ui-icon ui-icon-pencil" href="javascript:void(0)" onclick="javascript:editDeductionBreakdown(<?php echo "'".Utilities::encrypt($d->getId())."'";?>);" title="Edit"></a>
                </li>
            	<?php //if($d->getIsActive() == G_Settings_Deduction_Breakdown::YES) { ?> 
                	<li>   
                    	<a id="tipsy" class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:_deactivateDeductionBreakdown('<?php echo Utilities::encrypt($d->getId());?>')" title="Deactivate"></a>
                    </li>
                <?php //} else { ?>
                	<li>
	                    <a id="tipsy" class="ui-icon ui-icon-check" href="javascript:void(0)" onclick="javascript:_activateDeductionBreakdown('<?php echo Utilities::encrypt($d->getId());?>')" title="Activate"></a>
                    </li>
                <?php //} ?>                               
            </ul>
        </div>
    </td>-->
  </tr>
<?php endforeach; ?>
</table>
</div>
<script>
$('.dt_icons #tipsy').tipsy({gravity: 's'});
</script>
