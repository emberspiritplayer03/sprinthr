<?php include('includes/header.php');?>
<div id="content" class="sidebar_left">
	<div class="mtcntnr">
    	<h1 class="module_title"><i class="mticon_img icon_employee"></i><?php echo $page_title;?><?php echo $page_subtitle;?>
       	<?php if($button_type == 'leave') { ?>
        	<a id="request_leave_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_leave_form();"><strong>+</strong><b>Request Leave</b></a>
        <?php } else if($button_type == 'overtime') { ?>
        	<a id="request_overtime_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_overtime_form();"><strong>+</strong><b>Request Overtime</b></a>
        <?php } else if($button_type == 'change_schedule') { ?> 
        	<a id="request_change_schedule_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_change_schedule_form();"><strong>+</strong><b>Request Change Schedule</b></a>
		<?php } else if($button_type == 'rest_day') { ?> 
        	<a id="request_rest_day_button" class="add_button" href="javascript:void(0);" onclick="javascript:show_request_rest_day_form();"><strong>+</strong><b>Request Rest Day</b></a>
		<?php } ?>        	
       	</h1><div class="mtshad"></div>
    </div>
	<div id="message_container" style="width:auto; display:none;" class="ui-state-highlight ui-corner-all message_box"> 
        <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a>
    </div>
    <div id="main">
        <div class="module_content"><?php $this->showContent();?></div>
    </div>
</div>
<?php include('includes/footer.php');?>