<?php



/**

* WGFramework Upload class

*

* This class upload document depending on the allowed document types

*

* @version 1.0.1

* @package WGFramework

* @author Webgroundz

* @category Library

* @date created Jul-02-07 

* @last modified Jul-30-07      

*/



// Sample Usage:

/********************

	$config['directory'] = 'image/another_dir';

	$config['field'] = 'uploaded_file';

	$config['name'] = 'myfilename';

	$config['allowed_types'] = 'jpg|jpeg|gif|png|doc|pdf|html|exe';

	$config['max_size'] = 35000;



	//initialize upload class

	$uploader = new Wg_Upload($config);

	

	if (!$uploader->uploadFile())

	{

		echo $uploader->displayErrorMessages();

	}

*/

class Wg_Upload

{	

	public $directory, $field, $allowed_types, $filename, $max_size;

	protected $error_messages = array(), $success_message;

	

	/**

	 * Initialize the parameter for file upload

	 *

	 * @param array $config

	 * 

	*/

	public function __construct($config = array())

	{

		foreach($config as $conf => $value)

		{

			$this->$conf = $value;

		}

	}

	

	/**

	 * Execute The Uploading Of File

	 * @access  public

	 */

	public function uploadFile()

	{

		$return = false;

		

		//check if it has file selected

/*		if(!$this->getUploadedFileName())

		{

			$this->error_messages[] = "No File Selected";	

		}*/

		

		//check if the type is known in the array

		if ($this->allowed_types != null)

		{

			if(!$this->isAllowedTypes($this->getFileExtension()))

			{

				$this->error_messages[] = "Unknown File Type";

			}

		}

		

		//check if the the document file size is not reach the maximum file size

		if($this->getUploadedFileSize() >= $this->max_size && is_int($this->max_size) && $this->max_size > 0 )

		{

			$this->error_messages[] = "You Have Reached The Maximum File Size Limit!";

		}

		

		//upload document if no errors occur

		if (count($this->getErrorMessages()) == 0)

		{

			if(move_uploaded_file($this->getUploadedTemporaryName(), $this->getFilePath()))

			{

				$return = true;

			}

		} 	

		

		return $return;

		

	}

	

	public function getDirectory()

	{

		return $this->directory . '/';

	}

	

	/**

	 * Validate If Uploading File Has an Errors

	 *

	 * @return bool

	 */

	public function isUploaded()

	{

  		return (count($this->getErrorMessages())) ? FALSE : TRUE; 

	}

	

	public function isExecutable()

	{

		return (is_executable($this->getUploadedFileName()));

	}

	

	/**

	 * Get Upload Path

	 *

	 * @access	public

	 * @return	string

	 * 

	 */	

	public function getFilePath()

	{

		return $this->getDirectory() . $this->getFileName();

	}

	

	/**

	 * Get Name w/o extension of the file being uploaded

	 * @access public

	 * @return string 

	 */

	public function getName()

	{

		return $this->name = ($this->name == null) ? $this->getDefaultFileName() : $this->name;		

	}

	

	/**

	 * Get Name of the file being uploaded

	 * @access public

	 * @return string 

	 */

	public function getFileName()

	{

		return $this->getName() . '.' . $this->getFileExtension();

	}

	

	/**

	 * Filter all allowed file types

	 *

	 * @param string $file_extension

	 * @return bool

	 */

	private function isAllowedTypes($file_extension)

	{

		$return = false;

		$this->allowed_types = explode('|', $this->allowed_types);

		if(is_array($this->allowed_types))

		{

			if(in_array(strtolower($file_extension), $this->allowed_types))	

			{

				$return = true;

			}	

		}

		return $return;

	}

	

	/**

	 * Get File Extension

	 *

	 * @return string file_extension

	 */

	public function getFileExtension()

	{

		return strtolower(substr(strrchr($this->getUploadedFileName(), '.'), 1));

	}

	

	/**

	 * Get Default File Name

	 *

	 * @return string default file name

	 */

	private function getDefaultFileName()

	{

		$default_file_name = explode('.', $this->getUploadedFileName());

		return $default_file_name[0];

	}

	

	/**

	 * Get Document File Size

	 * 

	 * @return int

	 */

	private function getUploadedFileSize()

	{

		return $_FILES[$this->field]['size'];

	}

	

	/**

	 * Get Document Name

	 *

	 * @return string uploaded file name

	 */

	public function getUploadedFileName()

	{

		return $_FILES[$this->field]['name'];

	}

	

	/**

	 * Get Temporary Document Name

	 *

	 * @return string temporary name

	 */

	private function getUploadedTemporaryName()

	{

		return $_FILES[$this->field]['tmp_name'];

	}

	

	/**

	 * Get error messages

	 * 

	 * @return array

	 */

	private function getErrorMessages()

	{

		return $this->error_messages;

	}

	

	/**

	 * Display error message depends on the type of error

	 *

	 */

	public function displayErrorMessages()

	{

		foreach($this->getErrorMessages() as $error)

		{

			$str .= $error;

			$str .= '<br>';

		}

		echo $str;

	}

}

?>

