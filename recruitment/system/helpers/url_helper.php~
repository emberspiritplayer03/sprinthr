<?php
/**
 * Redirect to page. Uses header("Location:..");
 *
 * @param string $controller_method controller and method such as 'index/myMethod'
 * @param mixed $params
 */
function redirect($controller_method, $params = null)
{
	if (is_array($params) && count($params) > 0)
	{
		foreach ($params as $param => $value)
		{
			//$param_string .= "&$param=$value";
			$param_string .= '/' . $value;
		}
	}
	else if ($params != null)
	{
		//$params = substr($params, strlen($params), -1);
		$param_string = '/' . $params;
	}
	
	if (!HIDE_INDEX_PAGE)
	{
		$index_page = INDEX_PAGE . '/';
	}
	header("Location:" . get_base_url() . $index_page . "$controller_method{$param_string}");
}

function redirectMain($controller_method, $params = null)
{
	if (is_array($params) && count($params) > 0)
	{
		foreach ($params as $param => $value)
		{
			//$param_string .= "&$param=$value";
			$param_string .= '/' . $value;
		}
	}
	else if ($params != null)
	{
		//$params = substr($params, strlen($params), -1);
		$param_string = '/' . $params;
	}
	
	if (!HIDE_INDEX_PAGE)
	{
		$index_page = INDEX_PAGE . '/';
	}

	header("Location:" . get_main_base_url() . $index_page . "$controller_method{$param_string}");
}


/**
 * Get the complete URL
 *
 * @param string $controller_method
 * @param mixed $params
 * @return string
 */
function url($controller_method, $params = null)
{
	$param_string = '';
	
	if (is_array($params) && count($params) > 0)
	{
		foreach ($params as $param => $value)
		{
			//$param_string .= "&$param=$value";
			$param_string .= '/' . $value;
		}
	}
	else if ($params != null)
	{
		//$params = substr($params, strlen($params), -1);
		$param_string = '/' . $params;
	}

	if (!HIDE_INDEX_PAGE)
	{
		$index_page = INDEX_PAGE . '/';
	}
	
	return get_base_url() . $index_page . "$controller_method{$param_string}";
}

function urlMain($controller_method, $params = null)
{
	$param_string = '';
	
	if (is_array($params) && count($params) > 0)
	{
		foreach ($params as $param => $value)
		{
			//$param_string .= "&$param=$value";
			$param_string .= '/' . $value;
		}
	}
	else if ($params != null)
	{
		//$params = substr($params, strlen($params), -1);
		$param_string = '/' . $params;
	}

	if (!HIDE_INDEX_PAGE)
	{
		$index_page = INDEX_PAGE . '/';
	}
	
	return get_main_base_url() . $index_page . "$controller_method{$param_string}";	
}
?>