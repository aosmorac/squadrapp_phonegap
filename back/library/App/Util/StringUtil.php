<?php

/**
 * Enter description here ...
 * @author mPerez
 *
 */
class App_Util_StringUtil {
	
	function __construct() {
	
	}
	
    /**
     * startsWith
     * Prueba si un texto inicia con uno determinado
     *
     * @param     string
     * @param     string
     * @return    bool
     */
    static public function startsWith($haystack, $needle){
        return strpos($haystack, $needle) === 0;
    }
	
    /**
     * Quitar Acentos de una frase, tildes
     * 
     * @param string $string Cadena a quitar los acentos de las vocales
     * @return string $string Cadena sin acentos
     */
    static public function accentsRemove($string){
		$tofind = "áãäåāăąÁÂÃÄÅĀĂĄèééêëēĕėęěĒÉĔĖĘĚìíîïìĩīĭÌÍÎÏÌĨĪĬóôõöōŏőÒÓÔÕÖŌŎŐùúûüũūŭůÙÚÛÜŨŪŬŮ";
		$replac = "aaaaaaaAAAAAAAAeeeeeeeeeeEEEEEEiiiiiiiiIIIIIIIIoooooooOOOOOOOOuuuuuuuuUUUUUUUU";
		$text=utf8_encode((strtr(utf8_decode($string),utf8_decode($tofind),$replac)));
		//$texto= strtolower($texto);
		return $text;		
	}
        
   /**
     * Agrega Acentos de una frase, tildes
     * 
     * @param string $string Cadena a quitar los acentos de las vocales
     * @return string $string Cadena sin acentos
     */
    static public function accentsAdd($string){
		$tofind = array('a','A','e','E','i','I','o','O','u','U','n','N');
		$replac = array('á','Á','é','É','í','Í','ó','Ó','ú','Ú','ñ','Ñ');
		$text=  str_replace($tofind,$replac,$string);
		//$texto= strtolower($texto);
		return $text;		
	}
    
	function accentsHTMLRemove($string){
		$string = str_replace("&aacute;","á",$string);
		$string = str_replace("&eacute;","é",$string);
		$string = str_replace("&iacute;","í",$string);
		$string = str_replace("&oacute;","ó",$string);
		$string = str_replace("&uacute;","ú",$string);
		$string = str_replace("&ntilde;","ñ",$string);
		$string = str_replace("&Aacute;","Á",$string);
		$string = str_replace("&Eacute;","É",$string);
		$string = str_replace("&Iacute;","Í",$string);
		$string = str_replace("&Oacute;","Ó",$string);
		$string = str_replace("&Uacute;","Ú",$string);
		$string = str_replace("&Ntilde;","Ñ",$string);
		$string = str_replace("\""," ",$string);
		return $string;
	}
	
	static function dateFormat($date,$type=1){         
        switch ($type) {
            case 1:
                $dateF = date ( "d/m/Y" , strtotime($date));
            break;
            
            case 2:
                $dateF = date ( "j/m/ g:i a" , strtotime($date));
                $dateF = str_replace("/01/", " de Enero ", $dateF);
                $dateF = str_replace("/02/", " de Febrero ", $dateF);
                $dateF = str_replace("/03/", " de Marzo ", $dateF);
                $dateF = str_replace("/04/", " de Abril ", $dateF);
                $dateF = str_replace("/05/", " de Mayo ", $dateF);
                $dateF = str_replace("/06/", " de Junio ", $dateF);
                $dateF = str_replace("/07/", " de Julio ", $dateF);
                $dateF = str_replace("/08/", " de Agosto ", $dateF);
                $dateF = str_replace("/09/", " de Septiembre ", $dateF);
                $dateF = str_replace("/10/", " de Octubre ", $dateF);
                $dateF = str_replace("/11/", " de Noviembre ", $dateF);
                $dateF = str_replace("/12/", " de Diciembre ", $dateF);                
            break;
            case 3:
                $dateF = date ( "g:i a" , strtotime($date));
            break;
            default:
                $dateF="";
            break;
        }
	    return $dateF;
	}

        
        
        
        /**
        * Truncates the given string at the specified length.
        *
        * @param string $str The input string.
        * @param int $width The number of chars at which the string will be truncated.
        * @return string
        */
       static public function truncate($string, $limit, $continue="")
        {
            $text = $string." ";
            $text = substr($text,0,$limit);
            $text = substr($text,0,strrpos($text,' '));
            $text = $text.$continue;
            return $text;
        }
  
}
