<?php
session_start();
$cachefile = "application/scripts/generic.js";

// include JS files if it's in the browser's cache
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !isset($_GET['nocache'])) {
	$timestamp = filemtime($cachefile);
	$last_modified = gmdate('D, d M Y H:i:s', $timestamp).' GMT';
	$etag = md5($last_modified);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (24 * 60 * 60)) . ' GMT');
	header('Content-Type: text/javascript');
	header("Last-Modified: " . $last_modified);
	header("ETag:" . $etag);
	header('Cache-Control: public, max-age=' . (24 * 60 * 60));	
	header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
	exit;
}

if (file_exists($cachefile) && !isset($_GET['nocache'])) { // include JS files if it's in the server's cache
	$cachefile_size = filesize($cachefile);
	$timestamp = filemtime($cachefile);
	$last_modified = gmdate('D, d M Y H:i:s', $timestamp).' GMT';
	$etag = md5($last_modified);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (24 * 60 * 60)) . ' GMT');
	header('Content-Type: text/javascript');
	header('Content-Length: '. $cachefile_size);
	header("Last-Modified: " . $last_modified);
	header("ETag:" . $etag);
	header('Cache-Control: public, max-age=' . (24 * 60 * 60));
	include($cachefile);
}

// create and include JS files
else { 

   $cache_files = array(
   "application/scripts/jquery-1.4.4.min.js",
   "application/scripts/jquery-ui-1.8.9.custom.min.js",
	"application/scripts/jquery.validate.js", 
	"application/scripts/jquery.form.js", 
	"application/scripts/jquery.calendar.js",
	"application/scripts/jquery.scroll.js", 
	"application/scripts/jquery.block.ui.js",	
	"application/scripts/jquery.bgiframe.min.js",
	"application/scripts/jquery.ajaxQueue.js",
	"application/scripts/jquery.textboxlist.js",
	"application/scripts/jquery.simplemodal.js",
	"application/scripts/datatable2.js",
	"application/scripts/timepicker.js",
	"application/scripts/jquery.daterangepicker.js",

	 //"application/scripts/yui/build/yuiloader/yuiloader-min.js", 
	"application/scripts/yui/build/yahoo-dom-event/yahoo-dom-event.js",
	//"application/scripts/yui/build/dom/dom-min.js",	 
	//"application/scripts/yui/build/event/event-min.js",
	//"application/scripts/yui/build/animation/animation-min.js",
	"application/scripts/yui/build/element/element-min.js",
	"application/scripts/yui/build/utilities/utilities.js",
	//"application/scripts/yui/build/container/container-min.js",
	//"application/scripts/yui/build/button/button-min.js",
	//"application/scripts/yui/build/menu/menu-min.js",
	"application/scripts/yui/build/datasource/datasource-min.js",
	"application/scripts/yui/build/paginator/paginator-min.js",
	"application/scripts/yui/build/datatable/datatable-min.js",
	"application/scripts/yui/build/tabview/tabview-min.js",
	"application/scripts/yui.datatable.js",
	"application/scripts/jquery.textboxlist.js",	
	"application/scripts/main.js"
  );

	ob_start();
	$cachedfiles = "";
	foreach ($cache_files as $cache) {
		 include($cache); 
		 $cachedfiles .= $cache . "; ";
	}	
	$content = ob_get_contents();
	ob_end_clean();	
	
	include 'application/libraries/jsmin.php';
	$content = JSMin::minify($content);
	
	$content =  "/* " .  date('D, d M Y H:i:s', time()) .   " - " . $cachedfiles . " */ " . $content ;

	$file = fopen($cachefile, 'w');
	fwrite($file, $content);
	fclose($file);

	$cachefile_size = filesize($cachefile);
	
	$timestamp = filemtime($cachefile);
	$last_modified = gmdate('D, d M Y H:i:s', $timestamp).' GMT';
	$etag = md5($last_modified);
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + (24 * 60 * 60)).' GMT'); // 1 year from now
	header('Content-Type: text/javascript');
	header('Content-Length: '. $cachefile_size);
	header("Last-Modified: " . $last_modified);//gmdate('D, d M Y H:i:s', $last_modified).' GMT');
	header("ETag:" . $etag);
	header('Cache-Control: public, max-age=' . (24 * 60 * 60));
	echo $content;
}

?>
