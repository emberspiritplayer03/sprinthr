<?php
/**
 * Create a link like <a href='about/show/1/2'>text</a>
 *
 * @param string $text
 * @param string $controller_method controller and method such 'blog/view'
 * @param mixed $params
 * @return string
 */
function anchor($text, $controller_method = null, $params = null, $attributes = '')
{
	if (is_array($params) && count($params) > 0)
	{
		foreach ($params as $param => $value)
		{
			$param_string .= '/' . $value;
		}
	}
	else if ($params != null)
	{
		$param_string = '/' . $params;
	}
	if (!HIDE_INDEX_PAGE)
	{
		$index_uri = INDEX_PAGE . '/';
	}
	$index_uri = ($controller_method == null) ? '' : $index_uri;
	//return "<a href='" . get_base_url() . $index_uri . "$controller_method{$param_string}'>$text</a>";
	return "<a href='" . BASE_FOLDER . $index_uri . "$controller_method{$param_string}' {$attributes}>$text</a>";
}

function image($filename, $directory = null, $attributes = array())
{
	if ($directory == null)
	{
		$directory = 'images';
	}
	$att = '';
		
	if (is_array($attributes) && $attributes != NULL)
	{
		foreach($attributes as $attribute => $value)
		{
			$att .= "$attribute='$value' ";
		}
	}
	//$path = get_base_url() . $directory . '/' . $filename;
	$path = BASE_FOLDER . $directory . '/' . $filename;
	return "<img src='$path' $att />";
}
?>