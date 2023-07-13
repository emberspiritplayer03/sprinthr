<?php
//$cachefile = "cache/" . CONTROLLER . '_' . METHOD . '.html';
//start_cache($cachefile);
	  
?>
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
    <body>
            <script language="javascript">
            if (typeof lockScreen == "function") {
                lockScreen();
            }
            </script>
        <div id="wrapper">
        	
            <div id="container">
                <div id="header">
                    <a href="#"><img class="logo" src="<?php echo BASE_FOLDER; ?>themes/<?php echo THEME; ?>/themes-images/new/logo.png" alt="logo" /></a>
                    <div id="menu">
                        <ul>
                            <li class="selected"><a href="<?php echo urlMain("index"); ?>">Home</a></li>
                            <li><a href="<?php echo urlMain("job_vacancy"); ?>">Job Vacancy</a></li>                    
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Contact</a></li>
                            <?php if($hdr_email_address){?>
                                <div class="profile_info"><?php include('includes/profile_info.php'); ?></div>
                            <?php } ?>
                        </ul>
                    </div>             
                    <div class="clear"></div>
                </div><!-- #header -->
                <div id="main_container">    
        			<?php $this->showContent();?>
                    <div class="clear"></div>
                </div><!-- #main_container -->
                <div id="footer">
                    <div class="inner">
                        &copy; 2013 Metropolitan. All Rights Reserved. Website Design and Development by <a class="gleent" href="http://gleent.com" target="_blank">Gleent INCORPORATED</a>.
                    </div>
                </div><!-- #footer -->
            </div><!-- #container -->
        </div><!-- #wrapper -->
    </body>
</html>

<?php
end_cache($cachefile);
?>