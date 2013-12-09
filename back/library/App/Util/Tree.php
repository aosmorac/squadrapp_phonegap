<?php
class App_Util_Tree {
	// Structure table and fields
	protected $model	= "";
	protected $fields	= array(
                                    "id"=> false,
                                    "parent_id"	=> false,
                                    "position"	=> false,
            

                                );

	// Constructor 
	function __construct($model = null, $fields = array()) {
                if($model!==NULL){
                    $this->model = $model;
                    
                }
		
	}
        /**
         * Retorna un arreglo asociativo con los valores del nodo
         * @param int $id Identificador del Nodo
         */
	function _get_node($id) {
                $row= $this->model->getElementById();
                return ($row!==NULL) ? ($row->toArray()):false;
         }
         /**
          * Retorna los hijos de un nodo
          * @param $id Identificador del Nodo
          */
	function _get_children($id) {
                
		$childs=$this->model->getChildrenElement($id);
                return ($childs!==NULL) ? $childs:false;
	}
        /**
         *Crear un nodo
         * @param int $parent Identificador del nodo padre
         * @return type 
         */
	function _create($parent,$position,$data) {
                
            
                $this->model->createElement($parent,$position,$data);
		//return $this->_move(0, $parent,$position);
	}
        /**
         *Actualizar el nodo
         * @param int $parent Identificador del nodo padre
         * @return type 
         */
	function _update($id,$data) {
                
            
                $this->model->updateElement($id,$data);
		//return $this->_move(0, $parent,$position);
	}
        /**
         *Mover el nodo
         * @param int $id
         * @param int $ref_id
         * @param boolean $is_copy true/false es una copia o no
         * @return type 
         */
        function _move($id, $ref_id, $position,$is_copy = false) {
                    
                if($is_copy) {
                    $this->model->copyElement($id, $ref_id, $position);
                }
                else{
                    $this->model->moveElement($id, $ref_id, $position);
                }
                
		
        }
        /**
         *Eliminar el nodo
         * @param int $id Identificador del Nodo
         */
        function _remove($id) {
            $this->model->removeElement($id);
        }
}
?>
