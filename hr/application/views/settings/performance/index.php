<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br /><br />

<?php if($_GET['add_performance']=='true') { ?>
		<div id="performance_form_wrapper" >
<?php }else { ?>
		<div id="performance_form_wrapper" style="display:none" >
<?php } ?>

<?php include 'form/add_performance_form.php'; ?>
</div>
<div class="actions_holder">
<a class="add_button" id="add_performance_button_wrapper" href="#" onClick="javascript:load_add_performance();" ><strong>+</strong><b>Add Performance</b></a>
	<div class="btn-group float-right">	
        <a title="View All" id="btn_viewall" class="btn btn-small" href="javascript:load_performance_datatable();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
        <a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_archive_performance_datatable('nothing');">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>
    </div>
</div>

<div class="clear"></div>
<div class="yui-skin-sam">
	<div id="performance_datatable"></div>
</div>
<div id="performance_wrapper"></div>
<div id="confirmation"></div>
<script>
load_performance_datatable();
$(function() {	  	
	$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
  });
</script>
<input type="hidden" name="performance_id" />
