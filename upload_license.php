<?php 
$path =  $_SERVER['DOCUMENT_ROOT'].'/';
	if ($_FILES["fileField"]["error"] > 0)
	{
		echo "Error: " . $_FILES["fileField"]["error"] . "<br>";
	}
	else
	{
		 move_uploaded_file($_FILES["fileField"]["tmp_name"], $path . $_FILES["fileField"]["name"]);
		?>
		<center>Successfully uploaded<br />
		<a href="http://sprinthr.apps"> Click Here to load</center>
        </a>
<?php
	}
?>
