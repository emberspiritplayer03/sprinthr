<ul class="mod-list">
  <li class="main-module enable-disable-module"><label class="checkbox"><input type="checkbox" checked="checked" id="enable-audit_trail-module" /><b>Enable Audit Trail</b></label></li>
  <?php     
    $mod_index = $audit_trail_key;
    foreach($mod_audit_trail as $key => $mod){ 
      $actions     = $mod['actions'];
      $sub_modules = $mod['children'];            
  ?>
    <li class="main-module dtr-module-list">
      <div class="mod-caption"><?php echo $mod['caption']; ?></div>
      <div class="mod-input">
          : <select class="mod-action mod-input" name="mod[<?php echo $audit_trail_key; ?>][<?php echo $key; ?>]">
            <?php foreach($actions as $action){ ?>
              <option value="<?php echo $action; ?>"><?php echo $action; ?></option>
            <?php } ?>
          </select>
      </div>
      <div class="clear"></div>
    </li>

    <?php if( !empty($sub_modules) ){ ?>
    <li class="sub-module dtr-module-list"><?php include('_sub_mod.php'); ?></li>
    <?php } ?>

  <?php } ?>
</ul>