<?php

/**
 * class Form_Bluenet
 * 
 * Clase que inicia los decoradores para ser usados en los diferentes formularios de los módulos
 * @author Marino Perez
 *
 */
class Form_Bluenet extends Zend_Form {

    protected $_static = false;
    protected $_elementFile = array('File'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , 'Errors'
        , array(array('data' => 'HtmlTag'), array('tag' => 'td'))
        , array('Label', array('tag' => 'th'))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
    protected $_elementDecorators = array('ViewHelper'
        //,'Description'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , 'Errors'
        , array(array('data' => 'HtmlTag'), array('tag' => 'td'))
        , array('Label', array('tag' => 'th'))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
    protected $_buttonDecorators = array('ViewHelper'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , 'Errors'
        , array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'elementButton', 'colspan' => '2'))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
    protected $_oneRowDecorators = array('ViewHelper'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , 'Errors'
        , array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '2'))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
    );
    protected $_buttonDecoratorsOpen = array('ViewHelper'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'elementButton', 'colspan' => '2', 'openOnly' => true))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'openOnly' => true))
    );
    protected $_buttonDecoratorsClose = array('ViewHelper'
        , array('Description', array('tag' => 'span', 'escape' => false))
        , array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'elementButton', 'closeOnly' => true))
        , array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'closeOnly' => true))
    );

    public function __construct($options = null) {
        parent::__construct($options);

        $translate = new Zend_Translate("array", APPLICATION_PATH . '/langs/messages_es.php');
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        //$this->setDisableLoadDefaultDecorators(true);
        $this->makeStaticAutomatic();
    }

    /**
     * Cargar los decoradores para un formulario
     */
    public function loadTableDecorators() {
        $this->setDecorators(array('FormElements', array('HtmlTag', array('tag' => 'table', 'class' => 'iceDatTbl zebra')), 'Form'));
        /* @var $elem Zend_Form_Element */
        foreach ($this->_elements as $elem) {
            if ($elem->getType() == "Zend_Form_Element_File") {
                $elem->setDecorators($this->_elementFile);
            } elseif ($elem->getType() == "Zend_Form_Element_Hidden") {
                $elem->loadDefaultDecorators();
            } elseif ($elem->getType() == "Zend_Form_Element_Submit" || $elem->getType() == "Zend_Form_Element_Image" || $elem->getType() == "Zend_Form_Element_Button") {
                $elem->setDecorators($this->_buttonDecorators);
            } else {
                $elem->setDecorators($this->_elementDecorators);
            }
            //Zend_Debug::dump($elem->getType()."--------------");
        }
    }

    /**
     * Crea un link de comando para redirigir a otra página
     * @param string $texto
     * @param string $url
     * @param string $tooltip
     * @return string
     */
    protected function createCommandLink($texto, $url, $tooltip=null) {
        $href = $this->getView()->baseUrl($url);
        $attrTooltip = "";
        if (!is_null($tooltip)) {
            $attrTooltip = " title=\"{$tooltip}\"";
        }
        return "<a href=\"{$href}\"{$attrTooltip}>{$texto}</a>";
    }

    /**
     * Hace que todos los elementos del formulario sean de solo lectura
     * @param bool $bool
     */
    public function makeStatic($bool=true) {
        if (!$bool) {
            $this->_static = false;
            return;
        }
        $toRemove = array("btnSubmit");
        foreach ($this->_elements as $elem) {
            $elem->helper = "formNote";
            if ($elem->getType() == "Zend_Form_Element_Hidden") {
                $elem->setValue(null);
            } elseif ($elem->getType() == "Zend_Form_Element_File") {
                $toRemove[] = $elem->getName();
            } elseif ($elem->getType() == "Zend_Form_Element_Select" || $elem->getType() == "Zend_Form_Element_Radio") {
                $elem->setValue($elem->getMultiOption($elem->getValue()));
            } elseif ($elem->getType() == "Zend_Form_Element_Textarea") {
                $elem->setValue(nl2br($elem->getValue()));
            } elseif ($elem->getType() == "Zend_Form_Element_Text") {
                $elem->setValue(nl2br($elem->getValue()));
            }
        }
        foreach ($toRemove as $name) {
            $this->removeElement($name);
        }
        $this->_static = true;
    }

    /**
     * @return the $isStatic
     */
    public function isStatic() {
        return $this->_static;
    }

    /**
     * Almacena el estado actual de valores y errores de validación del formulario en sesión
     * para ser recuperados posteriormente por el método recoverFromRedirect
     */
    public function setStateRedirect() {
        $formData = new Zend_Session_Namespace("FORM_DATA_TRANSFER");
        $formData->errors = $this->getMessages();
        $formData->values = $this->getValues();
    }

    /**
     * Restaura los valores y mensajes de error del formulario de un estado previamente
     * guardado con setStateRedirect
     */
    public function recoverFromRedirect() {
        $formData = new Zend_Session_Namespace("FORM_DATA_TRANSFER");
        if (!is_null($formData) && is_array($formData->errors)) {
            foreach ($formData->errors as $key => $value) {
                if ($this->getElement($key) !== null) {
                    $this->getElement($key)->setErrors(array_values($value));
                }
            }
            //Zend_Debug::dump($formData->errors);
        }
        if (!is_null($formData) && is_array($formData->values)) {
            $this->setDefaults($formData->values);
            //Zend_Debug::dump($formData->values);
        }
        $formData->unsetAll();
        unset($formData);
    }

    /**
     * Convierte el formulario a solo lectura de forma automática si previamente
     * se ha definido la variable de sesión que lo indica
     */
    private function makeStaticAutomatic() {
        $formStatic = new Zend_Session_Namespace("FORM_DATA_STATIC");
        if (!is_null($formStatic) && is_bool($formStatic->readonly) && $formStatic->readonly == true) {
            $this->makeStatic();
        }
        $formStatic->unsetAll();
        unset($formStatic);
    }

}