<script type="text/javascript">
$(function() {
	$("#tree").treeview({
		collapsed: true,
		animated: "fast",
		control:"#sidetreecontrol",
		persist: "location"
	});
})
</script>
<style>
span.folder{z-index:1 !important;}
div.tree-buttons{float:left;}
div.tree-buttons a{}
label#add{margin-right:5px;}
.ui-widget-content .ui-icon{cursor: pointer;}

</style>
<div id="sidetreecontrol">&nbsp;<a href="?#">Collapse All</a> | <a href="?#">Expand All</a> </div><br />
<?php echo $cstructure; ?>