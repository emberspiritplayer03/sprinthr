<style>
div#version-info-container hr{margin-top:1px;}
div#version-info-container p{margin-bottom:2px;}
.info-label{display:block;padding:3px;background-color:#9acee9;}
p.version-label{font-size:14px;margin-top:20px;}
ul.mod-description-list{list-style:none;}
ul.mod-name-list{margin-left:32px;}
</style>

<script>
$(function(){
	var addon_key = "<?php echo $addon_key; ?>";

	$(".btn-addon-activate").click(function(){		
       activateAddon(addon_key);
    });

    $(".btn-addon-deactivate").click(function(){		
       deactivateAddOn(addon_key);
    });
   
});
</script>
<?php if( !empty($features) ){ ?>
	<p class="version-label">Released Date : <b><?php echo $released_date; ?></b></p>
	<hr />

	<p class="info-label">Features</p>
	<ul class="mod-name-list">
		<?php foreach($features as $feature){ ?>        	    
			<li>
				<?php echo $feature; ?>
			</li>    		    
		<?php } ?>
	</ul>
	<?php if($is_enabled == $addon_enabled){ ?>
		<a class="btn btn-primary pull-right btn-addon-deactivate" href="javascript:void(0);" style="margin-left:8px;">Deactivate</a>	
	<?php }else{ ?>
		<a class="btn btn-primary pull-right btn-addon-activate" href="javascript:void(0);" style="margin-left:8px;">Activate</a>	
	<?php } ?>
	<div class="clear"></div>
<?php } ?>