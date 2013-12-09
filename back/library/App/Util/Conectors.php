<?php

class App_Util_Conectors {
	
	private $conectors;
	private $actual = "";
	function __construct() {
		$this->conectors = array ();
	}
	
	function add($textConectors, $linkPage, $params = "",$container="",$Id="") {
		$this->conectors [$textConectors] ["url"] = $linkPage;
		$this->conectors [$textConectors] ["param"] = $params;
		$this->conectors [$textConectors] ["check"] = "No";
		$this->conectors [$textConectors] ["actual"] = "No";
		$this->conectors[$textConectors] ["container"] = $container;
                $this->conectors [$textConectors] ["id"] = $Id;
	}
	
	
	function delete($textConectors) {
		unset ( $this->conectors [$textConectors] );
		if ($this->actual == $textConectors)
			$this->actual = "";
	}
	
	function checkear($textConectors) {
		$this->buttons [$textConectors] ["check"] = "Si";
	}
	
	function actual($textConectors) {
		$this->actual = $textConectors;
	}
	
	function renderElement($orientation="") {
		$baseUrl = Zend_Controller_Front::getInstance ()->getBaseUrl();
		$arr_keys = array_keys ( $this->conectors );
		$actualButton = 0;
		$titleTable="";
		if("vertical"==$orientation){
			$beginTable="<table class=\"buttons buttonsV vertical\ >\n";
			$endTable="</table>";
			$beginButton="<tr><td>";
			$endButton="<td></tr>\n";
		}else{
			$beginTable="<div width='100%'  align='right'><table class=\"buttons horizontal\">\n<tr>";
			$endTable="</tr>\n</table></div>";
			$beginButton="<td>";
			$endButton="<td>\n";
			
			$sessionTabButton = new Zend_Session_Namespace("TABBUTTON");
			$title=$sessionTabButton->nameButtonH;
			if(!empty($sessionTabButton->nameButtonV)){
			    $title.=" :: ".$sessionTabButton->nameButtonV;
			}
		    if(!empty($sessionTabButton->bameButtonH)){
		        $titleTable="<div width='100%' align='left'><h2>{$title}</h2></div>";
		    }
		}
		
		$menuBoton = "{$beginTable}";

		for($i = 0; $i < count ( $this->conectors ); $i ++) {
			$parametros = $this->conectors [$arr_keys [$i]] ["param"];
			$url = $this->conectors [$arr_keys [$i]] ["url"];
			$actual = $this->conectors[$arr_keys [$i]] ["actual"];
			$checkeado = $this->conectors [$arr_keys [$i]] ["check"];
                        $container = $this->conectors [$arr_keys [$i]] ["container"];
                        $id = $this->conectors [$arr_keys [$i]] ["id"];
                        
			if ($parametros != "") {
				$url .= "{$parametros}";
			}
			$classActual="";
			if (($actual == "Si") || ($this->actual == $arr_keys [$i])) {
				$actualButton = $i;
				$classActual=" class=\"actual\"";
			}
			$imagencheckeado="";
			if("Si"==$checkeado){
				$imagencheckeado=" <img src=\"{$baseUrl}/img/success.png\">";
			}
                        $classContainer="";
                        $nameContainer="";
                        if(!empty($container)){
                            $classContainer='class="button_loader"';
                            $nameContainer=' nameContainer="'.$container.'"';
                        }
                        if ($url != "#") {
                        		$url = str_replace($baseUrl, "", $url);
                                $menuBoton .= "{$beginButton}<a id=\"{$id}\" {$classContainer}{$classActual} href=\"{$baseUrl}{$url}\"{$nameContainer}>{$imagencheckeado}{$arr_keys[$i]}</a>{$endButton}\n";
                        } else {
                                $menuBoton .= "{$beginButton}<a id=\"{$id}\" {$classContainer}href=\"#\" {$nameContainer}>{$imagencheckeado}{$arr_keys[$i]}</a>{$endButton}\n";
                        }

		}
                $script ='<script type="text/javascript">
                            $(".button_loader").click(function(e){
                                e.preventDefault();
                                var _href= $(this).attr("href");
                                var _container = $(this).attr("nameContainer");
                                $("#"+_container).empty().html("<center><img src=\"'.$baseUrl.'/img/loading16x16.gif\">Cargando...</center>");
                                $("#"+_container).load(_href);
                                $("a.actual").removeClass("actual");
                                $(this).addClass("actual");
                            });
                        </script>';
		$menuBoton .= "{$endTable}{$titleTable}{$script}";

		return $menuBoton;
	}
}

?>