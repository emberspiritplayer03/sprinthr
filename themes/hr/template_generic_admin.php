<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />        
    	<title><?php echo $GLOBALS['lang']['app_full_name']; ?></title>
        <?php echo $meta_tags;?>
    	<?php Loader::get();?>	
        <!--[if IE]>
        <style type="text/css"> 
        /* place css fixes for all versions of IE in this conditional comment */
        .mainStyle #sidebar1 { padding-top: 30px; }
        .mainStyle #mainContent { zoom: 1; padding-top: 15px; }
        /* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
        </style>
        <![endif]-->
    </head>

	<body class="mainStyle">
    	 <!-- For YUI Dialog -->
    	<div class="yui-skin-sam" style="font-size:12px; font:12px verdana"><div id="yuiContainer"></div></div>
		<script language="javascript">
        if (typeof lockScreen == "function") {
            lockScreen();
        }
        </script>

        <div id="container">
        
          <div id="header">
            <h1>Header</h1>
          </div><!-- end #header -->
          <div id="sidebar1">
          	<div class="navLinks">
                <p class="navLink"><a onmouseup="navigate('generic_admin')" href="<?php echo $base_folder;?>generic_admin/">Dashboard</a></p>
                    <div class="subNavLink"><?php echo $sub_nav_dashboard;?></div>
                <p class="navLink"><a onmouseup="navigate('settings')" href="<?php echo $base_folder;?>settings/">Settings</a></p>
                    <div class="subNavLink"><?php echo $sub_nav_settings;?></div>
               <div class="png" style="height:200px"></div>
            </div>
          </div><!-- end #sidebar1 -->
          
          <div id="mainContent">
          	<div class="message" style="display:none">Loading...</div>
            <h1 class="pageTitle"><?php echo $page_title;?></h1>
            <div class="innerContent">
                <?php $this->showContent();?>
            </div>
          </div><!-- end #mainContent -->
          
            <br class="clearfloat" />
            
           <div id="footer">
            <p><?php echo $GLOBALS['lang']['copyright_statement']; ?></p>
          </div><!-- end #footer -->
          
        </div><!-- end #container -->
    </body>
</html>