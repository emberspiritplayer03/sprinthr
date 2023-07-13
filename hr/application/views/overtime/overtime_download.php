<?php ob_start();?>
<style type="text/css">
.font-size {
	font-size: x-small;
}
</style>

<table width="100%" border="1">
    <thead>
    <tr>
        <th scope="col">Employee</th>
        <th scope="col">Date</th>
        <th scope="col">Overtime</th>
        <th scope="col">Reason</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($has_overtime):?>
    <?php foreach ($overtime as $o):?>
        <tr>
            <td><?php echo G_Employee_Helper::getEmployeeNameWithCodeById($o->getEmployeeId());?></td>
            <td><?php echo Tools::convertDateFormat($o->getDate());?></td>
            <td><?php echo Tools::timeFormat($o->getTimeIn());?> - <?php echo Tools::timeFormat($o->getTimeOut());?></td>
            <td><?php echo $o->getReason(); ?></td>
        </tr>
    <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="3" style="text-align: center"><i>No records found.</i></td>
        </tr>
    <?php endif;?>
    </tbody>
</table>

<?php
header("Content-type: application/x-msexcel;charset=UTF-8"); //tried adding  charset='utf-8' into header
header("Content-Disposition: attachment; filename=$filename");
header("Content-Disposition: attachment;filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
?>