<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $GLOBALS['lang_general']['title']; ?></title>
<?php echo $meta_tags;?>
<?php Loader::get();?>	
<style type="text/css">
<!--
.style3 {color: #F7F7F7}
-->
</style>
</head>

<body>
<script language="javascript">
if (typeof lockScreen == "function") {
	lockScreen();
}
</script>
<div id="top_navigation">
<a class="logo" href="#"></a>
<div class="welcome">
    <p><strong><span style="color:#555;">Welcome  <?php echo $_SESSION['summit_hr']['firstname'];?> <?php echo $_SESSION['summit_hr']['lastname'];?></span></strong>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="logout" href="<?php echo url('login/logout');?>">LOGOUT</a></p>
</div>
</div>
<div id="header">
	<div id="title">
    	Krikel V2
    </div>
	<div id="menu">
       
            <li><a href="<?php echo url('source'); ?>">Widgets</a></li>
            <li><a href="<?php echo url('source/jquery'); ?>">Jquery</a></li>
            <li><a href="<?php echo url('source/jqueryui'); ?>">Jquery UI</a></li>
           <li><a href="<?php echo url('source/scaffold'); ?>">Scaffolding</a></li>
           <li><a href="<?php echo url('source/paginator'); ?>">Paginator</a></li>
            <li><a href="<?php echo url('source/csv'); ?>">CSV</a></li>
            
            <li><a href="<?php echo url('source/template_form'); ?>">Template Form</a></li>
            <li><a href="<?php echo url('source/editor'); ?>">Editor</a> </li>
             <li><a href="<?php echo url('source/calendar'); ?>">Calendar</a> </li>
             <li><a href="<?php echo url('source/dialog'); ?>">Dialog</a> </li>

    </div>
</div>
<div id="content">
	<div class="content_page">
    	<h2><?php echo $page_title;?></h2>
        <div class="message"></div>
		<p><?php $this->showContent();?></p>
    </div>
</div>
<div id="content_bot">
</div>
<div id="backgroundPopup"></div>
<div id="footer">
<p>Copyright © 2010, All Rights Reserved | Privacy Policy</p>
</div>
</body>
</html>
