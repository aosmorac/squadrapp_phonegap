<?php

class App_Util_Buttons {
	
	private $buttons;
	private $actual = "";
	function __construct() {
		$this->buttons = array ();
	}
	
	function agregar($textButton, $linkPage, $params = "",$container="",$Id="") {
		$this->buttons [$textButton] ["url"] = $linkPage;
		$this->buttons [$textButton] ["param"] = $params;
		$this->buttons [$textButton] ["check"] = "No";
		$this->buttons [$textButton] ["actual"] = "No";
		$this->buttons [$textButton] ["container"] = $container;
                $this->buttons [$textButton] ["id"] = $Id;
	}
	
	
	function eliminar($textButton) {
		unset ( $this->buttons [$textButton] );
		if ($this->actual == $textButton)
			$this->actual = "";
	}
	
	function checkear($textButton) {
		$this->buttons [$textButton] ["check"] = "Si";
	}
	
	function actual($textButton) {
		$this->actual = $textButton;
	}
	
	function renderElement($orientation="") {
		$baseUrl = Zend_Controller_Front::getInstance ()->getBaseUrl();
		$arr_keys = array_keys ( $this->buttons );
		$actualButton = 0;
		$titleTable="";
		if("vertical"==$orientation){
			$beginTable="<table class=\"buttons buttonsV vertical\">\n";
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

		for($i = 0; $i < count ( $this->buttons ); $i ++) {
			$parametros = $this->buttons [$arr_keys [$i]] ["param"];
			$url = $this->buttons [$arr_keys [$i]] ["url"];
			$actual = $this->buttons [$arr_keys [$i]] ["actual"];
			$checkeado = $this->buttons [$arr_keys [$i]] ["check"];
                        $container = $this->buttons [$arr_keys [$i]] ["container"];
                        $id = $this->buttons [$arr_keys [$i]] ["id"];
                        
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