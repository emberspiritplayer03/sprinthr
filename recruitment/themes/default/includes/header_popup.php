<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/themes-images/favicon.ico">
<title>SprintHR &laquo; <?php echo strip_tags($page_title);?> <?php echo strip_tags($title);?></title>
<?php echo $meta_tags;?>		
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/bootstrap/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/bootstrap/bootstrap.css" />
<?php Loader::get();?>
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/fonts.css" />
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/front.css" />	
</head>
<body class="template_blank_popup">
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
	<div id="container">
    	<div id="header">
        </div><!-- #header -->
        <div id="main_container">