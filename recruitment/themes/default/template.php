<?php include('includes/header_no_login_details.php');?>
<div id="main" class="fullwidth">
    <div id="content">            	
        <div class="page_content">
            <h1 class="page_title"><?php echo $page_title . ' ' . $module_title;?>
            <a href="javascript:void(0);" onclick="javascript:history.go(-1);" class="gray_button title_back_button"><i></i>Back</a>
            </h1>
            <?php $this->showContent();?>
        </div>
    </div><!-- #content -->            
</div>
<?php include('includes/footer.php');?>
