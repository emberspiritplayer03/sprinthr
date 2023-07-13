<table id="box-table-a" class="formtable" summary="Schedule" style="margin:0px">
    <thead>
    <tr>
        <th width="70" scope="col">Employee</th>
        <th width="30" scope="col">Date</th>
        <th width="60" scope="col">Overtime</th>
        <th width="30" scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($has_overtime):?>
    <?php foreach ($overtime as $o):?>
        <tr>
            <td><?php echo G_Employee_Helper::getEmployeeNameWithCodeById($o->getEmployeeId());?></td>
            <td><?php echo Tools::convertDateFormat($o->getDate());?></td>
            <td><?php echo Tools::timeFormat($o->getTimeIn());?> - <?php echo Tools::timeFormat($o->getTimeOut());?></td>
            <td><?php echo G_Overtime_Helper::getOvertimeActionString($o);?></td>
        </tr>
    <?php endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="4" style="text-align: center"><i>No records found.</i></td>
        </tr>
    <?php endif;?>
    </tbody>
</table>

<script language="javascript">
    //$('.tooltip').tipsy({gravity: 's'});
    //$('.info').tipsy({gravity: 's'});
</script>