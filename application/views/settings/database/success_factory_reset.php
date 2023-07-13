<style>
div.action-buttons{ padding-right:10px;padding-top:10px; width:50%;}
div#tabs{min-height:200px;}
</style>
<script type="text/javascript">

</script>
<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />

<?php if($is_success) { ?>
	<div class="alert alert-success"><i class="icon icon-ok"></i> <strong >You have successfully reset and loaded the default values.</strong></div>
	<a href="<?php echo url('login');?>" class="btn">Configure Now</a>
<?php }else{ ?>
	<div class="alert alert-danger"><i class="icon icon-remove"></i> <strong >Factory reset is currently disabled.</strong> </div>
<?php } ?>