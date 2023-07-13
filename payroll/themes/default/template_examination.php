<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME;?>/images/favicon.ico">
<title><?php echo $GLOBALS['lang_general']['title']; ?></title>
<?php echo $meta_tags;?>
<?php Loader::get();?>
</head>

<body>
<script language="javascript">
if (typeof lockScreen == "function") {
	lockScreen();
}
</script>
<div id="wrapper">
    <div id="header_container">
        <div id="top_navigation">
            <div align="right"><span>Howdy, <strong><a class="account_name" href="#">Admin</a><?php //echo $_SESSION['summit_hr']['firstname'];?> <?php //echo $_SESSION['summit_hr']['lastname'];?></strong><a class="logout" href="<?php echo url('login/logout');?>">Log Out</a></span></div>
        </div><!-- #top_navigation -->
        <div id="header">
            <div class="logo_container">
                <a href="#"><img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/logo.png" border="0" /></a>
            </div>            
        </div><!-- #header -->
        <div id="menu"> </div><!-- #menu -->
    </div><!-- #header_container -->
    <div id="wrap">
            <div id="content">
                <?php $this->showContent();?>
            </div>
            <div class="clear"></div>
        </div><!-- #container -->
    </div><!-- #wrap -->
    <div id="backgroundPopup"></div>
    <div id="footer_container">
        <div id="footer">
            <p><?php echo $GLOBALS['lang_general']['footer_title']; ?></p>
            <small><?php echo $GLOBALS['lang_general']['copyright_statement']; ?></small>
        </div><!-- #footer -->
    </div><!-- #footer_container -->
</div><!-- #wrapper -->
</body>
</html>

