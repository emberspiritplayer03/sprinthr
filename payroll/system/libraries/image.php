<?php

/**

* WGFramework Image class

*

* This class gets all the attributes of image

*

* @version 1.0.1

* @package WGFramework

* @author Webgroundz

* @category Library

* @date created Jul-18-07 

* @last modified Jul-27-07     

*/



// Sample Usage:

/********************

	$image = new Wg_Image('images/wg_logo.gif');

	$image->getSize();

	$image->getHeight();

	$image->getWidth();

	$image->getFileExtension();

	$image->getName();

	$image->getMimeType();

	$image->getFileName();

	$image->getFilePath();

	$image->getFileDirectory();

*/



class Wg_Image

{

	public $image_path;

	

	/**

	 * Initialize image path

	 *

	 * @param string $image_path

	 */

	public function __construct($image_path)

	{

	    $this->image_path = $image_path;

		if(!file_exists($this->image_path))

		{

			die('File Does Not Exist');

		}

	}

	

	/**

	 * Get image size

	 *

	 * @return int

	 */

	public function getSize()

	{

		return filesize($this->image_path);

	}

	

	/**

	 * Get image height

	 *

	 * @return int

	 */

	public function getHeight()

	{

		list($width, $height) = getimagesize($this->image_path);

		return $height;

	}

	/**

	 * Get image width

	 *

	 * @return int

	 */

	public function getWidth()

	{

		list($width, $height) = getimagesize($this->image_path);

		return $width;

	}

	

	/**

	 * Get File Extension

	 *

	 * @return string

	 */

	public function getFileExtension()

	{

		return substr(strrchr(strtolower($this->getFilePath()), '.'), 1);

	}

	

	/**

	 * Get image name

	 *

	 * @return string

	 */

	public function getName()

	{

		$name = substr($this->getFilePath(), '.', strlen($this->getFilePath()) - 4);

		$names = explode('/', $name);

		return $names = ($names == true) ? $names[count($names) - 1] : $name;

	}

	

	/**

	 * Get image mime type

	 *

	 * @return string

	 */

	public function getMimeType()

	{

		$mime = getimagesize($this->getFilePath());

		return $mime['mime'];

	}

	

	/**

	 * Get image file name

	 *

	 * @return string

	 */

	public function getFileName()

	{

		return $this->getName() . '.' . $this->getFileExtension();//$this->image_path;

	}

	

	/**

	 * Get image file path

	 *

	 * @return string

	 */

	public function getFilePath()

	{

		return $this->image_path;

	}

	

	public function getFileDirectory()

	{

		$dirs = explode('/', $this->image_path);

		$end_key = count($dirs) - 1;

		unset($dirs[$end_key]);

		

		foreach ($dirs as $dir)

		{

			$directory .= $dir . '/';

		}

		

		return $directory;

	}

}

?>

