<?php include('includes/header.php');?>
<?php include('includes/submenu_options.php');?>
<div id="content" class="sidebar">
	<div class="mtcntnr"><h1 class="module_title"><i class="mticon_img icon_option"></i><?php echo $page_title;?></h1><div class="mtshad"></div></div>
	<div id="main">
        <div class="module_content"><?php $this->showContent();?></div>
    </div>
</div>
<?php include('includes/footer.php');?>