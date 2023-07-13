<?php
class Wg_Error
{
	protected $errno, $errstr, $errfile, $errline;
	public $error_message;

	public function wgErrorHandler($errno, $errstr, $errfile, $errline)
	{
	    switch ($errno) {
			case E_USER_ERROR:
				$this->error_message .= "<b>WG ERROR:</b> [$errno] $errstr<br />\n";
				//echo "  Fatal error on line $errline";
				$this->error_message .= "PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				$this->viewError($this->error_message);
				$this->recordErrorLog($this->error_message);
				exit(1);
				break;

			case E_USER_WARNING:
				$this->error_message .= "<b>WG WARNING:</b> [$errno] $errstr<br />\n";
				$this->viewError($this->error_message);
				$this->recordErrorLog($this->error_message);
				break;

			case E_USER_NOTICE:
				$this->error_message .= "<b>WG NOTICE:</b> [$errno] $errstr<br />\n";
				$this->viewError($this->error_message);
				$this->recordErrorLog($this->error_message);
				break;
	    }
	    return true;
	}

	public function setErrorHandler()
	{
		set_error_handler(array($this, 'wgErrorHandler'));
	}

	protected function recordErrorLog($errstr)
	{
		return error_log($errstr, 3, 'error/error.log')."\n";
	}	

	private function viewError($error_message)
	{?>
		<html>
		<head>
		<style type="text/css">
		@import 'error/err.css';
		</style>
		</head>
			<body>
				<div id="content"><h1>
				<?php
					echo $error_message;
				?></h1></div>
			</body>	
		</html>
	<?php
	}
}
?>