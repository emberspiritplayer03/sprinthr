<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/themes-images/favicon.ico">
<title>SprintHR &laquo; <?php echo strip_tags($page_title);?> <?php echo strip_tags($title);?></title>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/bootstrap/bootstrap.css" />
<?php echo $meta_tags;?>
<?php Loader::get();?>
<link rel="stylesheet" href="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/fonts.css" />
</head>
<body>
<div id="wrapper" class="template_plane">
	<div id="header_wrapper">
        <div id="header_container">        	
            <div id="header" class="clearfix">
                <div class="logo_container">
                	<a href="<?php echo url('schedule');?>">                    	
                    	<img src="<?php echo MAIN_FOLDER; ?>themes/<?php echo MAIN_THEME; ?>/themes-images/logo.png" border="0" alt="SprintHR" />
                    </a>                    
                </div>
                <div class="date_time">
                    <a style="margin-left:20px;" class="btn btn-small btn-primary" href="<?php echo MAIN_FOLDER.'index.php/login/logout'; ?>">Logout</a>
                    <div class="date_view" style="font-size:21px;"><?php echo Tools::getGmtDate("F d, Y");?></div>
                    <div class="time_view blue" style="font-size:21px;"><?php echo Tools::getGmtDate('g:i:s a');?></div> 
                </div>
                <div class="clear"></div>
            </div><!-- #header -->
        </div><!-- #header_container -->
    </div><!-- #header_wrapper -->