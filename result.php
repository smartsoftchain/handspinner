<?php
	$files = "";
	$fileinfo = pathinfo($_FILES["file"]["name"]);
	$fileext = strtoupper($fileinfo["extension"]);
	$files = date("YmdHis").".".strtolower($fileext);
	if(move_uploaded_file($_FILES['file']['tmp_name'], './upload/'.$files)){
		echo $files;
	}
?>