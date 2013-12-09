<?php
/**
 * class CronController
 * 
 */
class General_CronController extends Zend_Controller_Action
{
	
        private $cron;
	
    public function init(){
        $this->cron = new General_Model_Cron();
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
     * Action initializeSchedule
     * 
     */
	public function initializeScheduleAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $fid = trim($this->getParam('fid'));
            if (isset($fid) && $fid>0)
                $this->cron->initializeSchedule($fid);
            else 
                $this->cron->initializeSchedule();
    }
       

    
}
?>
