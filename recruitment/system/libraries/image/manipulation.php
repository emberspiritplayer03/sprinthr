<?php

/**

* WGFramework Image_Manipulation class

*

* This image manipulation resizes image to make thumb

*

* @version 1.0.0

* @package WGFramework

* @author Webgroundz

* @category Library

* @date created Jul-18-07 

* @last modified Sept-1-08     

*/



// Sample Usage:

/********************

	$image = new Wg_Image('images/wg_logo.gif');

	$image_manipulation = new Wg_Image_Manipulation($image);

	$image_manipulation->makeThumb('thumb', 'suffix'); // default is 'prefix'

	$image_manipulation->setDirectory('mydir/mydir2');

	$image_manipulation->resize(25, 25);

*/



class Wg_Image_Manipulation

{

	public $thumb_prefix = 'thumb_';	

	public $image_dir;	

	public $new_width;

	public $new_height;

	

	private $is_thumb_prefix;

	private $is_thumb;

	

	protected $destination_dir;

	/**

	 * Data type object

	 *

	 * @var object

	 */

	protected $image;

		

	/**

	 * Initialize object using Type Hinting

	 *

	 * @param object $image

	 */

	public function __construct($image)

	{

		try 

		{

			if(!is_object($image))

			{

				throw new Exception("Your initialization does not instantiate an object");

			}

			else 

			{

				$this->image = $image;

			}	

		}

		catch(Exception $e)

		{

			 echo '<b>Warning</b> : ' . $e->getMessage();	

		}	



	}

	

	/**

	 * Process Resizing of Images

	 * 

	 * @param int $width

	 * @param int $height

	 * @return bool 

	 */

	public function resize($width = null, $height = null)

	{

		$width = ($width == null) ? $this->image->getWidth() : $width;

		$height = ($height == null) ? $this->image->getHeight() : $height;



		// Get new sizes

		$this->new_width = $width;

		$this->new_height = $height;

		

		//Load image

		$thumb = $this->doThumb();

		$source = $this->getImageSource();



		//Copy and resize part of an image

		imagecopyresized(

						  $thumb,

						  $source, 

						  0, 0, 0, 0, 

						  $this->new_width, 

						  $this->new_height, 

						  $this->image->getWidth(), 

						  $this->image->getHeight()

						);

		



		

		// check if image is thumb

		if($this->is_thumb)

		{			

			// add prefix or prefix to the filename. example: "thumb_hello.jpg"

			$filename = $this->image->getFileName();

			if ($this->is_prefix)

			{

				$path = $this->getDirectory() . $this->thumb_prefix . $filename;		

			}

			else

			{

				$base_name = substr($filename, 0, strripos($filename, '.'));

				$extension = end(explode(".", $filename));

				$path = $this->getDirectory() . $base_name . $this->thumb_prefix . '.' . $extension;

			}

		}

		else 

		{

			$path = $this->getDirectory() . $this->image->getFileName();

		}

		

		return $this->executeImageThumb($thumb, $path);

	} 

	

	/**

	 * Create a new true color

	 * image identifier representing a black image of the specified size.

	 *

	 * @param int $new_height

	 * @param int $new_width

	 */

	private function doThumb()

	{

		return imagecreatetruecolor($this->new_width, $this->new_height);

	}

	

	/**

	 * Get Image thumb depending on the file extension

	 *

	 * @return string output image to browser or file

	 */

	private function executeImageThumb($thumb, $file_path)

	{

		switch($this->image->getFileExtension())

		{

			case 'jpeg':

				return imagejpeg($thumb, $file_path);

			break;

			

			case 'jpg':

				return imagejpeg($thumb, $file_path);

			break;	

			

			case 'png':

				return imagepng($thumb, $file_path);

			break;	

			

			case 'gif':

				return imagegif($thumb, $file_path);

			break;	

		}

	}

	

	/**

	 * Send content type that passes to header

	 *

	 * @return string

	 */

	public function sendHttpContentType()

	{

		header('Content-type: ' . $this->image->getMimeType() . '');

	}

	

	/**

	 * Create a new image from file or URL

	 *

	 * @return string image identifier

	 */

	private function getImageSource()

	{

		

		switch($this->image->getFileExtension())

		{

			case 'jpeg':

				return imagecreatefromjpeg($this->image->getFilePath());

			break;

			

			case 'jpg':

				return imagecreatefromjpeg($this->image->getFilePath());

			break;	

			

			case 'png':

				return imagecreatefrompng($this->image->getFilePath());

			break;	

			

			case 'gif':

				return imagecreatefromgif($this->image->getFilePath());

			break;

			

			default:

				return imagecreate($this->image->getFilePath());

		}

	}

	

	/**

	 * Set directory

	 *

	 * @param string $image_dir

	 */

	public function setDirectory($image_dir)

	{

		$this->destination_dir = $image_dir;		

		if(strlen($this->destination_dir) > 0)

		{

			$dir = explode('/', $this->destination_dir);

			foreach ($dir as $dir)

			{

				$directory .= $dir . '/';

				if(!is_dir($directory))

				{

					$this->createDirectory($directory, 0777);

				}

			}	

		}

	}

	

	/**

	 * Get directory

	 *

	 * @return string

	 */

	public function getDirectory()

	{

		if ($this->destination_dir)

		{

			return $this->destination_dir . '/';

		}

		else

		{

			// get the original directory

			return $this->image->getFileDirectory();

		}

	}

	

	/**

	 * Make the image thumb with the prefix example: 'thumb_mypic.jpg'

	 *

	 * @param string $thumb_prefix prefix for image. such as 'thumb_'

	 */

	public function makeThumb($thumb = null, $prefix = 'prefix')

	{

		if ($thumb)

		{			

			$this->thumb = $thumb;

			$this->thumb_prefix = $thumb;

		}

		$this->is_prefix = ($prefix == 'prefix') ? true : false ;

		$this->is_thumb = true;

	}

	

	/**

	 * Create directory if not exist

	 *

	 * @param string $dir

	 * @param int $permission

	 * @return string

	 */

	private function createDirectory($dir, $permission)

	{

		mkdir($dir, $permission);

	}

	

	public function setLibrary()

	{

	  

	}

}

?>