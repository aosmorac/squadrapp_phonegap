<?php

class App_Util_Tabs {
	
	private $_tabs;
	private $_actual = "";
	
	function __construct() {
		$this->_tabs = array ();
	}
	
	function addView($titleTab, $pagina_enlace, $parametros = "", $title="") {
		$this->_tabs [$titleTab] ["content"] = $pagina_enlace;
		$this->_tabs [$titleTab] ["param"] = $parametros;
		$this->_tabs [$titleTab] ["type"] = "view";
		$this->_tabs [$titleTab] ["actual"] = false;
		$this->_tabs [$titleTab] ["check"] = false;
                $this->_tabs [$titleTab] ["pending"] = false;
		$this->_tabs [$titleTab] ["title"] = $title;
	}
	
	function addContent($titleTab, $contenido = "") {
		$this->_tabs [$titleTab] ["content"] = $contenido;
		$this->_tabs [$titleTab] ["param"] = "";
		$this->_tabs [$titleTab] ["type"] = "content";
		$this->_tabs [$titleTab] ["actual"] = false;
		$this->_tabs [$titleTab] ["check"] = false;
                $this->_tabs [$titleTab] ["pending"] = false;
		$this->_tabs [$titleTab] ["title"] = $titleTab;
	}
	
	function deleteTab($titleTab) {
		unset ( $this->_tabs [$titleTab] );
		if ($this->_actual == $titleTab)
			$this->_actual = "";
	}
	
	function checker($titleTab) {
		$this->_tabs [$titleTab] ["check"] = true;
	}
        
        function pending($titleTab) {
		$this->_tabs [$titleTab] ["pending"] = true;
	}
	
	function actual($titleTab) {
		$this->_actual = $titleTab;
	}
	
	function contentTab($titleTab, $contenido) {
		$this->_tabs [$titleTab] ["content"] = $contenido;
		$this->_tabs [$titleTab] ["type"] = "content";
	}
	
	function content($contenido) {
		$this->contentTab($this->_actual, $contenido);
	}
	
	function renderElement($orientation="",$reload=true) {
		$baseUrl = Zend_Controller_Front::getInstance ()->getBaseUrl();
		$uniId = uniqid();
		$DivName="{$orientation}Tabs{$uniId}";
		$Div=" id=\"{$DivName}\"";
		$ul="<ul>\n";
		$ul_="</ul>\n";
		$li="<li>\n";
		$li_="</li>\n";
		$arr_keys = array_keys ( $this->_tabs );
		$tabActual = 0;
		$menuTab = "<div{$Div}>\n";
		$menuTab .= "{$ul}";
		for($i = 0; $i < count ( $this->_tabs ); $i ++) {
			$parametros = $this->_tabs [$arr_keys [$i]] ["param"];
			$type = $this->_tabs [$arr_keys [$i]] ["type"];
			$content = $this->_tabs [$arr_keys [$i]] ["content"];
			$actual = $this->_tabs [$arr_keys [$i]] ["actual"];
			$checker = $this->_tabs [$arr_keys [$i]] ["check"];
                        $pending = $this->_tabs [$arr_keys [$i]] ["pending"];
			$title = $this->_tabs [$arr_keys [$i]] ["title"];
			if ($parametros != "") {
                           if(strpos("_lock/", $parametros)===false){
                              $parametros = App_Util_SafeUrl::encryptString($parametros);
                           }
			   $content .= "{$parametros}";
			}
			if (($actual == "Si") || ($this->_actual == $arr_keys [$i])) {
				$tabActual = $i;
			}
			$imgChecker="";
			if("Si"==$checker){
				$imgChecker=" <img src=\"{$baseUrl}/img/success.png\" align=\"absmiddle \">";
			}
                        $imgPending="";
			if("Si"==$pending){
				$imgPending=" <img width=\"7px \" height=\"12px \" src=\"{$baseUrl}/img/Pendiente.gif\" align=\"absmiddle \" >";
			}
                        
                        
			if ("view" == $type) {
				if ($content != "#") {
					$menuTab .= "{$li}<a href=\"#{$DivName}_{$i}\" id=\"{$DivName}_{$i}_A\" onclick=\"javascript:openpage('{$baseUrl}{$content}','#{$DivName}_{$i}');\" title=\"{$title}\">{$arr_keys[$i]}{$imgChecker}{$imgPending}</a>{$li_}\n";
				} else {
					$menuTab .= "{$li}<a href=\"#\">{$arr_keys[$i]}{$imgChecker}{$imgPending}</a>{$li_}\n";
				}
			} else {
				$menuTab .= "{$li}<a href=\"#{$DivName}_{$i}\">{$arr_keys[$i]}{$imgChecker}{$imgPending}</a>{$li_}\n";
			}
		}
		$menuTab .= "{$ul_}";
		for($i = 0; $i < count ( $this->_tabs ); $i ++) {
			$type = $this->_tabs [$arr_keys [$i]] ["type"];
			$content = $this->_tabs [$arr_keys [$i]] ["content"];
			$menuTab .= "<div id=\"{$DivName}_{$i}\">\n";
			if ("content" == $type) {
				$menuTab .= "{$content}<br/><br/><br/>";
			} elseif ($content != "#") {
				$menuTab .= "<br/><br/><br/><br/><br/><br/>";
			}
			$menuTab .= "</div>\n";
		}
		$menuTab .= "</div>\n";
		$menuTab .= "<script type=\"text/javascript\">\n";
		if ($tabActual > 0) {
			$menuTab .= "var tabs = $(\"#{$DivName}\").tabs();\n";
			$menuTab .= "tabs.tabs('select', {$tabActual});\n";
		}
		if ("horizontal"!=$orientation){
			$menuTab .= "$(\"#{$DivName}\").tabs().addClass('ui-tabs-{$orientation} ui-helper-clearfix');\n";
			//$menuTab .= "$(\"#{$DivName}\").removeClass('ui-corner-top').addClass('ui-corner-left');\n";
		}
		/*$menuTab .= "$(function() {\n";
		$menuTab .= "$(\"#{$DivName}\").tabs().show();\n";
		$menuTab .= "});\n";*/
		$menuTab .= "function openpage(url,div){\n
                    $(div).html('');\n
		    $(div).load(url);\n
                    ";
                if(!$reload){
                    $menuTab .= "      $(div+'_A').removeAttr('onclick');";
                }
                $menuTab .= "
		}\n
		\n";
		$menuTab .= "</script>\n";
		return $menuTab;
	}
	
}
