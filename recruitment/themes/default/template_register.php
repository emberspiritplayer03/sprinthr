<?php include('includes/header_no_login_details.php');?>
<div id="main" class="fullwidth">
    <div id="content">
        <div class="mtcntnr">
            <h1 class="module_title"><i class="mticon_img icon_employee"></i><?php echo $page_title;?><?php echo $page_subtitle;?></h1><div class="mtshad"></div>
        </div>
        
        <div id="main">
            <div class="module_content"><?php $this->showContent();?></div>
        </div>
    </div>
</div>
<?php include('includes/footer.php');?>