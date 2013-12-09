<?php
/**
 * class IndexController
 * 
 * Clase de inicio por defecto del sistema
 */
class IndexController extends Zend_Controller_Action
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
    }
    
    /**
     * Action index
     * 
     * Action de inicio por defecto
     */
    public function indexAction ()
    {
            $this->render("list");
    }
	
    
    
    /**
     * Action list
     * 
     */
	public function listAction ()
    {
            $idUser = App_User::getUserId();
            if (isset($idUser) && $idUser>0) {
                $this->render("list-login");
            }//$this->render("list-login");
        
    }
    
    
    /**
     * Action auto-complete-city
     * 
     */
	public function autoCompleteCityAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $ini = $this->getParam('term');
            $cities = App_Util_AutoComplete::getCities('COL', $ini);
            echo json_encode($cities);
    }
    /**
     * Action auto-complete-sport
     * 
     */
	public function autoCompleteSportAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $ini = $this->getParam('term');
            $cities = App_Util_AutoComplete::getSports($ini);
            echo json_encode($cities);
    }
    
    	public function autoCompleteHourAction ()
    {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $hours = array();   
            $hours[] = array("hour"=>"06:00 - 07:00", "value"=>"06:00-07:00");
            $hours[] =  array("hour"=>"07:00 - 08:00", "value"=>"07:00-08:00");
            $hours[] =  array("hour"=>"08:00 - 09:00", "value"=>"08:00-09:00");
            $hours[] =  array("hour"=>"09:00 - 10:00", "value"=>"09:00-10:00");
            $hours[] =  array("hour"=>"10:00 - 11:00", "value"=>"10:00-11:00");
            $hours[] =  array("hour"=>"11:00 - 12:00", "value"=>"11:00-12:00");
            $hours[] =  array("hour"=>"12:00 - 13:00", "value"=>"12:00-13:00");
            $hours[] =  array("hour"=>"13:00 - 14:00", "value"=>"13:00-14:00");
            $hours[] =  array("hour"=>"14:00 - 15:00", "value"=>"14:00-15:00");
            $hours[] =  array("hour"=>"15:00 - 16:00", "value"=>"15:00-16:00");
            $hours[] =  array("hour"=>"16:00 - 17:00", "value"=>"16:00-17:00");
            $hours[] =  array("hour"=>"17:00 - 18:00", "value"=>"17:00-18:00");
            $hours[] =  array("hour"=>"18:00 - 19:00", "value"=>"18:00-19:00");
            $hours[] =  array("hour"=>"19:00 - 20:00", "value"=>"19:00-20:00");
            $hours[] =  array("hour"=>"20:00 - 21:00", "value"=>"20:00-21:00");
            $hours[] =  array("hour"=>"21:00 - 22:00", "value"=>"21:00-22:00");
            $hours[] =  array("hour"=>"22:00 - 23:00", "value"=>"22:00-23:00");
            $hours[] =  array("hour"=>"23:00 - 24:00", "value"=>"23:00-24:00");
            echo json_encode($hours);
    }
    
    
    
    
    /**
     * Action search-fields
     * 
     */
	public function searchFieldsAction ()
    {
            $this->_helper->layout()->disableLayout ();
            
            $location = explode(',', App_User::getCity());
            $city_model = new Model_DbTable_City();
            $sport_model = new Model_DbTable_Sport();
            $city_info = $city_model->getCityByName($location[0])->toArray();
            $sport_info = $sport_model->getSportByName('fútbol')->toArray();
            
            if (!isset($city_info[0])){
                $branch_model = new Ground_Model_Branch();
                $location = App_User::getLocation();
                $branches = $branch_model->getBranches(0, 1, '', $location['use_loc_lat'], $location['use_loc_lng'], App_User::getUserId(),0, 3);
                $this->view->branches = $branches;
                $this->render("message");
            }else {
                $city_id = $city_info[0]['ID'];
                $sport_id = $sport_info[0]['ID'];
                $ini = trim($this->getParam('ini'));
                $likes = $this->getParam('likes');
                $branch_model = new Ground_Model_Branch();
                $location = App_User::getLocation();
                $branches = $branch_model->getBranches($city_id, $sport_id, $ini, $location['use_loc_lat'], $location['use_loc_lng'], App_User::getUserId(),$likes);
                $this->view->branches = $branches;
                //Zend_Debug::dump($branches);
            }
            
    }
    
    
    
    /**
     * Action save-suggest-city
     * 
     */
	public function saveSuggestCityAction ()
        {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $other_city = trim($this->getParam('city'));
            if ($other_city != '') {
                $suggestions = new Model_Suggestions();
                $suggestions->setCity($other_city);
            }
        }
      
        
     /**
     * Action save-suggest-sport
     * 
     */
	public function saveSuggestSportAction ()
        {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $other_sport = trim($this->getParam('sport'));
            if ($other_sport != '') {
                $suggestions = new Model_Suggestions();
                $suggestions->setSport($other_sport);
            }
        }
        
        
        
        /**
         * 
         */
        public function headerSearchAction ()
        {
            $this->_helper->layout()->disableLayout ();
            $this->_helper->viewRenderer->setNoRender(true);
            $ini = $this->getParam('term');
            $branch = new Ground_Model_Branch();
            $branches = $branch->searchBranches($ini);
            echo json_encode($branches);
        }
        
  
    

    
}
?>
