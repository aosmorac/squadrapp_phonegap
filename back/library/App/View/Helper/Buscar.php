<?php

class App_View_Helper_Buscar extends Zend_View_Helper_Abstract {
	
	public function buscar($action,$value="Buscar...") {
		$html="<form id=\"frmBuscar\" name=\"frmBuscar\" action=\"{$action}\" method=\"post\">";
		$html.="<div align=\"right\">";
		$html.="<input type=\"text\" class=\"search\" name =\"buscar\" id=\"buscar\" value=\"{$value}\"/>";
		$html.="</div>";
		$html.="</form>";
		return $html;
	}
}
