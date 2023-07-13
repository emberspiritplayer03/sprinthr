	<style type="text/css">

		/* By defining CKFinderFrame, you are able to customize the CKFinder frame style */
		.CKFinderFrame
		{
			border: solid 2px #e3e3c7;
			background-color: #f1f1e3;
		}

	</style>
	<script type="text/javascript">

// This is a sample function which is called when a file is selected in CKFinder.
function ShowFileInfo( fileUrl, data )
{
	var msg = 'The selected URL is: ' + fileUrl + '\n\n';
	// Display additional information available in the "data" object.
	// For example, the size of a file (in KB) is available in the data["fileSize"] variable.
	if ( fileUrl != data['fileUrl'] )
		msg += 'File url: ' + data['fileUrl'] + '\n';
	msg += 'File size: ' + data['fileSize'] + 'KB\n';
	msg += 'Last modifed: ' + data['fileDate'];

	alert( msg );
}

	</script>
<?php 


	
	//editor 3
	$ckeditor3 = new CKEditor() ;
	$ckeditor3->basePath	= BASE_FOLDER. 'application/libraries/ckeditor/';

	$config2['skin'] = 'v2'; //kama,v2,office2003
	/*$config2['toolbar'] = array(
		  array( 'Source', '-', 'Bold', 'Italic', 'Underline', 'Strike' ),
		  array( 'Image', 'Link', 'Unlink', 'Anchor' )
	  );*/
	$config2['height'] = '100px';
	$config2['width'] = '470px';
	CKFinder::SetupCKEditor( $ckeditor3, BASE_FOLDER.'application/libraries/ckeditor/ckfinder/');
	$ckeditor3->editor('CKEditor3', $initialValue,$config2);
	

	
	
	
	$finder = new CKFinder() ;
	$finder->BasePath = BASE_FOLDER.'application/libraries/ckeditor/ckfinder/' ;	// The path for the installation of CKFinder (default = "/ckfinder/").
	$finder->SelectFunction = 'ShowFileInfo' ;
	$finder->Create() ;

	
?>