<ul class="mod-list">
  <li class="main-module enable-disable-module"><label class="checkbox"><input type="checkbox" <?php echo $payroll_checked; ?> id="enable-payroll-module" /><b>Enable Payroll Module</b></label></li>
  <?php     
    $mod_index = $payroll_key;    
    foreach($mod_payroll as $key => $mod){ 
      $actions     = $mod['actions'];
      $sub_modules = $mod['children'];  
      $add_class   = ""; 
      if( $payroll_data[$key] == $no_access ){
        $add_class = "remove-sub-element";
      }           
  ?>
    <li class="main-module payroll-module-list">
      <div class="mod-caption"><?php echo $mod['caption']; ?></div>
      <div class="mod-input">
          : <select class="mod-action mod-input" name="mod[<?php echo $payroll_key; ?>][<?php echo $key; ?>]">
            <?php foreach($actions as $action){ ?>
              <option <?php echo($payroll_data[$key] == $action ? 'selected="selected"' : ''); ?> value="<?php echo $action; ?>"><?php echo $action; ?></option>
            <?php } ?>
          </select>
      </div>
      <div class="clear"></div>
    </li>

    <?php if( !empty($sub_modules) ){ ?>
      <li class="sub-module payroll-module-list <?php echo $add_class; ?>"><?php include('_sub_edit_mod.php'); ?></li>
    <?php } ?>

  <?php } ?>
</ul>