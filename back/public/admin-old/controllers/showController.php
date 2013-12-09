<?php
class showController extends ControllerBase
{

    public function table(){
      $config = Config::singleton();
      require 'models/showModel.php';
		$items = new showModel();
     	$table = gett('a') != -1 ? gett('a') : $config->get('tabla_default');


		$data = Array(/* "table_label" => $table_label, */
		          "title" => "BackOffice | $table",
		          "items_head" => $items->getItemsHead($table),
		          "items" => $items->getAll($table),
		          "HOOK_JS" => $items->js($table),
                  "table" => $table,
					"table_label" =>$items->getTableAttribute($table,'table_label'),
					"notification" => gett('i') != -1 ? 'Se ha guardado correctamente' : ''
		      		          
		          );

		
		$this->view->show("show.php", $data);
		
		
    }
    
	 
}
?>
