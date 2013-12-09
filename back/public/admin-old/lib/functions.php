<?
 function gett($var){
	   $retrieved = -1;	
	   if (isset ($_GET[$var]) and $_GET[$var] != '') $retrieved = $_GET[$var];
	   else if(isset ($_POST[$var])) $retrieved = $_POST[$var];
	  // $retrieved = str_replace("/","",$retrieved);
	//   $retrieved = preg_replace('[^a-zA-Z0-9_-]', '', $retrieved);
	  // if (trim($retrieved) == '') $retrieved = -1;

	   return $retrieved;
    }



    function urls_amigables($url) {
        
        $url = strtolower($url);
        
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ','à', 'è', 'ì', 'ò', 'ù', 'ç','ä', 'ë', 'ï', 'ö', 'ü','â', 'ê', 'î', 'ô', 'û','#39');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n','a', 'e', 'i', 'o', 'u', 'c','a', 'e', 'i', 'o', 'u','a', 'e', 'i', 'o', 'u','');
        
        $url = str_replace ($find, $repl, $url);
        
        $find = array(' ', '&', '\r\n', '\n', '+');
        $url = str_replace ($find, '-', $url);
        
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        
        return $url;
        
    }
    
function ObtenerNavegador($user_agent) { 
     $navegadores = array( 
          'Opera' => 'Opera', 
          'Mozilla Firefox'=> '(Firebird)|(Firefox)', 
          'Galeon' => 'Galeon', 
          'Mozilla'=>'Gecko', 
          'MyIE'=>'MyIE', 
          'Lynx' => 'Lynx', 
          'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)', 
          'Konqueror'=>'Konqueror', 
          'Internet Explorer 7' => '(MSIE 7\.[0-9]+)', 
          'Internet Explorer 6' => '(MSIE 6\.[0-9]+)', 
          'Internet Explorer 5' => '(MSIE 5\.[0-9]+)', 
          'Internet Explorer 4' => '(MSIE 4\.[0-9]+)', 
); 
foreach($navegadores as $navegador=>$pattern){ 
       if (eregi($pattern, $user_agent)) 
       return $navegador; 
    } 
return 'Desconocido'; 
}


    function formatear_primera_may($cadena){
//
// Primera letra de la cadena la pone en mayuscula y el resto en min.
// Devuelve la cadena formateada.

$cadena = trim($cadena);
$longitud = strlen($cadena);
$primera_lletra = strtoupper(substr($cadena,0,1));
$resto_lletras = substr($cadena,1,$longitud);
//return strip_slashes($primera_lletra."".$resto_lletras);
return $primera_lletra."".$resto_lletras;
}



function generar_cadena_random($long){ 
//
// Genera cadena aleatoria de minusuclas i numeros.
// Requiere pasarle una variable con la longitud de la cadena que queremos.
// Devuelve la cadena de la longitud dada.

$str = "abcdefghijklmnopqrstuvwxyz1234567890";
$cad = "";
for($i=0;$i<$long;$i++) {
$cad .= substr($str,rand(0,36),1);
}
return $cad;

}


// FORMS
function create_combo_before_number($name){
	echo "<select name=\"".$name."\">

	<option value=\">=\">>=</option>
		<option value=\"<=\"><=</option>
	</select> ";
}

function float_to_sql($valor){
	$valor = str_replace('.','',$valor);
	$valor = str_replace(',','.',$valor);
	return $valor;
}
function generate_seo_link($input,$replace = '-',$remove_words = true,$words_array = array())
{
	//make it lowercase, remove punctuation, remove multiple/leading/ending spaces
	$return = trim(ereg_replace(' +',' ',preg_replace('/[^a-zA-Z0-9\s]/','',strtolower($input))));

	//remove words, if not helpful to seo
	//i like my defaults list in remove_words(), so I wont pass that array
	if($remove_words) { $return = remove_words($return,$replace,$words_array); }

	//convert the spaces to whatever the user wants
	//usually a dash or underscore..
	//...then return the value.
	return str_replace(' ',$replace,$return);
}

/* takes an input, scrubs unnecessary words */
function remove_words($input,$replace,$words_array = array(),$unique_words = true)
{
	//separate all words based on spaces
	$input_array = explode(' ',$input);

	//create the return array
	$return = array();

	//loops through words, remove bad words, keep good ones
	foreach($input_array as $word)
	{
		//if it's a word we should add...
		if(!in_array($word,$words_array) && ($unique_words ? !in_array($word,$return) : true))
		{
			$return[] = $word;
		}
	}

	//return good words separated by dashes
	return implode($replace,$return);
}

function purificar($cadena){
//
// Limpia todas las letras problematicas de una cadena.
// Requiere la cadena
// Devuelve la cadena formateada y en minusculas.
// Recomendado usarlo para cuando trabajamos con archivos.
// Puede dar problemas el hecho de que cambia hasta los espacios en blanco por _

$cadena = trim($cadena);
$cadena = strtolower($cadena);

   $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ','à', 'è', 'ì', 'ò', 'ù', 'ç','ä', 'ë', 'ï', 'ö', 'ü','â', 'ê', 'î', 'ô', 'û','#39');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n','a', 'e', 'i', 'o', 'u', 'c','a', 'e', 'i', 'o', 'u','a', 'e', 'i', 'o', 'u','');
        $cadena = str_replace ($find, $repl, $cadena);
        
$cadena = str_replace(" ", "_", $cadena);
$cadena = str_replace("%20","_",$cadena);
$cadena = preg_replace('/[^(\x20-\x7F)]*/','', $cadena);


return $cadena; 

}

function generar_nombre_archivo($filename){


// cuidado con extensiones como .jpeg (4 chars)
$punto_pos = strrpos ( $filename, ".");
$soloname = substr($filename,0,$punto_pos );
$soloname = purificar($soloname);
$ext = substr($filename,$punto_pos + 1, strlen($filename) - $punto_pos);
$new_code = generar_cadena_random(7);

return $soloname."_".$new_code.".".$ext;

}