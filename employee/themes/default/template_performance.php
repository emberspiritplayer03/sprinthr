<?php include('includes/header.php');?>
<div id="content">
	<div class="mtcntnr">
		<h1 class="module_title"><i class="mticon_img icon_performance"></i><?php echo $page_title;?><?php echo $page_subtitle;?><a class="add_button" id="add_employee_performance_button_wrapper" href="javascript:void(0);" onclick="javascript:load_add_employee_performance();"><strong>+</strong><b>Add Employee Performance</b></a></h1><div class="mtshad"></div>
	</div>
    <div id="message_container" style="display:none;" class="ui-state-highlight ui-corner-all message_box"> 
        <span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
        <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a>
    </div>
    <?php include('includes/employee_search.php'); ?>
    <div id="main">
        <div class="module_content"><?php $this->showContent();?></div>
    </div>
</div>
<?php include('includes/footer.php');?>