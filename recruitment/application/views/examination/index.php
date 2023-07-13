<script>
$("#exam_form").validationEngine({scroll:false});
$('#exam_form').ajaxForm({
	success:function(o) {
		closeDialog('#' + DIALOG_CONTENT_HANDLER);	
		if(o==0) {		
			dialogOkBox('Invalid Exam Code',{dialog_id: '#summary_dialog',ok_url: 'examination'});	
		}else if(o==-1) {
			dialogOkBox('Exam Code Already Done',{dialog_id: '#summary_dialog',ok_url: 'examination'});
		}else {		
			$.post(base_url + 'examination/_get_examination_summary',{applicant_examination_id:o},
			function(summary){
				subDialogOkBox(summary,{title: "Applicant Exam",button_caption:"Start",dialog_id: '#summary_dialog',width:450,height:260,icon: 'no-icon',ok_url: 'examination/start_exam?examination='+o});			
			});
		}		
	},
	beforeSubmit:function() {
		showLoadingDialog('Checking...');	
	
	}
});

 function submitform(){ 
 	$('#button').click();
 }
</script>
<div id="detailscontainer" class="detailscontainer_blue">	
    <div id="applicant_details">
    	<div id="applicant_details">        	
            <div id="applicant_details">
                <div id="form_main">
                    <div id="applicant_details" style="padding:5px 0;">
                        <h2 class="field_title blue" style="font-size:22px;"><i class="icon-list-alt icon-fade vertical-middle"></i> <?php echo $title; ?></h2>
                        <div class="form_separator"></div>
                        <form id="exam_form" name="exam_form" method="post" action="<?php echo url('examination/_verify_exam_code'); ?>">
                        <input type="hidden" name="token" value="<?php echo $token; ?>" />
                          &nbsp;<strong>Exam Code:</strong>&nbsp;&nbsp;&nbsp;<input type="text" class="validate[required] text-input text" name="exam_code" id="submit_btn" value="<?php echo $ecode; ?>" id="exam_code" />&nbsp;<button type="submit" class="curve blue_button" name="button" id="button"><i class="icon-pencil icon-white"></i> Take Exam</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="summary_dialog"></div>
<script>
	$(window).load( function() {
	<?php if($_GET['code']){?>	
		submitform();
	<?php } ?>
	});	
</script>
