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
                <li class="<?php echo $plugins; ?>"><a href="<?php echo url('source/plugins'); ?>"><span>Plugins<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
	                <ul class="submenu">
                        <li><a href="<?php echo url('source/plugins?sidebar=autocomplete'); ?>">Autocomplete</a></li>
                        <li><a href="<?php echo url('source/plugins?sidebar=textboxlist'); ?>">Textbox List</a></li>
                        <li><a href="<?php echo url('source/plugins?sidebar=chart'); ?>">Chart</a></li>
                        <li><a href="<?php echo url('source/plugins?sidebar=datatable'); ?>">Datatable</a></li>
                        
                    </ul><!-- .submenu -->
                    
                </li>
                <li class="<?php echo $jquery;?>" ><a href="<?php echo url('source/jquery');?>"><span>Jquery<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo url('source/jquery?sidebar=jquery');?>">Jquery</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=ui');?>">UI</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=validator');?>">Validator</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=inline_validation');?>">Inline Validation</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=jquery_upload');?>">Jquery Upload</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=image_lightbox');?>">Image Lightbox</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=pretty_photo');?>">Jquery Pretty Photo</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=dialog');?>">Dialog</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=date_rangepicker');?>">Date Range Picker</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=block_ui');?>">Block UI</a></li>
                        <li><a href="<?php echo url('source/jquery?sidebar=form_submit');?>">Form Submit</a></li>
                    </ul><!-- .submenu -->
                </li>
                <li class="<?php echo $libraries; ?>"><a href="<?php echo url('source/libraries');?>"><span>Libraries<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo url('source/libraries?sidebar=tools');?>">Tools</a></li>
                        <li><a href="<?php echo url('source/libraries?sidebar=date');?>">Date</a></li>
                        <li><a href="<?php echo url('source/libraries?sidebar=server_validation');?>">Validation</a></li>
                     
                    </ul><!-- .submenu -->
            	</li>
               <li class="<?php echo $themes; ?>"><a href="<?php echo url('source/themes');?>"><span>Themes / Style<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo url('source/themes?sidebar=themes');?>">Tables</a></li>
                        <li><a href="<?php echo url('source/themes?sidebar=text_curve');?>">Curve</a></li>
                        <li><a href="<?php echo url('source/themes?sidebar=periodic_table');?>">Periodic Table</a></li>
                        <li><a href="<?php echo url('source/themes?sidebar=periodic_table_form');?>">Periodic Table with Form</a></li>
                        <li><a href="<?php echo url('source/themes?sidebar=forms');?>">Forms</a></li>
                    </ul><!-- .submenu -->
                </li>
                <li class="<?php echo $reports; ?>"><a href="<?php echo url('source/reports');?>"><span>Reports<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="<?php echo url('source/reports?sidebar=pdf_writer');?>">PDF</a></li>
                        <li><a href="<?php echo url('source/reports?sidebar=excel');?>">Excel</a></li>
                        <li><a href="<?php echo url('source/reports?sidebar=doc');?>">Doc</a></li>
                    </ul><!-- .submenu -->
                </li>
                <li class="<?php echo $miscelleneous; ?>"><a href="#"><span>Miscelleneous<img src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/menu_dropicon.png" border="0" /></span></a>
                    <ul class="submenu">
                        <li><a href="#">Backup</a></li>
                        <li><a href="#">Scaffolding</a></li>
                        <li><a href="#">Paginator</a></li>
                        <li><a href="#">Ajax Paginator</a></li>
                        <li><a href="#">CKEditor</a></li>
                        <li><a href="#">CSV</a></li>
                    </ul><!-- .submenu -->
                </li>
                <li><a href="#"><span>Help</span></a></li>
            </ul>
        </div><!-- #menu -->
    </div><!-- #header_container -->
    <div id="wrap">
        <div id="container">
            <div id="submenu">
                <ul class="ulmenu">
                    <li><a href="<?php echo url('source/libraries?sidebar=tools'); ?>">Tools <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="<?php echo url('source/libraries?sidebar=date'); ?>">Date <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                    <li><a href="<?php echo url('source/libraries?sidebar=server_validation'); ?>">Server Validation <img class="subdropicon" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/submenu_down_icon.png" /></a></li>
                </ul>
            </div>
            <div id="content">
            	<p><h1><?php echo $page_title; ?></h1></p>
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
