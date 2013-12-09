<?php
/**
 * Description of MultiSelect
 *
 * @author Alexandra Benavides <alexandra.benavides@soluciones.net.co>
 */
class App_Util_MultiSelect
{
    //put your code here
    private $_selectOptions;
    private $_configOptions;
    function __construct ()
    {
        $this->_selectOptions = array();
        $this->_configOptions = array();
    }
    /**
     *Setea las opciones de configuracion para el multiselect
     * @param array $options Opciones de configuracion
     */
    public function setConfigOptions ($options)
    {
        $this->_configOptions = $options;
    }
    /**
     * Setea las opciones para el select
     * @param array $options 
     * @example 
     * $options['id']='valor' Valor de la opcion
     */
    public function addOptions ($options)
    {
        if (is_array($options)) {
            $this->_selectOptions = array_merge($this->_selectOptions, $options);
        }
    }
    /**
     * Añade una unica opcion al arreglo de opciones
     * @param type $id propiedad value de la opcion
     * @param type $name texto de la opcion
     * @param type $father padre de la opcion, null si es un grupo
     * @param type $childs arreglo de hijos en caso de que los tenga con las mismas opciones
     */
    public function addOption ($id, $name, $father = null, $childs = array())
    {
        $options = array("id" => $id, "name" => $name, "father" => $father, 
        "childs" => $childs);
        $this->_selectOptions = array_merge($this->_selectOptions, $options);
    }
    /**
     *Genera el html del select e incluye el js necesario para generarlo como multiselect
     * @param type $selectId
     * @param type $options
     * @return string 
     */
    public function renderElement ($options = array(), $required = true, $width = 200,$idMultiselect='multiselect', $height = 64, $isIdOption=false)
    {
        if (! empty($options)) {
            if ($isIdOption){
                $this->_selectOptions = $options;
            }else {
                $this->addOptions($options);
            }
        }
        $txtHtml = "<select id='{$idMultiselect}'";
        ;
        $class = "";
        if ($required) {
            $class .= "required";
        }
        $class .= " multiselect";
        $txtHtml .= " class='$class'";
        $txtHtml .= "' multiple='multiple' name='{$idMultiselect}[]'>";
        $txtHtml .= $this->generateOptions($this->_selectOptions);
        $txtHtml .= "</select>";
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/ui.multiselect.js"));
        $view->JQuery()->addStylesheet(
        $view->baseUrl("/css/ui.multiselect.css"));
        $txtHtml .= "<script type=\"text/javascript\">\n";
        $txtHtml .= "$(document).ready(function(){\n";
        $txtHtml .= '$(".ui-multiselect .selected").css("width","' . $width . 'px");
		     $(".ui-multiselect .available").css("width","' . $width . 'px");
		     $(".ui-multiselect ul.selected").css("height","' . $height . 'px");
		     $(".ui-multiselect ul.available").css("height","' . $height . 'px");
		  	 $(".ui-multiselect").css("width","' .
         (($width * 2) + 2) . 'px");';
        $txtHtml .= "$('.multiselect').multiselect( ";
        if (! empty($this->_configOptions)) {
            $txtHtml .= "{";
        }
        foreach ($this->_configOptions as $key => $option) {
            $txtHtml .= "$key:'$option'";
        }
        if (! empty($this->_configOptions)) {
            $txtHtml .= "}";
        }
        $txtHtml .= ")});\n";
        $idioma = App_User::getLanguage("sp");
        $urlLanguage = APPLICATION_PATH . "\langs\multiSelect_" . $idioma .
         ".txt";
        $txtHtml .= '$.extend($.ui.multiselect.locale, {';
        $txtHtml .= file_get_contents($urlLanguage, true);
        $txtHtml .= '});';
        $txtHtml .= "</script>\n";
        return $txtHtml;
    }
    public function generateOptions ($options, $father = 0, $fatherName = "")
    { 
        static $txtOptions = "";
        if (! empty($options)) {
            foreach ($options as $i => $option) {
                
                $id = $option["id"];
                
                $text = $option["name"];
                if (! empty($fatherName)) {
                    $text = $option["name"] . " [" . $fatherName . "]";
                }
                if (isset($option["metadata"]) &&
                 isset($option["metadata"]["AliasColumn"])) {
                    $text = $option["metadata"]["AliasColumn"];
                    if (! empty($fatherName)) {
                        $text = $option["metadata"]["AliasColumn"] . "[" .
                         $fatherName . "]";
                    }
                }
                if (! empty($option["childs"])) {
                    $fatherName = $text;
                    if (empty($father)) {
                        $text = $option["name"];
                        if (isset($option["metadata"]) &&
                         isset($option["metadata"]["AliasColumn"])) {
                            $text = $option["metadata"]["AliasColumn"];
                        }
                        $txtOptions .= "<optgroup label='$text'>";
                    } else {
                        $txtOptions .= "<option value='$i'";
                        if (isset($option["selected"]) && $option["selected"]) {
                            $txtOptions .= "selected='selected'";
                        }
                        if (isset($option["order"])) {
                            $order = $option['order'];
                            $txtOptions .= " order='$order'";
                        }
                        $txtOptions .= ">$text</option>";
                    }
                    $childs = $option["childs"];
                    $this->generateOptions($childs, $id, $text);
                } else {
                    $txtOptions .= "<option value='$i'";
                    if (isset($option["selected"]) && $option["selected"]) {
                        $txtOptions .= "selected='selected'";
                    }
                    if (isset($option["order"])) {
                        $order = $option['order'];
                        $txtOptions .= " order='$order'";
                    }
                    $txtOptions .= "> $text</option>";
                }
                if (! empty($option["childs"])) {
                    if (empty($father)) {
                        $txtOptions .= "</optgroup>";
                    }
                }
            }
            return $txtOptions;
        } else
            return $txtOptions;
    }
}
?>
