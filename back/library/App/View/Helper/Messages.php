<?php

class App_View_Helper_Messages extends Zend_View_Helper_Abstract {

    public function messages($texto, $style="") {
        switch ($style) {
            case "info":
                $style = "highlight";
                $icon = "info";
                break;
            case "error":
                $style = "error";
                $icon = "alert";
                break;

            default:
                $style = "";
                break;
        }
        $class = "";
        if (empty($style)) {

            $htmlSub = '<h3 class="ui-widget-header ui-corner-all" style="width:400px;margin:0;padding:0.4em;text-align:center;">';
            $_htmlSub = '</h3>';
        } else {
            $class = 'class="ui-widget"';
            $htmlSub = '<h4 style="width:400px;margin:0;padding:0.4em;text-align:center;" class="ui-state-' . $style . ' ui-corner-all">';
            $htmlSub .= '<span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-' . $icon . '"></span>';
            $_htmlSub = '</h4>';
        }


        $html = '<div align="center" ' . $class . '>';
        $html.=$htmlSub;
        $html.=$texto;
        $html.=$_htmlSub;
        $html.='</div>';
        return $html;
    }

}
