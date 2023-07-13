 <select name="department_id" class="curve" id="department" onchange="javascript:loadPosition();">
        <option value="all">-All Department-</option>
        <?php foreach($department as $key=>$value) { ?>
        <option value="<?php echo $value['id']; ?>"><?php echo $value['department_name']; ?></option>
        <?php } ?>
    </select>