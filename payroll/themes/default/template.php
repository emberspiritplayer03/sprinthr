<?php include('includes/header.php');?>
<div id="content" class="fullwidth">
	<div class="mtcntnr"><h1 class="module_title"><i class="mticon_img"></i><?php echo $page_title . $module_title;?><a href="javascript:history.go(-1)" class="gray_button title_back_button"><i></i>Back</a></h1><div class="mtshad"></div></div>
	<div id="main">
        <div class="module_content">
			<div id="message_container" style="margin-top: 20px; padding: 0 .7em; width:30%; display:none" class="ui-state-highlight ui-corner-all"> 
                <div><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-info"></span>
                <span class="message"></span><a class="ui-icon ui-icon-close" href="javascript:void(0)" onclick="javascript:$('#message_container').hide()" style="float:right" title="Close"></a></div>
            </div><!-- #message_container -->
		    <?php $this->showContent();?>
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>
