<?php

class App_View_Helper_Footer extends Zend_View_Helper_Abstract {

    function __construct() {

    }

    public function footer($nombre ="globalbluenet") {
        switch ($nombre) {
            case "globalbluenet":
                $this->view->placeholder($nombre)->captureStart("SET");
                ?>
                <br/><br/><br/>
                <div id="dcmenu" class="dcmenu dcmenuFloat">
                <!-- 
                                <div id="dcmenuContainer" style="float:left">
                                    <ul id="dcmenuitems">
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Accounts_bar_32.png"); ?>" alt="Create Account" title="Create Account"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Contacts_bar_32.png"); ?>" alt="Create Contact" title="Create Contact"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Leads_bar_32.png"); ?>" alt="Create Lead" title="Create Lead"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Opportunities_bar_32.png"); ?>" alt="Create Opportunity" title="Create Opportunity"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Calls_bar_32.png"); ?>" alt="Log Call" title="Log Call"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Emails_bar_32.png"); ?>" alt="Send Email" title="Send Email"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Meetings_bar_32.png"); ?>" alt="Schedule Meeting" title="Schedule Meeting"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Tasks_bar_32.png"); ?>" alt="Create Task" title="Create Task"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Notes_bar_32.png"); ?>" alt="Create Note or Attachment" title="Create Note or Attachment"></a></li>
                                        <li><a href="#"><img class="icon" src="<?php echo $this->view->baseUrl("img/icon_Cases_bar_32.png"); ?>" alt="Create Cases" title="Create Cases"></a></li>
                                    </ul>
                                </div>
                                 -->
                            <div style="margin-right:10px;margin-top:1px; float:right">
                               <!--  <img src="<?php echo $this->view->baseUrl("img/logosnet_min.png"); ?>" alt="Soluciones .NET" title="Soluciones .NET"/> -->
                            </div>
                </div>
                <?php
                $this->view->placeholder($nombre)->captureEnd();
                break;
            case "globalbluenet_popup":
                $this->view->placeholder($nombre)->captureStart("SET");
                ?>
                <br/>
                <br/>
                <br/>
                <div id="dcmenu" class="dcmenu dcmenuFloat">
                            <div style="margin-right:10px;margin-top:1px; float:right">
                              <!--   <img src="<?php echo $this->view->baseUrl("img/logosnet_min.png"); ?>" alt="Soluciones .NET" title="Soluciones .NET"/>  -->
                            </div>
                </div>           
                <?php
                $this->view->placeholder($nombre)->captureEnd();
                break;
        }
        return $this->view->placeholder($nombre);
    }

}
?>