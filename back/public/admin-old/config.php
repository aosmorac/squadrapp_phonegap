<?

$config = Config::singleton();

	$config->set('lang','esp');
	$config->set('base_title','Project');
	$config->set('base_url','http://www.');
	$config->set('base_url_data','http://www./');
	$config->set('db_prefix','');
	$config->set('tabla_default','foundation');
    $config->set('validUser','test');
    $config->set('validPass','test');

	/* Toggles */
	$config->set('developer_mode',1);
	$config->set('combo_add',0);
	$config->set('delete_permission',1);



	$config->set('big_h',0);// 0 for no resize
	$config->set('big_w',0); // 0 for no resize
	$config->set('img_content_w',503);
	$config->set('img_content_h',0);
	$config->set('thumb_h',180);
	$config->set('thumb_w',260);
	



    $PATH = dirname(__FILE__);

	$config->set('path',$PATH); 
	
	$config->set('setup_dir',$PATH.'/setup/');
	$config->set('data_dir',$PATH.'/../data/');
	$config->set('files_dir',$PATH.'/../data/files/');
	$config->seT('http_files_dir',$config->get('base_url_data').'files/');
	
	$config->set('controllersFolder', 'controllers/');
	$config->set('modelsFolder', 'models/');
	$config->set('viewsFolder', 'views/');
  
	$config->set('dbhost', '');
	$config->set('dbname', '');
	$config->set('dbuser', '');
	$config->set('dbpass', '');
