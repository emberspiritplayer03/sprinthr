<table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
    <thead>
    <tr>
        <th width="70" scope="col">Employee</th>
        <th width="30" scope="col">Date</th>
        <th width="100" scope="col">Error</th>
        <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
            <th width="64" scope="col">Action</th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php if ($has_error):?>
    <?php foreach ($errors as $error):?>
        <tr>
            <td><?php echo $error->getEmployeeName();?> (<?php echo $error->getEmployeeCode();?>)</td>
            <td><?php echo Tools::convertDateFormat($error->getDate());?></td>
            <td><?php echo $error->getMessage();?></td>
            <?php if($permission_action == Sprint_Modules::PERMISSION_02) { ?>
                <td><?php echo G_Overtime_Error_Helper::getFixActionString($error);?></td>
            <?php } ?>
        </tr>
    <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="4" style="text-align: center"><i>Yahoo! There are no errors found.</i></td>
        </tr>
    <?php endif;?>
    </tbody>
</table>

<br>
<div style="text-align: center"><?php echo $pager_links;?></div>

<script language="javascript">
    //$('.tooltip').tipsy({gravity: 's'});
    //$('.info').tipsy({gravity: 's'});
</script>