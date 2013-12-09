<?php

class App_View_Helper_Box extends Zend_View_Helper_Abstract {
	
	public function box($contenido, $titulo="") {
		$html = '<div class="ui-widget-content ui-corner-all" style="width:350px;min-height:130px;padding:15px;">';
		if(!empty($titulo)) {
			$html.='<h3 class="ui-widget-header ui-corner-all" style="margin:0;padding:0.4em;text-align:center;">'.$titulo.'</h3>';
		}
		$html.='<p>'.$contenido.'</p>';
		$html.='</div>';
		return $html;
	}
}
