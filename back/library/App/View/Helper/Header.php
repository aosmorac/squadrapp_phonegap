<?php

class App_View_Helper_Header extends Zend_View_Helper_Abstract {

    function __construct() {

    }

    public function header($nombre="globalbluenet") {
        switch ($nombre) {
            case "globalbluenet":
                $this->view->placeholder($nombre)->captureStart("SET");
                ?>
                <div>
                    <table cellpading="0" cellspacing="0" border="0" style="width: 100%">
                        <tr valign="top">
                            <td>
                                <div >
                                    <img src="<?= $this->view->baseUrl("/img/company_logo.png") ?>" border="0" alt="Blue Cargo Group">
                                </div>
                            </td>
                            <td>
                                <?php if (App_User::isLogged()): ?>
                                    <div align="right" style="width: 100%">
                                        <strong><?= App_User::getUserName() ?> :: <?= App_User::getRolName() ?></strong> ||
                                        <?php
                                        $roles = App_User::getRoles();
                                        if (isset($roles) && (count($roles) > 1)):
                                            ?>
                                            <strong><a href="<?php echo $this->view->baseUrl("users/security/roles/") . App_Util_SafeUrl::encryptString("selectable/1") ?>">[cambiar Rol]</a></strong>
                                        <?php
                                        endif;
                                        $select = new Zend_Form_Element_Select("appLanguage");
                                        $idLanguage = App_User::getAttrib("domVal_language");
                                        $select->setLabel(App_Util_Language::getTextLanguage("Global")->domVal->language->label);
                                        $select->setRegisterInArrayValidator(false);
        								$arrayLanguage = App_Util_DomVal::getArrayDomVal("language", false);
                                        foreach ($arrayLanguage as $key => $value) {
                                        	if($key<=22)
                                        		$select->addMultiOption($key,$value);
                                        }
                                        $select->setValue($idLanguage);
                                        $decorators = array('ViewHelper'
                                            , array('Description', array('tag' => 'span', 'escape' => false))
                                            , 'Errors'
                                            , array(array('data' => 'HtmlTag'), array('tag' => 'span'))
                                            , array('Label', array('tag' => 'span'))
                                            , array(array('row' => 'HtmlTag'), array('tag' => 'span'))
                                        );
                                        $select->setDecorators($decorators);
                                        echo $select->render();
                                        ?>
                                        <script type="text/javascript">

                                            $(document).ready(function() {
                                                $("#appLanguage").change(function() {
                                                    var datapost = "language="+$(this).val();
                                                    var page = "<?= $this->view->baseUrl("index/change-language") ?>";
                                                    $.ajax({
                                                        type: 'POST',
                                                        url: page,
                                                        data: datapost,
                                                        success: function() {
                                                            location.reload();
                                                        }
                                                    });
                                                    return false;
                                                });
                                            });

                                        </script>
                                        <strong><a href="<?= $this->view->baseUrl("users/security/logout") ?>">[Salir]</a></strong>
                                    </div>
                                <?php else : ?>
                                    &nbsp;
                <?php endif ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php
                $this->view->placeholder($nombre)->captureEnd();

                break;
                case "globalbluenet_popup":
                 $this->view->placeholder($nombre)->captureStart("SET");
                ?>
                <div>
                    <table cellpading="0" cellspacing="0" border="0" style="width: 100%">
                        <tr valign="top">
                            <td>
                                <div >
                                    <img src="<?= $this->view->baseUrl("/img/bluecargo_logo_min.png") ?>" border="0" alt="Blue Cargo Group">
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <?php
                $this->view->placeholder($nombre)->captureEnd();

                   break;
        }
        return $this->view->placeholder($nombre);
    }

}
?>