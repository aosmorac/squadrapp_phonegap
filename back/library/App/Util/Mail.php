<?php

class App_Util_Mail {

	private static function init(&$Mail) {
		$Mail = new Zend_Mail();
		//FIXME: cambiar lo que esta quemado para pasarlo a config
	}

	public static function mail($De, $Para, $Asunto, $CuerpoDeMensaje, $NombreDe="", $Cc=array()) {
		self::init($Mail);
		$Mail->setFrom("{$De}", "{$NombreDe}");
		$Mail->addTo($Para);
		$Mail->setSubject("{$Asunto}");
		$Mail->setBodyHtml($CuerpoDeMensaje);
		$Mail->addCc($Cc);
		$Mail->send();
	}

	public static function mailAttachment($NombreAdjunto, $TipoAdjunto, $Cc, $De, $Para, $Asunto, $CuerpoDeMensaje, $NombreDe="",$cco=false) {
		self::init($Mail);
		$Mail->setFrom("{$De}", "{$NombreDe}");
		$Mail->addTo($Para);
		$Mail->setSubject("{$Asunto}");
		$Mail->setBodyHtml("{$CuerpoDeMensaje}");
		$Mail->addCc($Cc);
		if($cco) $Mail->addBcc($cco);

		$filename = basename("{$NombreAdjunto}");
		$at = new Zend_Mime_Part("{$TipoAdjunto}");
		$at->type = App_Util_File::getTipoMime($filename);
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding = Zend_Mime::ENCODING_BASE64;
		$at->filename = $filename;
		$Mail->addAttachment($at);
		$Mail->send();
	}

	public static function mailAttachments($asunto,$mensaje,$from=array("name"=>"Squadrapp","mail"=>"info@squadrapp.com"),$to=array(),$cc=array(),$cco=array(),$Adjuntos=array()) {
		self::init($Mail);
		$Mail = new Zend_Mail();
		$Mail->setFrom("{$from["mail"]}", "{$from["name"]}");
		if(is_array($to)){
			foreach ($to as  $value) {
				if(is_array($value)){
					$Mail->addTo("{$value["mail"]}", "{$value["name"]}");
				}else{
					$Mail->addTo($value);
				}
			}
		}
		else{
			$Mail->addTo($to);
		}

		if(is_array($cc)){
			foreach ($cc as  $value) {
				if(is_array($value)){
					$Mail->addCc("{$value["mail"]}", "{$value["name"]}");
				}else{
					$Mail->addCc($value);
				}
			}
		}
		else{
			$Mail->addCc($cc);
		}
		
		if(is_array($cco)){
			foreach ($cco as  $value) {
				$Mail->addBcc($value);
			}
		}
		else{
			$Mail->addBcc($cco);
		}
		$Mail->setSubject("{$asunto}");
		$Mail->setBodyHtml("{$mensaje}");
		foreach ($Adjuntos as  $key => $value 	) {
			$filename = basename("{$key}");
			$at = new Zend_Mime_Part("{$value}");
			$at->type = App_Util_File::getTipoMime($filename);
			$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
			$at->encoding = Zend_Mime::ENCODING_BASE64;
			$at->filename = $filename;
			$Mail->addAttachment($at);
		}
		$Mail->send();
	}

	public static function check_email_address($email) {


		// First, we check that there's one @ symbol,
		// and that the lengths are right.
		if (!preg_match("[^[^@]{1,64}@[^@]{1,255}$]", $email)) {
			// Email invalid because wrong number of characters
			// in one section or wrong number of @ symbols.
			return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
			if
			(!preg_match("[^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$]", $local_array[$i])) {
				return false;
			}
		}
		// Check if domain is IP. If not,
		// it should be valid domain name
		if (!preg_match("[^\[?[0-9\.]+\]?$]", $email_array[1])) {
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2) {
				return false; // Not enough parts to domain
			}
			for ($i = 0; $i < sizeof($domain_array); $i++) {
				if
				(!preg_match("[^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|↪([A-Za-z0-9]+))$]", $domain_array[$i])) {
					return false;
				}
			}
		}
		return true;
	}

}