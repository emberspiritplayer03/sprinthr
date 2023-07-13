<?php
/**
 * Create a link that calls javascript function
 *
 * @param string $text
 * @param string $function function to be called such 'getName()'
 * @param string $event event to be used such 'onclick', 'onmousemove'
 * @return string
 */
function anchor_js($text, $function, $event = "onclick")
{
	return "<a $event='$function' href='#'>$text</a>";
}
?>