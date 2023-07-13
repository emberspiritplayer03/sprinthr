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
        <th scope="col">Error</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($has_error):?>
    <?php foreach ($errors as $error):?>
        <tr>
            <td><?php echo $error->getEmployeeName();?> (<?php echo $error->getEmployeeCode();?>)</td>
            <td><?php echo Tools::convertDateFormat($error->getDate());?></td>
            <td><?php echo $error->getMessage();?></td>
        </tr>
    <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="3" style="text-align: center"><i>Yahoo! There are no errors found.</i></td>
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