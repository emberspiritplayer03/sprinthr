<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:200px;}
</style>
<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
	
	$(document).ready(function () {
		$('#tabs').tabs({
			select: function (event, ui) {
				location.href = ui.tab.rel;
			}
		});
	});
</script>
<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" >Database</a></li>
      <li><a href="#tabs-2" rel="<?php echo url('settings/policy'); ?>">Policy</a></li>      
	</ul>    
	<div id="tabs-1">		
       <?php include_once('includes/database_settings.php'); ?>
	</div>
	
   <div id="tabs-2">
		<?php echo 'loading...'; ?>
	</div>	
    
   <!-- <div id="tabs-2">
		<div id="c-department"></div>
	</div> -->
    
</div>
<?php include_once('includes/_wrappers.php'); ?>

<script>

</script>