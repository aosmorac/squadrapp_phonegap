<?php

class App_Audit_Factory extends Zend_Db_Table_Abstract {
    
    /** Audit factory create action */
    const ACTION_CREATE = 'CREATE';
 
    /** Audit factory update action */
    const ACTION_UPDATE = 'UPDATE';
 
    /** Audit factory delete action */
    const ACTION_DELETE = 'DELETE';
 
    /** @var array Table name */
    protected $_name = 'GB_system_audit';
 
    /** @var mixed Primary key column or columns */
    protected $_primary = 'audit_id';
 
    // Transaction ID for audit records
    private static $_transactionId = null;
 
    /**
     * Returns an instance of this class
     * @return OpenEprs_Audit_Factory instance
     */
    public static function getInstance() {
        static $instance = null;
 
        // If the instance is null, create it
        if(is_null($instance)) {
            $instance = new self;
        }
        // Create an audit transaction ID
        if(is_null(self::$_transactionId)) {
            self::$_transactionId = uniqid();
        }
        return $instance;
    }
 
    /**
     * Logs modified records to the application's system audit table.
     *
     * @param string $actionMethod   OpenEprs_Audit_Factory action method
     * @param array  $modifiedFields columns where data has been updated
     * @param array  $oldRow         row before modification
     * @param array  $newRow         row after modification
     * @param mixed  $tableInfo      Zend_Db_Table information
     * @param int    $userId	     Código del usuario en sesion
     * @return boolean true on success, false otherwise
     */
    public function logModify($actionMethod, $modifiedFields, $oldRow, $newRow, $tableInfo, $userId,$adapter) {
        // If nothing is modified get out...
        if(empty($modifiedFields)) {
            return false;
        }
        $this->_setAdapter($adapter);
        // Get the user's ID
        /*$auth = Zend_Auth::getInstance();
        $userId = 0;
        if($auth->hasIdentity()) {
        	$userId = $auth->getIdentity()->codusuario;
        }*/
 
        // Start the audit process
        try {
            // Get the current datetime
            $actionDate = new Zend_Db_Expr('getdate()');
 
            // Process each modified filed

            foreach($modifiedFields as $key => $value) {
                // If the new value is actually different from the old value
                if($actionMethod == self::ACTION_CREATE || ( $actionMethod != self::ACTION_CREATE && $oldRow[$key] !== $newRow[$key]) ) {
                    // Get the primary key field
                    $objPrimaryKey = $tableInfo['primary']['1'];
 
                    // Select the table ID based on the action method
                    $tableId = '';
                    switch ($actionMethod){
                        case self::ACTION_CREATE:
                            $tableId = $newRow[$objPrimaryKey];
                            break;
                        case self::ACTION_UPDATE:
                            $tableId = $oldRow[$objPrimaryKey];
                            break;
                        case self::ACTION_DELETE:
                            $tableId = $oldRow[$objPrimaryKey];
                            break;
                    }
 
                    // Create a new audit row
                    $record = $this->createRow();
 
                    // Set the audit fields
                    $data['use_id'] = $userId;
                    $data['transaction_id'] = self::$_transactionId;
                    $data['table_name'] = $tableInfo['name'];
                    $data['table_id'] = $tableId;
                    $data['action_method'] = $actionMethod;
                    $data['action_date'] = $actionDate;
                    $data['field_name'] = $key;
 
                    if($tableInfo['metadata'][$key]['DATA_TYPE'] == 'text') {
                        // Field is of type 'text' -> store in text fields
                        if($actionMethod != self::ACTION_CREATE) {
                        	$data['before_value_text'] = $oldRow[$key];
                        }
                        $data['after_value_text'] = $newRow[$key];
                    } else {
                        // Field is NOT of type 'text' -> store in varchar fields
                        if($actionMethod != self::ACTION_CREATE) {
                        	$data['before_value_string'] = $oldRow[$key];
                        }
                        $data['after_value_string'] = $newRow[$key];
                    }
 
                    // Save the audit row
                    $registerAudit = true;
                    if($actionMethod==self::ACTION_UPDATE && $data['after_value_string']==$data['before_value_string'] ) $registerAudit = false;
                    
                    if($registerAudit) $record->setFromArray($data)->save();
                    
                }
            }
 
            return true;
        } catch (Exception $e) {
            // Build the error message
            $errMsg = "\nMessage: " . $e->getMessage() .
                      "\nStack Trace:\n" . $e->getTraceAsString();
              $pm=  new App_View_Helper_PriorityMessenger();
              $pm->priorityMessenger($errMsg,"error");
            // Log the error
            App_Util::log($errMsg,"error");
 
            return false;
        }
    }
    
/**
     * Logs modified records to the application's system audit table.
     *
     * @param string $actionMethod   OpenEprs_Audit_Factory action method
     * @param array  $modifiedFields columns where data has been updated
     * @param array  $oldRow         row before modification
     * @param array  $newRow         row after modification
     * @param mixed  $tableInfo      Zend_Db_Table information
     * @param int    $userId	     Código del usuario en sesion
     * @return boolean true on success, false otherwise
     */
    public function logModifyPublic($actionMethod,  $oldRow, $newRow, $tableInfo, $userId,$adapter) {
        $this->_setAdapter($adapter);
 
        // Start the audit process
        try {
            // Get the current datetime
            $actionDate = new Zend_Db_Expr('getdate()');
 
            // Process each modified filed

            foreach($newRow as $key => $value) {
                // If the new value is actually different from the old value
                if($actionMethod == self::ACTION_CREATE || ( $actionMethod != self::ACTION_CREATE && $oldRow[$key] !== $newRow[$key]) ) {
                    // Get the primary key field
                    $objPrimaryKey = $tableInfo['primary']['1'];
 
                    // Select the table ID based on the action method
                    $tableId = '';
                    switch ($actionMethod){
                        case self::ACTION_CREATE:
                            $tableId = $newRow[$objPrimaryKey];
                            break;
                        case self::ACTION_UPDATE:
                            $tableId = $oldRow[$objPrimaryKey];
                            break;
                        case self::ACTION_DELETE:
                            $tableId = $oldRow[$objPrimaryKey];
                            break;
                    }
 
                    // Create a new audit row
                    $record = $this->createRow();
 
                    // Set the audit fields
                    $data['use_id'] = $userId;
                    $data['transaction_id'] = self::$_transactionId;
                    $data['table_name'] = $tableInfo['name'];
                    $data['table_id'] = $tableId;
                    $data['action_method'] = $actionMethod;
                    $data['action_date'] = $actionDate;
                    $data['field_name'] = $key;
 
                    if($tableInfo['metadata'][$key]['DATA_TYPE'] == 'text') {
                        // Field is of type 'text' -> store in text fields
                        if($actionMethod != self::ACTION_CREATE) {
                        	$data['before_value_text'] = $oldRow[$key];
                        }
                        $data['after_value_text'] = $newRow[$key];
                    } else {
                        // Field is NOT of type 'text' -> store in varchar fields
                        if($actionMethod != self::ACTION_CREATE) {
                        	$data['before_value_string'] = $oldRow[$key];
                        }
                        $data['after_value_string'] = $newRow[$key];
                    }
 
                    // Save the audit row
                    $registerAudit = true;
                    if($actionMethod==self::ACTION_UPDATE && $data['after_value_string']==$data['before_value_string'] ) $registerAudit = false;
                    
                    if($registerAudit) $record->setFromArray($data)->save();
                    
                }
            }
 
            return true;
        } catch (Exception $e) {
            // Build the error message
            $errMsg = "\nMessage: " . $e->getMessage() .
                      "\nStack Trace:\n" . $e->getTraceAsString();
              $pm=  new App_View_Helper_PriorityMessenger();
              $pm->priorityMessenger($errMsg,"error");
            // Log the error
            App_Util::log($errMsg,"error");
 
            return false;
        }
    }
}