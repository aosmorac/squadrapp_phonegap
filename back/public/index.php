<?php
// Define zend debug, echo output if true.
defined('APPLICATION_DUMP')
        || define('APPLICATION_DUMP', false);
// Define path to application directory
defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../../squadrapp_desar/application'));
// Define application environment
defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path(),
        )));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
                APPLICATION_ENV,
                APPLICATION_PATH . '/configs/application.ini'
);

Zend_Session::start();

    if (!isset($_SESSION['user_location'])){
        $user_location = array(
                            "use_loc_street_number"=>''
                            ,"use_loc_route"=>''
                            ,"use_loc_locality"=>''
                            ,"use_loc_city"=>''
                            ,"use_loc_administrative_area_level_2"=>''
                            ,"use_loc_administrative_area_level_1"=>''
                            ,"use_loc_country"=>''
                            ,"use_loc_postal_code"=>''
                            ,"use_loc_formatted_address"=>''
                            ,"use_location_name"=>(App_User::getCityByIp())
                            ,"use_loc_lat"=>''
                            ,"use_loc_lng"=>''
            );
        $_SESSION['user_location'] = $user_location;
    }

                

$application->bootstrap()
        ->run();
