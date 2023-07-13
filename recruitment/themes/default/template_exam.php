<?php include('includes/header.php');?>
<div id="main" class="fullwidth">
    <div id="content">            	
        <div class="page_content">
            <h1 class="page_title"><?php echo $page_title . $module_title;?></h1>
            <?php $this->showContent();?>
        </div>
    </div><!-- #content -->            
</div>
<?php include('includes/footer.php');?>
