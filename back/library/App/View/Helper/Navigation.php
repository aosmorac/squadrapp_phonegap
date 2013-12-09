<?php

class App_View_Helper_Navigation extends Zend_View_Helper_Abstract {
	function __construct() {
	}
	
	function Navigation($navs) {
		$baseUrl = Zend_Controller_Front::getInstance ()->getBaseUrl();
		//print_r($navs);
		$nav_array = $navs->toArray ();
		$html = "";
		$cantidad = count($nav_array);
		if ($cantidad > 0) {
			$html = "<ul id=\"breadcrumbs\" class=\"xbreadcrumbs\">";
                        if($nav_array[0]["url"]=="#")
                            $html .= "<li><a class=\"home\" href=\"#\" title=\"{$nav_array[0]["description"]}\">{$nav_array[0]["label"]}</a></li>\n";
			else
                            $html .= "<li><a class=\"home\" href=\"{$baseUrl}{$nav_array[0]["url"]}\" title=\"{$nav_array[0]["description"]}\">{$nav_array[0]["label"]}</a></li>\n";
			for($i = 1; $i < $cantidad; $i++) {
				if ($i < ($cantidad - 1)) {
					if ($nav_array[$i]["url"] == "") {
						throw new Exception ( "La navegacion debe tener una URL", 1002 );
					}
                                        $nav_array[$i]["url"]=str_replace($baseUrl, "", $nav_array[$i]["url"]);
					$html .= "<li><a href=\"{$baseUrl}{$nav_array[$i]["url"]}\" title=\"{$nav_array[$i]["description"]}\">{$nav_array[$i]["label"]}</a></li>\n";
				} else {
					$html .= "<li class=\"current\">{$nav_array[$i]["label"]}</li>";
				}
			}
			$html .= "</ul><br/>\n";
		}
		$html.= "<div style=\"clear:both;\"></div>";
		return $html;
	}
}
