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
        <div id="menu"> 
            <ul>      
                <li class="selected"><a href="#"><span>Dashboard</span></a></li>
                <li><a href="#"><span>Leave</span></a></li>
                <li><a href="#"><span>Time</span></a></li>
                <li><a href="#"><span>Benefits</span></a></li>
                <li><a href="#"><span>Recruitment<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="#">Dropdown Menu</a></li>
                        <li><a href="#">Dropdown Menu</a></li>
                        <li><a href="#">Dropdown Menu</a></li>
                        <li><a href="#">Dropdown Menu</a></li>
                        <li><a href="#">Dropdown Menu</a></li>
                        <li><a href="#">Dropdown Menu</a></li>
                    </ul><!-- .submenu -->
                </li>
                <li><a href="#"><span>Performance</span></a></li>
                <li><a href="#"><span>Reports</span></a></li>
                <li><a href="#"><span>Bug Tracker</span></a></li>
                <li><a href="#"><span>Help</span></a></li>
            </ul>
        </div><!-- #menu -->
    </div><!-- #header_container -->
    <div id="wrap">
        <div id="container">
            <div id="submenu">
                <ul class="ulmenu">
                    <li><a href="#">Summary <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li class="selected"><a href="#">Overview</a>
                        <ul class="ulsubmenu">
                            <li><a href="#">Marketing</a></li>
                            <li><a href="#">Content</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Traffic <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="#">Search Engine <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="#">Website Content <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="#">Browsers <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="#">Bug Tracker <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                </ul>
            </div>
            <div id="content">
                <p><?php $this->showContent();?></p>
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
