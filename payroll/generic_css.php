<?php
session_start();
$theme_name = 'default';
$cachefile = "themes/{$theme_name}/assets/generic.css";

// include JS files if it's in the browser's cache
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !isset($_GET['nocache'])) {
	$timestamp = filemtime($cachefile);
	$last_modified = gmdate('D, d M Y H:i:s', $timestamp).' GMT';
	$etag = md5($last_modified);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (24 * 60 * 60)) . ' GMT');
	header('Content-Type: text/css');
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
	header('Content-Type: text/css');
	header('Content-Length: '. $cachefile_size);
	header("Last-Modified: " . $last_modified);
	header("ETag:" . $etag);
	header('Cache-Control: public, max-age=' . (24 * 60 * 60));
	include($cachefile);
}

// create and include JS files
else { 

   $cache_files = array(
	"themes/{$theme_name}/assets/datatable.css",
	"themes/{$theme_name}/assets/paginator.css", 
	"themes/{$theme_name}/assets/tabview.css",
	"themes/{$theme_name}/assets/form_validator.css",
	"themes/{$theme_name}/assets/jquery.calendar.css",
	"themes/{$theme_name}/assets/form.css",
	"themes/{$theme_name}/assets/internal.autocomplete.css",
	"themes/{$theme_name}/assets/jquery.autocomplete.css",
	"themes/{$theme_name}/assets/thickbox.css",
	//"themes/{$theme_name}/assets/jquery.simplemodal.css",
	"themes/{$theme_name}/assets/jquery.textboxlist.css",
	"themes/{$theme_name}/assets/ui.daterangepicker.css"
	
  );

	ob_start();
	$cachedfiles = "";
	foreach ($cache_files as $cache) {
		 include($cache); 
		 $cachedfiles .= $cache . "; ";
	}	
	$content = ob_get_contents();
	ob_end_clean();	
	
	include 'application/libraries/css_compressor.php';
	$c = new CSSCompression;
	$content = $c->compress($content);
	
	$content =  "/* " .  date('D, d M Y H:i:s', time()) .   " - " . $cachedfiles . " */ " . $content ;

	$file = fopen($cachefile, 'w');
	fwrite($file, $content);
	fclose($file);

	$cachefile_size = filesize($cachefile);
	
	$timestamp = filemtime($cachefile);
	$last_modified = gmdate('D, d M Y H:i:s', $timestamp).' GMT';
	$etag = md5($last_modified);
	header('Expires: '.gmdate('D, d M Y H:i:s', time() + (24 * 60 * 60)).' GMT'); // 1 year from now
	header('Content-Type: text/css');
	header('Content-Length: '. $cachefile_size);
	header("Last-Modified: " . $last_modified);//gmdate('D, d M Y H:i:s', $last_modified).' GMT');
	header("ETag:" . $etag);
	header('Cache-Control: public, max-age=' . (24 * 60 * 60));
	echo $content;
}

?>
