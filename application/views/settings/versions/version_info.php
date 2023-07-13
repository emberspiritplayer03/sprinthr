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
	var version = "<?php echo $version; ?>";

	$(".btn-app-update").click(function(){		
       updateDatabase(version);
    });

    $(".btn-tdd-update").click(function(){
    	updateTddDatabase(version);
    });
});
</script>

<p class="version-label">Released Date : <b><?php echo $release_date; ?></b></p>
<hr />

<?php if( !empty($info_new_mod) ){ ?>
	<p class="info-label">New Modules</p>
	<?php foreach($info_new_mod as $key => $value){ ?>        
	    <ul class="mod-name-list">
			<li>
				<b><?php echo $key; ?></b>
				<ul class="mod-description-list">
					<?php
						$description = explode("|", $value);
						foreach( $description as $d ){
					?>    				
					<li><?php echo $d; ?></li>
				<?php } ?>  
				</ul>  			
			</li>    	
	    </ul>
	<?php } ?>
<?php } ?>

<?php if( !empty($info_fixes_mod) ){ ?>
	<p class="info-label">Fixes / Bugs</p>
	<?php foreach($info_fixes_mod as $key => $value){ ?>        
	    <ul class="mod-name-list">
			<li>
				<b><?php echo $key; ?></b>
				<ul class="mod-description-list">
					<?php
						$description = explode("|", $value);
						foreach( $description as $d ){
					?>    				
					<li><?php echo $d; ?></li>
				<?php } ?>  
				</ul>  			
			</li>    	
	    </ul>
	<?php } ?>
<?php } ?>
<a class="btn btn-primary pull-right btn-app-update" href="javascript:void(0);" style="margin-left:8px;">Update Main Database</a>
<!-- <a class="btn btn-primary pull-right btn-tdd-update" href="javascript:void(0);">Update TDD Database</a> -->
<div class="clear"></div>