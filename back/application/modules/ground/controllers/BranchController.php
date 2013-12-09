<?php
/**
 * class BranchController
 * 
 * Clase de inicio por defecto del sistema
 */
class BranchController extends Zend_Controller_Action
{
	
	/**
	 * @var Zend_Config_Ini $textGlobal
	 * @var Zend_Config_Ini $textModule
	 */
	private $textGlobal;
	private $textModule;
	
    public function init(){
    	$this->textGlobal = App_Util_Language::getTextLanguage();
        $this->textModule = App_Util_Language::getTextLanguage($this->getRequest()->getModuleName()); 
        $this->view->placeholder("title")->set($this->textModule->index->title);
	$this->view->title = "Canchas | ".$this->textModule->index->label;
        $this->view->headTitle($this->view->title);
	$this->_helper->layout->setLayout('application');
        //if (!App_User::isLogged()){
            //$this->_redirect('/');
        //}
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {

    }
    
    
	
    /**
     * Action site
     * 
     * Action de sitio
     */
    public function siteAction ()
    {
        $date = date("Y-m-d");      //  Fecha actual
        $hour = date("H:i:00");     //  Hora actual
        $sid = trim($this->getParam('sid'));
        $branch_model = new Ground_Model_Branch();
        $branch = $branch_model->getBranch($sid);
        $header = $branch_model->getHeaderImages($sid);
        $visitors = $branch_model->getVisitorsFriends($sid, 5);
        $moreVisitors = $branch_model->getVisitorsFriends($sid, 10);
        $fields = $branch_model->getNextFieldFree($sid,$date,$hour);
        $fieldKinds = $branch_model->getFieldsFeatures($sid);
        $hours = $branch_model->getHoursByDay($sid, date("Y-m-d"));
        $this->view->branch = $branch;
        $this->view->header = $header;
        $this->view->visitors = $visitors;
        $this->view->moreVisitors = $moreVisitors;
        $this->view->fields = $fields;
        $this->view->fieldKinds = $fieldKinds;  //
        $this->view->hours = $hours;            //
        $this->view->friendId = $this->getParam('uid');   
        //Zend_Debug::dump($header);
        //Zend_Debug::dump($branch);
        //Zend_Debug::dump($visitors);
        //Zend_Debug::dump($fields);
        //Zend_Debug::dump($fieldKinds);
        //Zend_Debug::dump($hours);
        $branch_model->setVisitor($sid);
    }
       
    
    /**
     * 
     */
    public function loadAvailabilityFieldAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $variables = $this->_getAllParams();
        $branch_model = new Ground_Model_Branch();
        $availability = $branch_model->getBranchAvailabilityByHour($variables['ids_field'], $variables['date'], $variables['hour']);
        echo json_encode($availability);
        //Zend_Debug::dump($variables);
    }
    
    
    /**
     * 
     */
    public function autoCompleteAttributesAction ()
    {
            $sid = trim($this->getParam('sid'));
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            //$ini = $this->getParam('term');
            $branch_model = new Ground_Model_Branch();
            $attributes = $branch_model->getFieldsFeatures($sid);
            echo json_encode($attributes);
    }
    
    
    /**
     * 
     */
    public function autoCompleteHoursAction ()
    {
            $sid = trim($this->getParam('sid'));
            $date = trim($this->getParam('date'));
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            //$ini = $this->getParam('term');
            $branch_model = new Ground_Model_Branch();
            $hours = $branch_model->getHoursByDay($sid, $date);
            echo json_encode($hours);
    }
    
    
    
    public function getSuggestionsAction (){
        $this->_helper->layout()->disableLayout ();
        $sid = trim($this->getParam('bra_id'));
        $date = trim($this->getParam('date'));
        $hour = trim($this->getParam('hour'));
        $sugType = trim($this->getParam('sugType'));
        $branch_model = new Ground_Model_Branch();
        $suggest = array();
        if ($sugType == 1) {
            $sameDay = $branch_model->getSuggestFields($sid, $date, $hour, 5, 1);
            $sameHour = $branch_model->getSuggestFields($sid, $date, $hour, 5, 2);
            $suggest = array_merge($sameDay, $sameHour);
        }if ($sugType == 2) {
            $same = $branch_model->getSuggestFields($sid, $date, $hour, 10, 3);
            $otherHours = array();
            $reg = count($same);
            if ( $reg < 10){
                
                $otherHours = $branch_model->getSuggestFields($sid, $date, $hour, 10-$reg, 4);
            }
            $suggest = array_merge($same, $otherHours);
        }//Zend_Debug::dump($suggest);die();
        $this->view->suggest = $suggest;
    }
    
    
    
    
    public function confirmBookingFormAction(){
        $this->_helper->layout()->disableLayout ();
        $vars = $this->_getAllParams();
        $schs = explode(',', $vars['ids_sch']);
        $vars['ids_sch'] = $schs[0];
        $branch_model = new Ground_Model_Branch();
        $bookElement = $branch_model->getBookElement($vars['ids_sch']);
        //Zend_Debug::dump($bookElement); die;
        $this->view->branch = $bookElement;
    }
    
    
    
    
    public function saveBookingFormAction(){
        $this->_helper->layout()->disableLayout ();
        $vars = $this->_getAllParams();
        $branch_model = new Ground_Model_Branch();
        $booking = array('fie_sch_id'=>$vars['sch_id'], 'fie_sch_date'=>$vars['sch_date']
            , 'fie_sch_hour'=>$vars['sch_hour'], 'use_name'=>$vars['name']
            , 'use_lastname'=>$vars['lastname'], 'document'=>$vars['document']
            , 'email'=>$vars['email'], 'phone'=>$vars['phone']);
        if ($branch_model->saveBookingFromUser($booking)){
            echo 'ok';
        }else{
            echo 'error';
        }
    }
    
    
    
    public function getContactAction (){
        $this->_helper->layout()->disableLayout ();
        $sid = trim($this->getParam('bra_id'));
        $branch_model = new Ground_Model_Branch();
        $branch = $branch_model->getBranch($sid);
        //Zend_Debug::dump($branch); die;
        $this->view->branch = $branch;
    }
    
    
    public function getKindsAction(){
        $this->_helper->layout()->disableLayout ();
        
        $sid = trim($this->getParam('bra_id'));
        $branch_model = new Ground_Model_Branch();
        $fieldKinds = $branch_model->getFieldsFeatures($sid);
        $this->view->fieldKinds = $fieldKinds;
    }
    
    
    public function saveLikeAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $sid = trim($this->getParam('bra_id'));
        if (App_User::isLogged()){
            $branch_model = new Ground_Model_Branch();
            if ($branch_model->saveLike($sid, App_User::getUserId())){
                echo 'ok';
            }else {
                echo 'error';
            }
        }else {
            echo 'error';
        }
    }
    
    public function removeLikeAction(){
        $this->_helper->layout()->disableLayout ();
        $this->_helper->viewRenderer->setNoRender(true);
        $sid = trim($this->getParam('bra_id'));
        if (App_User::isLogged()){
            $branch_model = new Ground_Model_Branch();
            if($branch_model->deleteLike($sid, App_User::getUserId())){
                echo 'ok';
            }else {
                echo 'error';
            }
        }else {
            echo 'error';
        }
    }
   

    
}
?>
