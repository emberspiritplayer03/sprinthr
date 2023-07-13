<ul class="mod-list">
  <li class="main-module enable-disable-module"><label class="checkbox"><input type="checkbox" <?php echo $hr_checked; ?> id="enable-hr-module" /><b>Enable HR Module</b></label></li>
  <?php     
    $mod_index = $hr_key;
    foreach($mod_hr as $key => $mod){     
      $actions     = $mod['actions'];
      $sub_modules = $mod['children'];   
      $add_class   = ""; 
      if( $hr_data[$key] == $no_access ){
        $add_class = "remove-sub-element";
      }         
  ?>    
    <li class="main-module hr-module-list">
      <div class="mod-caption"><?php echo $mod['caption']; ?></div>
      <div class="mod-input">
          : <select class="mod-action mod-input" name="mod[<?php echo $hr_key; ?>][<?php echo $key; ?>]">
            <?php foreach($actions as $action){ ?>
              <option <?php echo($hr_data[$key] == $action ? 'selected="selected"' : ''); ?> value="<?php echo $action; ?>"><?php echo $action; ?></option>
            <?php } ?>
          </select>
      </div>
      <div class="clear"></div>
    </li>

    <?php if( !empty($sub_modules) ){ ?>
      <li class="sub-module hr-module-list <?php echo $add_class; ?>"><?php include('_sub_edit_mod.php'); ?></li>
    <?php } ?>

  <?php } ?>
</ul>