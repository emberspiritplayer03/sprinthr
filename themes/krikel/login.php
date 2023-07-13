<?php
$cachefile = "cache/" . CONTROLLER . '_' . METHOD . '.html';
start_cache($cachefile);
	  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php echo $meta_tags;?>
		<?php Loader::get();?>
		<title><?php echo $GLOBALS['lang']['app_full_name']; ?></title>
	</head>
    <body style="text-align:center;">
            <script language="javascript">
            if (typeof lockScreen == "function") {
                lockScreen();
            }
            </script>
        <?php $this->showContent();?>
    </body>
</html>

<?php
end_cache($cachefile);
?>