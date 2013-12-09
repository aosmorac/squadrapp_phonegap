<?php

class App_Audit_Row extends Zend_Db_Table_Row_Abstract {
	
	/* Para almacenar la metadata de la tabla */
	protected $_info;
	
	protected $_use_id = 0;
	
	/** Indica si se debe habilitar el registro del log de auditoria  */
	protected $_enableAudit = true;
	
	public function init() {
		$this->_info = $this->getTable()->info();

                if(App_User::isLogged()){
                    $this->_use_id = App_User::getUserId();
                }
	}


        protected function _postInsert() {
        // Check if any rows have been created and if so, record the changes
        if ($this->_enableAudit && !empty($this->_data)) {
            $this->_logModifications(App_Audit_Factory::ACTION_CREATE);
        }
    }
 
    /**
     * Allows post-update logic to be applied to row
     * @return void
     */
    protected function _postUpdate() {
        // Check if any rows have been modified and if so, record the changes
        if ($this->_enableAudit && !empty($this->_cleanData)) {
            $this->_logModifications(App_Audit_Factory::ACTION_UPDATE);
        }
    }
 
    /**
     * Allows post-delete logic to be applied to row
     * @return void
     */
    protected function _postDelete() {
        // Check if any rows have been deleted and if so, record the changes
        if ($this->_enableAudit && !empty($this->_cleanData)) {
            // Get the primary key field, this will NOT be set as modified
            //$info = $this->getTable()->info();
            $info = $this->_info;
            $pkField = $info['primary']['1'];
 
            // Set all fields as modified so they are captured
            foreach ($this->_cleanData as $key => $value) {
                if ($key != $pkField) {
                    $this->_modifiedFields[$key] = true;
                    $this->_data[$key] = null;
                }
            }
 
            // Record the deletions
            $this->_logModifications(App_Audit_Factory::ACTION_DELETE);
        }
    }
 
    /**
     * Writes an audit record to the database for the given $actionMethod
     * @see App_Audit_Factory
     * @param string $actionMethod OpenEprs_Audit_Factory action method
     * @return void
     */
    protected function _logModifications($actionMethod) {
        // Get metadata about the table and its columns
        //$info = $this->getTable()->info();
        $info = $this->_info;
        //Zend_Debug::dump($this->_data);
 
        // Log the modifications
        App_Audit_Factory::getInstance()->logModify(
            $actionMethod,
            $this->_modifiedFields,
            $this->_cleanData,
            $this->_data,
            $info,
            $this->_use_id,
            $this->getTable()->getAdapter()
        );
    }
    
    static function log($action,$dataNew,$dataOld,$dbTable){
    	switch ($action) {
    		case "update":
    			$actionMethod=App_Audit_Factory::ACTION_UPDATE;
    		break;
    		case "insert":
    			$actionMethod=App_Audit_Factory::ACTION_CREATE;
    		break;
    		case "delete":
    			$actionMethod=App_Audit_Factory::ACTION_DELETE;
    		break;
    		default:
    			return;
    		break;
    	}
    	 // Get metadata about the table and its columns
        //$info = $this->getTable()->info();
        $info=$dbTable->_info;
        //Zend_Debug::dump($this->_data);
 
        // Log the modifications
        App_Audit_Factory::getInstance()->logModifyPublic(
            $actionMethod,
           	$dataOld,
            $dataNew,
            $info,
            App_User::getUserId(),
            $dbTable->getAdapter()
        );
    }
}