<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" type="image/png" href="<?php echo BASE_FOLDER; ?>hr/themes/default/themes-images/favicon.ico">
<title><?php echo $GLOBALS['lang_general']['title']; ?></title>
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/hr/bootstrap/bootstrap.css" />
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/hr/bootstrap/bootstrap.min.css" />
<?php echo $meta_tags;?>
<?php Loader::get();?>
<link rel="stylesheet" href="<?php echo BASE_FOLDER; ?>themes/hr/fonts.css" />
</head>

<body>
<script language="javascript">
if (typeof lockScreen == "function") {
	lockScreen();
}
</script>
<div id="wrapper">
<?php $this->showContent();?>
</div><!-- #wrapper -->
</body>
</html>