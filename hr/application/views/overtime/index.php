<table class="formtable" width="100%">
<thead>
  <tr>
    <th width="200"><strong>Period</strong></th>    
    <th width="180"><strong>Action</strong></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
</thead>
  <?php foreach ($periods as $period):?>
  <tr>
    <td width="160" class="payslip_period"><strong><a href="<?php echo url('overtime/period?from='. $period['start'] .'&to='. $period['end']  . '&hpid=' . Utilities::encrypt($period['id']));?>"><?php echo Tools::getGmtDate('M j', strtotime($period['start']));?> - <?php echo Tools::getGmtDate('M j', strtotime($period['end']));?></a></strong></td>
    <td class="vertical-middle"><div id="dropholder"><a class="dropbutton" href="<?php echo url('overtime/period?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>"><i class="icon-zoom-in icon-fade"></i> View List</a></div></td>
    <td class="vertical-middle"><i class="icon-download-alt icon-fade"></i> Download:&nbsp;&nbsp;&nbsp;<a class="btn btn-mini" href="<?php echo url('overtime/download_ot?from='. $period['start'] .'&to='. $period['end'] . '&hpid=' . Utilities::encrypt($period['id']));?>">Overtime</a></td>
    <td class="vertical-middle">
		<?php if($period['is_lock'] == G_Cutoff_Period::NO){ ?>
             Import:&nbsp;&nbsp;&nbsp;<a class="btn btn-mini" href="javascript:void(0);" onclick="javascript:importOvertimePending('<?php echo $eid; ?>')">Overtime</a>
        <?php }else{ ?>    	
        <?php } ?>
    </td>
	<td class="vertical-middle text-right">
		<?php if($period['is_lock'] == G_Cutoff_Period::NO){ ?>
    
		<?php }else{ ?>
            <div style="margin-right:17px;font-size:13px;">
            <a class="btn disabled active btn-mini" href="#"><i class="icon-lock disabled"></i> Period Locked</a>
        </div>
        <?php } ?>
    </td>
</tr>
  <?php endforeach;?>
</table>
