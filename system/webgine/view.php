<?php
class View
{
	protected $view_folder = "views";
	//protected $template_folder = "_templates";
	protected $template_list;
	
	public $page;
	public $template;
	public $variables;
	
	function __construct()
	{
		$this->template_list = array(
			'template' => 'template.php',
			'template2' => 'template2.php',
			'template3' => 'template3.php'
		);
	}
	
	function render($page, $var = array())
	{
		// perform time benchmarking
		//Loader::appLibrary('class_page_load');
		//$loader = new Page_Load();	//start counting
		$this->page = $page;
		
		$this->variables = $var;
				
		if ($this->template)
		{
			if (is_array($this->variables))
			{
				foreach($this->variables as $key => $value)
				{
					//$this->$key = $value;
					${$key} = $value;
				}
			}			
			include_once $this->template;
		}
		else
		{
			$this->showContent();
		}
	}
	
	function getRender($page, $var = array())
	{
		$this->page = $page;
		$this->variables = $var;
		
		ob_start();	
		if ($this->template)
		{
			if (is_array($this->variables))
			{
				foreach($this->variables as $key => $value)
				{
					${$key} = $value;
				}
			}			
			include_once $this->template;
		}
		else
		{
			$this->showContent();
		}
		$out = ob_get_clean();
		return $out;
	}	
	
	function showContent()
	{
		if (is_array($this->variables))
		{
			foreach($this->variables as $key => $value)
			{
				//$this->$key = $value;
				${$key} = $value;
			}
		}

		include APP_PATH . $this->view_folder . "/" . $this->page;
	}
	
	function setTemplate($page)
	{
		//Loader::appLibrary('template_config');
		//$config = new Template_Config();
		//$templates = $config->get();

		$template = $this->template_list[$page];//$templates['setting']->$page;
		if ($template)
		{
			$page = $template;
		}
			
		//$file = APP_PATH . $this->view_folder . "/" . $this->template_folder . "/$page";
		$file = 'themes/' . THEME ."/$page";

		if ($_GET['ajax'] == 1) {
			$this->noTemplate();
		} else if (file_exists($file)) {
			$this->template = $file;
		} else {
			return false;
		}
	}
	
	function getTemplateList()
	{
		return $this->template_list;
	}
	
	function noTemplate()
	{
		$this->template = '';
	}

}
?>