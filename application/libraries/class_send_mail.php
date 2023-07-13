<?php
/*
* Usage:
	$config['to'] = 'tolits@gmail.com';
	$config['from'] = 'marlito.dungog@gmail.com';
	$config['subject'] = 'hello world';
	$config['message'] = 'this is my message<b>hehehe</b>';
	$config['attachment'] = 'folder/test.pdf, folder/jquery.rar';
	send_mail($config);
*/
function send_mail($config)
{
	$to      = $config['to'];
	$from    = $config['from'];
	$subject = $config['subject'];
	$message = $config['message'];

	$headers = "From: $from";
	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";


//	// Add the headers for a file attachment
//	$headers .= "\nMIME-Version: 1.0\n" .
//				 "Content-Type: text/html;\n";
//
//	// Add a multipart boundary above the plain message
//	$message = "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
//			   "Content-Transfer-Encoding: 7bit\n\n" .
//				$message . "\n\n";
//

	$ok = @mail($to, $subject, $message);
	return $ok;
}
?>