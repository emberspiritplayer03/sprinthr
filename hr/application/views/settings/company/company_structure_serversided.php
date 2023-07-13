<script type="text/javascript">
function initTrees() {
	$("#company_structure").treeview({		
		control: "#sidetreecontrol",		
		url : base_url + 'benchmark_bio/_tree_view_asyn',	
		// add some additional, dynamic data and request with POST			
		ajax: {			
			type: "post"
		},		
		//unique: true,
		//toggle: function()
		//{
				//$.cookie("navigationtree", this.id); 
				//alert($.cookie("navigationtree"));
		//},
		persist: "cookie",
   		cookieId: "navigationtree"

	});
}


$(document).ready(function(){
	    var current = $('ul.treeview').find("a").filter(function() {return this.href.toLowerCase() == location.href.toLowerCase(); });
    current.parent().addClass("selected").end().addClass("selected").parents("ul, li").add( current.next() ).show();
	
	initTrees();
	
	$('#treecontrol a').hide();
	
	$('#expandAll').click(function() {        
        $('#treecontrol a:eq(2)').click();    
    });
    
    $('#collapseAll').click(function() {
        $('#treecontrol a:eq(0)').click();        
    });
		
});
</script>
<style>
span.folder{z-index:1 !important;}
div.tree-buttons{float:left;}
div.tree-buttons a{}
label#add{margin-right:5px;}
.ui-widget-content .ui-icon{cursor: pointer;}
</style>
<div id="sidetreecontrol">&nbsp;<a href="?#" id="collapseAll">Collapse All</a> | <a href="?#" id="expandAll">Expand All</a> </div><br />
<ul id="company_structure" class="filetree treeview-famfamfam"></ul>
<?php //echo $cstructure; ?>