<ul class="sub-mod-list">
  <?php foreach($sub_modules as $key => $sub_mod){ ?>
  <?php $sub_mod_actions = $sub_mod['actions']; ?>
    <li>
      <div class="mod-caption"><?php echo $sub_mod['caption']; ?></div>
      <div class="mod-input">
          : <select class="sub-mod-action" name="mod[<?php echo $mod_index; ?>][<?php echo $key; ?>]">
            <?php foreach($sub_mod_actions as $action){ ?>
              <option value="<?php echo $action; ?>"><?php echo $action; ?></option>
            <?php } ?>
          </select>
      </div>
      <div class="clear"></div>
    </li>
  <?php } ?>
</ul>
