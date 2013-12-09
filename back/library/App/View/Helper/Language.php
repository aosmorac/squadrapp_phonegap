<?php

class App_View_Helper_Language extends Zend_View_Helper_Abstract {
	
	public function Language($module = "") {
            if(empty($module)) $module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        	$textModule = App_Util_Language::getTextLanguage($module);
		return $textModule;
	}
}
