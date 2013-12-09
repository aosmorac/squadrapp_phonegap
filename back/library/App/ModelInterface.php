<?php

/*
 * Interface encargada de proveer los métodos a implementar obligatoriamente si
 * se va a usar dataTable
 * @author alexandra.benavides
 * 
 */
interface App_ModelInterface {
     /**
     * Retorna el select con los datos a mostrar a partir de la plantilla cargada
     * @return Zend_Db_Table_Select object. 
     */
    public function getDataSelect() ;
    /**
     * Funcion que crea o edita un modelo apartir de los datos recibidos
     */
    public function createFunction($data);
    /**
     *Retorna el arreglo de columnas a mostrar en el datatable con 
     * la configuracion para editar
     * @return array Arreglo con la configuracion de las columnas 
     */
    public function getDisplayColumns($idTemplate) ;
    
     /**
     * Retorna el dbTable del modelo
     * @return Zend_Db_Table_Abstract 
     */
    public function getDbTable() ;
    
}

?>
