<?php
class App_View_Helper_MenuPrincipal extends Zend_View_Helper_Abstract
{
    private $baseUrl;
    private $textGlobal;
    function __construct ()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $this->baseUrl = ($frontController->getBaseUrl());
        $this->textGlobal = App_Util_Language::getTextLanguage();
    }
    public function menuPrincipal ()
    {        
        if (!App_User::isLogged()){
            return false;
        }
        $perfil = App_Perfil::getInstance(); 
        
        if(!isset($perfil)){
                return;
        }
        $loggedUser = new Zend_Session_Namespace("loggedUser");
    	$MenuActual = $loggedUser->MenuActual;
		
        $array_menu= $perfil->getMenus();
        if(!empty($MenuActual)){
        $MenuActual=$perfil->getFather($MenuActual);}
        $array_menu = $perfil->getMenus();
        if(!isset($array_menu) || (count($array_menu)==0)){
                return "";
        }
        return $this->RenderElement($array_menu, $MenuActual ,0);
    }

    	private function RenderElement($array_menu,$actual, $nivel) {
                $textGlobal = $this->textGlobal->module;
		$class_menu = "";
		$class_link = "";
		//$class_menu_border="";
		$class_menu_ul = "";
		$class_li="";
		$counter=0;
		if ($nivel == 0) {
					//$class_menu_border=" class=\"\"";
                    $class_menu = " id=\"menu\"";
                    $class_menu_ul = " class= \"menu ui-widget-header ui-corner-all\"";
                    $class_link = " class=\"parent\"";
		}
		$url="";
		$text = "<div{$class_menu}>";
		$text .= "<ul{$class_menu}{$class_menu_ul}>";
		foreach ( $array_menu as $key => $item ) {
                    $counter++;
                    $url='';
                    $paramMenu = "/menu/{$key}";
                    if (array_key_exists ( "url", $item )) {
                          if ($item ["url"] != "") {
                                      if (!(empty($item ["param"]) || is_null($item ["param"]) || $item ["param"]=="")) {
                                              $params = "/".App_Util_SafeUrl::encryptString($item["param"],false);
                                      }
                                      else{
                                             $params = App_Util_SafeUrl::encryptString($paramMenu,false);
                                     }
                                     $url = "href=\"{$this->baseUrl}/{$item["url"]}{$params}\"";
                                    }
                            }
                    if (count ( $item ["children"] ) > 0) {
                                    $class_link = " class=\"parent\"";
                            } else{
                                    $class_link = "";
                            }
                            $class_li="";
                            if($counter==count($array_menu)){
                                //$class_li=" class=\"last\"";
                            }
                            $class_actual="";
                            if($key==$actual){
                                $class_actual=" class=\"current\"";
                            }
                            //$textModule = 
                            
                            $labelModule = str_replace(" ", "", $item["label"]);
                            //echo "module.".$labelModule.".description = \"".trim($item["label"])."\"<br>module.".$labelModule.".label = \"".trim($item["label"])."\"<br>";
                            if(isset($textGlobal->$labelModule)){
	                            $description = $textGlobal->$labelModule->description;
	                            $labelUrl = $textGlobal->$labelModule->label;
                            }else{
	                            $description = $item["name"];
	                            $labelUrl = $item["name"];
                            }
                            	
                            $classVisible = '';
                            if (($nivel == 0)&&($counter==8)) {
                                $text .= "<li class=\"last\"><a href=# title=\"Otros\"><span>&gg;</span></a>";
                                $text .= "<div>";
                                $text .= "<ul>";
                            }
                            $text .= "<li{$classVisible}{$class_li}{$class_actual}><a {$url} title=\"{$description}\"><span>{$labelUrl}</span></a>";
                            if (count ( $item ["children"] ) > 0) {
                                    $text .= $this->RenderElement( $item ["children"],$actual, ($nivel + 1) );
                            }
                            $text .= "</li>";
		}
                if (($nivel == 0)&&($counter>=8)) {
                    $text .= "</ul>";
                    $text .= "</div>";
                    $text .= "</li>";
                }
                
		$text .= "</ul>";
		$text .= "</div><br>";
                
		return $text;
	}
	
}//fin de la clase
