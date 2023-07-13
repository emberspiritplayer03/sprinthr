<?php if($permission_action == G_Sprint_Modules::PERMISSION_02) { ?>
<img src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" border="1"  onClick="javascript:loadPhotoDialog();"  /><a class="action_change_photo" href="javascript:void(0);" onClick="javascript:loadPhotoDialog();">[Click to upload]</a>
<?php } else { ?>
<img src="<?php echo $filename;?>?<?php echo $filemtime; ?>" width="140" border="1" />
<?php } ?>