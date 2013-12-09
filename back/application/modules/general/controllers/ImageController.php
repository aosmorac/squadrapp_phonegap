<?php
/**
 * class CronController
 * 
 */
class General_ImageController extends Zend_Controller_Action
{
	
        
	
    public function init(){
        
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {

    }
	
    
    
    /**
     * Action Registro
     * Entra al haber registro
     * No realiza funcion
     * 
     */
	public function squadrappFieldsMapAction ()
    {
            $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
            $html_code = "
<html>
<head>
 <title>My test title</title>
 <style>
 body {
 font-family:verdana;
 font-size:11px;
 color:black
 }
 </style>
</head>
<body>
 this is the body
</body>
</html>";

// Create the image
$img = imagecreate("300", "600");
imagecolorallocate($img,0,0,0);
$c = imagecolorallocate($img,70,70,70);
imageline($img,0,0,300,600,$c);
imageline($img,300,0,0,600,$c);

$white = imagecolorallocate($img, 255, 255, 255);
imagettftext($img, 9, 0, 1, 1, $white, "VERDANA.TTF", $html_code);

// Display the image
header("Content-type: image/jpeg");
imagejpeg($img);
    }
    
       

    
}
?>
