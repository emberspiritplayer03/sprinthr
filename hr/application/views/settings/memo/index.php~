<script>
    var jq17 = jQuery.noConflict();
</script>
<script>
$(document).ready(function() {	
	$('#withSelectedAction').validationEngine({scroll:false});	
});
function countChecked()
{		
	var inputs     = document.withSelectedAction.elements['dtChk[]'];
	var is_checked = false;
	var cnt        = 0;
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            is_checked = theForm.elements[i].checked;
			if(is_checked){								 
			 	cnt++;
			}
    }
	
	return cnt;

}

function chkUnchk()
{
	var check_uncheck = document.withSelectedAction.elements['check_uncheck'];
	if(check_uncheck.checked == 1) {
		$('#check_uncheck').attr('title', 'Uncheck All');							
		$("#chkAction").removeAttr('disabled');
		var status = 1; 
	} else { 		
		$('#check_uncheck').attr('title', 'Check All');					
		$("#chkAction").attr('disabled',true);
		var status = 0;
	}
	
	var theForm = document.withSelectedAction;
	for (i=0; i<theForm.elements.length; i++) {			
        if (theForm.elements[i].name=='dtChk[]')
            theForm.elements[i].checked = status;
    }
}

</script>
<div class="ui-state-highlight ui-corner-all">
<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span><?php echo $module_title; ?>
</div><br />

	<div id="memo_form_wrapper" style="display:none">
		<div id="memoFormsAjax"></div>
	</div>
<form name="withSelectedAction" id="withSelectedAction">
<input type="hidden" name="memo_with_selected_action" id="memo_with_selected_action" value="" />
<div class="break-bottom inner_top_option">
	<div class="actions_holder">
		<a class="add_button" id="add_memo_button_wrapper" href="javascript:void(0);" onClick="javascript:load_add_memo();" ><strong>+</strong><b>Add Memo</b></a>
		<div class="pull-right">
    		<a title="View All" id="btn_viewall" class="btn btn-small active" href="javascript:load_memo_template_list_dt();">&nbsp;&nbsp;<i class="icon-th-list"></i>&nbsp;&nbsp;</a>
      	<a title="View Archives" id="btn_viewallarchives" class="btn btn-small" href="javascript:load_archive_memo_template_list_dt();">&nbsp;&nbsp;<i class="icon-trash"></i>&nbsp;&nbsp;</a>
    	</div>
    	<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>

<div id="memo_template_list_dt_wrapper" class="dtContainer"></div>    
</form>
<script>
	$(function() { 
		load_memo_template_list_dt(); 
		$('#btn_viewall').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
		$('#btn_viewallarchives').tipsy({trigger: 'focus',html: true, gravity: 's'});	 
	});
</script>

<?php include('includes/modal_forms.php'); ?>