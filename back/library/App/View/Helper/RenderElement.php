<?php

class App_View_Helper_RenderElement extends Zend_View_Helper_Abstract {
	
	function __construct() {
	
	}
	
	public function renderElement(Zend_Form_Element $element) {
		
		switch ($element->getType()) {
			case "Zend_Form_Element_Select":
			case "Zend_Form_Element_Radio":
				return $element->getView()->{$element->helper}(
			        $element->getName(),
			        $element->getValue(),
			        $element->getAttribs(),
			        $element->getMultiOptions()
		        );
				break;

			case "Zend_Form_Element_Checkbox":
				return $element->getView()->formCheckbox(
			        $element->getName(),
			        $element->getValue(),
			        $element->getAttribs(),
			        $element->options
			    );
				break;
				
			case "Zend_Form_Element_File":
				return $element->getView()->formFile(
			        $element->getName(),
			        $element->getAttribs()
			    );
				break;

			case "Zend_Form_Element_MultiCheckbox":
				return $element->getView()->formMultiCheckbox(
			        $element->getName(),
			        $element->getValue(),
			        $element->getAttribs(),
			        $element->getMultiOptions(),
			        $element->getSeparator()
			    );
				break;
			
			default:
				return $element->getView()->{$element->helper}(
				        $element->getName(),
				        $element->getValue(),
				        $element->getAttribs()
			        );
			break;
		}
		
	}

}

?>