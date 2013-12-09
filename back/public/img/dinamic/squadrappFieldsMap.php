<?php
/*
image.php
*/
    header("Content-type: image/png");
	$cadena = $_GET['label'];
	$im     = imagecreatefrompng("icon_sqbranch_map.png");
	$px     = (imagesx($im) - 7.5 * strlen($cadena)) / 2;
	imagestring($im, 3, $px, 9, $cadena, $naranja);
	imagepng($im);
	imagedestroy($im);
?>