<?php include('includes/header.php');?>
<div id="content" class="fullwidth">
	<div class="mtcntnr">
    	<h1 class="module_title"><i class="mticon_img icon_recruitment"></i><?php echo $page_title;?><?php echo $page_subtitle;?><a class="add_button" id="add_candidate_button_wrapper" href="#candidate_form_wrapper" onClick="javascript:load_add_candidate();"><strong>+</strong><b>Add Candidate</b></a><a class="gray_button title_back_button" href="javascript:history.go(-1)"><i></i>Back</a></h1><div class="mtshad"></div>
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