<?php

class App_Util_File {
	
	
	/**
	 * Devuelve el tipo mime de un archivo dado
	 * @param string $file nombre o ruta completa del archivo
	 * @return string tipo mime del archivo
	 */
	public static function getTipoMime($file) {
		$ctype="";
		$ext = pathinfo($file, PATHINFO_EXTENSION );
		$ext = strtolower($ext);
		switch( $ext ) {
			case "docx": $ctype="application/vnd.openxmlformats"; break;
			case "pptx": $ctype="application/vnd.openxmlformats"; break;
			case "xlsx": $ctype="application/vnd.openxmlformats"; break;
			case "pdf": $ctype="application/pdf"; break;
			case "rtf": $ctype="application/msword"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "rar": $ctype="application/x-rar"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			case "mp3": $ctype="audio/mpeg"; break;
			case "wav": $ctype="audio/x-wav"; break;
			case "mpeg":
			case "mpg":
			case "mpe": $ctype="video/mpeg"; break;
			case "mov": $ctype="video/quicktime"; break;
			case "avi": $ctype="video/x-msvideo"; break;
			case "htm":
			case "html": $ctype="text/html"; break;
			case "txt": $ctype="text/plain"; break;
			case "swf": $ctype="application/x-shockwave-flash"; break;
	
			default:
				case "swf": $ctype="application/octet-stream"; break;
				//FIXME: cambiar por etiqueta lang
				//throw new Exception("No se pudo determinar el tipo mime del archivo.");
			break;
		}
		return $ctype;
	}
		
	/**
	 * Funcion para convertir una cadena de bytes a Mb, Kb y bytes
	 * @param string|int $tamano Tama�o en bytes a formatear
	 * @return string
	 */
	public static function formatSize($size) {
		$size = intval($size);
		if ($size > 1048576) { /* literal.float */
			return $re_sized = sprintf("%01.2f", $size / 1048576) . " MB";
		} elseif ($size > 1024) {
			return $re_sized = sprintf("%01.2f", $size / 1024) . " KB";
		} else {
			return $re_sized = $size . " bytes";
		}
	}

}//fin de la clase
