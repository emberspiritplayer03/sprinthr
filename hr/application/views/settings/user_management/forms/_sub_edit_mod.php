<ul class="sub-mod-list">
  <?php 
    foreach($sub_modules as $key => $sub_mod){ 
    $sub_mod_actions = $sub_mod['actions']; 
    if( $mod_index == $hr_key ){
      $mod_compare_action = $hr_data[$key];
    }elseif( $mod_index == $dtr_key ){
      $mod_compare_action = $dtr_data[$key];
    }elseif( $mod_index == $employee_key ){
      $mod_compare_action = $employee_data[$key];
    }else{
      $mod_compare_action = $payroll_data[$key];
    }
  ?>
    <li>
      <div class="mod-caption"><?php echo $sub_mod['caption']; ?></div>
      <div class="mod-input">
          : <select class="sub-mod-action" name="mod[<?php echo $mod_index; ?>][<?php echo $key; ?>]">
            <?php foreach($sub_mod_actions as $action){ ?>
              <option <?php echo($mod_compare_action == $action ? 'selected="selected"' : ''); ?> value="<?php echo $action; ?>"><?php echo $action; ?></option>
            <?php } ?>
          </select>
      </div>
      <div class="clear"></div>
    </li>
  <?php } ?>
</ul>
