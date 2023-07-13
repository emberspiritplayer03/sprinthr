<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/themes-images/favicon.ico">
<title>SprintHR &laquo; <?php echo $page_title;?><?php //echo $GLOBALS['lang_general']['title']; ?></title>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.css" />
<?php echo $meta_tags;?>
<?php Loader::get();?>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/fonts.css" />
</head>
<body>
<script language="javascript">
	if (typeof lockScreen == "function") {
		lockScreen();
	}
</script>
<script type="text/javascript">
$(function() {
    var button = $('#dropButton');
    var box = $('#dropBox');
    //var form = $('#contDiv');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });

    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#dropButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
});
</script>

<div id="wrapper">
	<div id="header_wrapper">
        <div id="header_container">
            <div id="header" class="clearfix">
                <div class="logo_container">
                	<a href="#">
                    	<img src="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/themes-images/<?php echo $sprint_logo; ?>" border="0" alt="SprintHR" />
                    </a>
                </div>
                <?php include('profile_info.php');?>
                <?php include('menu.php');?>
            </div><!-- #header -->
        </div><!-- #header_container -->
    </div><!-- #header_wrapper -->
    <div id="wrapcontainer">
    	<div id="container">
        	<div class="contshad contshadcor lefttop"></div>
            <div class="contshad contshadcor righttop"></div>
            <div class="contshad contshadcor leftbottom"></div>
            <div class="contshad contshadcor rightbottom"></div>
            <div class="contshad leftside"></div>
            <div class="contshad rightside"></div>
            <div class="contshad topside"></div>
            <div class="contshad bottomside"></div>