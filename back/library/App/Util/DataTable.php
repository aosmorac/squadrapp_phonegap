<?php
class App_Util_DataTable
{
    private $_columns;
    private $_attributesTable;
    private $_attributesExtras;
    private $_isEditable;
    private $_arrayExtrasJs;
    private $_filtersColumns;
    private $_positionFilters;
    private $_hiddenBeforeSearch;
    private $_aoColumns;
    private $_editablecolumns;
    private $_idTemplate;
    private $_Template;
    private $_AbleKeys;
    private $_validateColumns;
    private $_hiddenColumns;
    private $_column_ant;
    /**
     * Class constructor
     */
    function __construct ()
    {
        $this->_columns = array();
        $this->_attributesExtras = array();
        $this->_attributesTable = array();
        $this->_arrayExtrasJs = array();
        $this->_aoColumns = array();
        $this->_hiddenColumns = array();
        $this->_isEditable = false;
        $this->_filtersColumns = false;
        $this->_editablecolumns = false;
        $this->_Template = false;
        $this->_hiddenBeforeSearch = false;
        $this->_AbleKeys = true;
        $this->_idTemplate = null;
        $this->_positionFilters = "thead";
        $this->_attributesTable["sPaginationType"] = '"full_numbers"'; //paginacion
        $this->_attributesTable["bJQueryUI"] = "true";
        //si se bloquean las columnas
        //$this->_attributesTable["sScrollX"] = '"100%"';
        //$this->_attributesTable["sScrollXInner"] = '"100%"';
        //$this->_attributesTable["bScrollCollapse"] = true;
        $this->_attributesTable["oTableTools"]["sRowSelect"] = '"single"'; //seleccionar una fila
        $this->_attributes["otherParams"]["check"] = false;
        $this->_column_ant=1;
         //ocultar y mostrar columnas
    //$this->_attributesTable["sDom"] = "'RC<\"clear\">lfrtip'";
    //$this->_attributesTable["oColVis"]["aiExclude"] = '[0]';
    //$this->_attributesTable["oColVis"]["buttonText"] = '"Mostrar/Ocultar"';
    //$this->_attributesExtras["FixedHeader"] = true; //cabecera bloqueada
    //        $this->_attributesExtras["FixedHeader"]["left"] = true;
    //        $this->_attributesExtras["FixedHeader"]["zLeft"] = 105;
    /* var oTable = $('#example').dataTable();
          new FixedHeader( oTable, { -...-} ); */
    //$this->_attributesExtras["FixedColumns"] = true; //cabecera bloqueada
    //$this->_attributesExtras["FixedColumns"]["iLeftColumns"] = 1;
    //$this->_attributes["otherParams"]["FixedColumns"]["iLeftWidth"]=350;
    //$this->_attributesExtras["FixedColumns"]["iRightColumns"] = 0;
    /* new FixedColumns( oTable, {---} ); */
    }
    /**
     * Agregar columnas a mostrar en la Tabla
     * 
     * @param string $nameColumn llave asociada a la columna
     * @param string $titleColumn valor a mostrar en la cabecera de la columna, si no existe es el mismo nameColumn
     * @param array $arrParams Parametros adicionales a la columna
     */
    function addColumn ($nameColumn, $titleColumn = "", $paramsColumn = array())
    {
        $this->_columns[$nameColumn]["name"] = $nameColumn;
        $this->_columns[$nameColumn]["title"] = $titleColumn;
        $this->_columns[$nameColumn]["params"] = $paramsColumn;
    }
    /**
     * Eliminar una columna de la tabla
     * @param string $nameColumn llave asociada a la columna
     */
    function deleteColumn ($nameColumn)
    {
        unset($this->_columns[$nameColumn]);
    }
    /**
     * Parametros para modificar el comportamiento de una columna
     * @param string $nameColumn  llave asociada a la columna
     * @param array $params Parametros de la columna 
     */
    function paramsColumn ($nameColumn, $params)
    {
        $this->_columns[$nameColumn]["params"] = $params;
    }
    function setAbleKeys ($able = true)
    {
        $this->_AbleKeys = $able;
    }
    /**
     * Definir si el dialog se abre automaticamente.
     *
     * @param $flag "true" para que el dialog se abra automaticamente, "false" para que no se abra
     */
    public function setAttribute ($attribute, $value)
    {
        $this->_attributesTable[$attribute] = $value;
    }
    public function setTableTools ($attribute, $value)
    {
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addStylesheet($view->baseUrl("/css/TableTools.css"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.DataTables.min.js"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/TableTools.min.js"));
        $this->_attributesTable["sDom"] = "'T<\"clear\"><\"fg-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix\"lfr>t<\"fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix\"ip>'";
        $this->_attributesTable["oTableTools"][$attribute] = $value;
    }
    public function setObjectAttribute ($object, $attribute, $value)
    {
        $this->_attributesTable[$object][$attribute] = $value;
    }
    public function setExtraAttribute ($attribute, $value)
    {
        $this->_attributesExtras[$attribute] = $value;
    }
    public function setExtraObjectAttribute ($object, $attribute, $value)
    {
        $this->_attributesExtras[$object][$attribute] = $value;
    }
    public function isEditable ()
    {
        $this->_isEditable = true;
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.dataTables.KeyTable.js"));
    }
    /**
     * Setea la variable _hiddenBeforeSearch para ocultar o mostrar la grilla
     * antes del primer filtro
     * @param boolean $hidden true si se oculta false en caso contrario
     */
    public function isHiddenBeforeSearch ($hidden = true)
    {
        $this->_hiddenBeforeSearch = $hidden;
    }
    /**
     * Retorna la propiedad solicitada
     * @param string $property nombre de la propiedad a retornar
     * @return type 
     */
    public function getProperty ($property)
    {
        return $this->$property;
    }
    public function getAttributesExtras ()
    {
        return $this->_attributesExtras;
    }
    public function setAoColumns ($aocolumns,$columnAnt=1)
    {
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.jeditable.js"));
         $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.jeditable.currentlydate.js"));
        unset($aocolumns['data']);
        unset($aocolumns['columns']);
        unset($aocolumns['alias']);
        $displayAoColumns = "[\n";
        $validateColumns = "";
        $i = 0;
        $this->_hiddenColumns = array();
        foreach ($aocolumns as $columns) {
            if ($columns['visible'] === false) {
                $this->_hiddenColumns[] = $i;
            }
            if (isset($columns['editableOptions'])) {
                $displayAoColumns .= "{\n";
                foreach ($columns['editableOptions'] as $key => $item) {
                    if ($item != 'true' && $item != 'false') {
                        $displayAoColumns .= "'{$key}': '{$item}',\n";
                    } else {
                        $item = str_replace("'", "", $item);
                        $displayAoColumns .= "'{$key}': {$item},\n";
                    }
                    if ($key == 'type') {
                        switch ($item) {
                            case 'datepicker':
                                $language=App_User::getLanguage();
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/localization_datetimepicker/jquery.ui.datepicker-{$language}.js"));
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/jquery.jeditable.datepicker.js"));
                                break;
                            case 'datetimepicker':
                                $language=App_User::getLanguage();
                                if($language!='en'){
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/localization_datetimepicker/jquery.ui.datepicker-{$language}.js"));
                                    $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/localization_datetimepicker/jquery.ui.datetimepicker-{$language}.js"));
                                }
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/jquery-ui-timepicker-addon.js"));
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/jquery.jeditable.datetimepicker.js"));
                                   
                                break;
                            case 'checkbox':
                                $view->JQuery()->addJavascriptFile(
                                    $view->baseUrl("/js/jquery/jquery.jeditable.checkbox.js"));
                                break;
                        }
                    }
                }
                $displayAoColumns .= "'onblur': 'submit',\n";
                $displayAoColumns .= "'onreset': function(){
                            /* Unblock KeyTable, but only after this 'esc' key event  finished. Otherwise
                            * it will 'esc' KeyTable as well
                            */
                            setTimeout( function () {keys.block = false;}, 0);
                        },\n";
                
                
                
                $displayAoColumns .= "'placeholder': '&nbsp;',\n";
                $displayAoColumns .= "'callback':function () {
                 	$('.dataTables_processing').css('visibility', 'hidden'); 
            	},\n ";
                //$displayAoColumns.="'sReadOnlyCellClass': 'read_only',\n";
                if (isset($columns['validateOptions'])) {
                    $name = $columns['name'];
                    $displayAoColumns .= "oValidationOptions : {\n";
                    $name = $columns['name'];
                    foreach ($columns['validateOptions'] as $index => $validate) {
                        $displayAoColumns .= $index . ":{\n ";
                        $displayAoColumns .= "value:{\n ";
                        foreach ($validate as $key => $value) {
                            $displayAoColumns .= $key . ":" . $value . ",\n ";
                        }
                        $displayAoColumns .= "}\n },\n";
                    }
                    $displayAoColumns .= "},\n ";
                }
                $displayAoColumns = substr($displayAoColumns, 0, - 2);
                $displayAoColumns .= "\n},\n";
                $columns['params']['class'] = 'edit';
            } else {
                $displayAoColumns .= "null, \n";
            }
            if (isset($columns['name'])) {
            	
                $paramsColumn = array();
                $nameColumn = $titleColumn = $columns['name'];
                if (isset($columns['type']) && $columns['type'] == 0) {
                    if (isset($columns["columnName"])) {
                        $nameColumn = $columns["columnName"];
                    }
                } else 
                    if (isset($columns['type'])) {
                        if (isset($columns["id"])) {
                            $nameColumn = $columns["id"];
                        }
                    }
                if (isset($columns['title'])) {
                    $titleColumn = $columns['title'];
                }
                if (isset($columns['params'])) {
                    $paramsColumn = $columns['params'];
                }
                $this->addColumn($nameColumn, $titleColumn, $paramsColumn);
            }
            $i ++;
        }
        $displayAoColumns = substr($displayAoColumns, 0, - 2);
        $displayAoColumns .= "\n]";
        $this->isEditable();
        $this->_column_ant=$columnAnt;
        $this->_aoColumns = $displayAoColumns;
        $this->_validateColumns = $validateColumns;
    }
    public function EditableColumns ($editable = true)
    {
        $this->_editablecolumns = $editable;
    }
    /**
     * 
     * Retorna las columnas del dataTable
     */
    public function getColumns(){
    	return $this->_columns;
    }
    /**
     * Habilita ajax para paginación y busqueda
     */
    public function Ajax ($model, $editable = false, $validate = array(), 
    $serverside = false)
    {
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.DataTables.min.js"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.dataTables.Ajax.js"));
        $this->setAttribute("bServerSide", 'true');
        $this->setAttribute("bProcessing", 'true');
        $this->setAttribute("sServerMethod", '"POST"');
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $ajaxPath = "'$baseUrl/settings/DataGrid/ajax/";
        $ajaxPath .= App_Util_SafeUrl::encryptString("model/$model") . "'";
        $this->setAttribute("sAjaxSource", $ajaxPath);
        if ($editable) {
            $param="";
            if ($this->_Template) {
                //crear plantillas
                $param = "/" . App_Util_SafeUrl::encryptString("idTemplate/$this->_idTemplate");
            }
            $language = App_Util_Language::getTextLanguage("settings");
            $text = $language->templateGrid->list->SelectTemplate;
            $ajaxDraw = 'function(){
                    $(".dataTables_processing").hide();
                    
                    $(oTable.fnGetNodes()).find("td [class=' . "'edit'" .
             ']")' . ".editable('url/update{$param}',{
             			
                         'submitdata': function ( value, settings ) {
                         
                            return { 'id': oTable.fnGetData( this.parentNode )[0], // get the value of first row/column. id COLUMN
                                'columnId': oTable.fnGetPosition( this )[2]
                                , // Column number
                            };
                         },\n
                         'callback': function( sValue, y ) {
                            
                            /* Redraw the table from the new data on the server */
                           //  oTable.fnDraw(true);
                         },\n
                         
                  

                    } );
                             if(nCelledit.length>0){ 
                                keys.fnSetPosition(nCelledit[0],nCelledit[1]); \n
                            }\n
                    
            }";
            $this->setAttribute("fnDrawCallback", $ajaxDraw);
            $rowDraw = " function( nRow, aData, iDisplayIndex) {
              
                    $('.dataTables_processing').hide();
                    if(aData[aData.length-1] && typeof aData[aData.length-1] !='undefined' && typeof aData[aData.length-1].class !== 'undefined' && aData[aData.length-1].class){
                        
                        $ (nRow).addClass(aData[aData.length-1].class);
                    }
                    return nRow;
                }";
            $this->setAttribute("fnRowCallback", $rowDraw);
            $fnServerData = " function ( sSource, aoData, fnCallback ) {
						if( $('#grid').is(':visible') ) {
	     					$.post(sSource,aoData,fnCallback,'json').error(function() { alert('error'); });
                         }
                        	
                       
		}";
            //$this->setAttribute ( "fnServerData", "fnDataTablesPipeline" );
            $this->setAttribute("fnServerData", $fnServerData);
        }
    }
    /**
     * Coloca los filtros por cada columna
     * @param string $position thead/tfoot dependiendo si los filtros se quieren
     * en la cabecera o en el footer
     */
    //FIXME: Revisar problema de css al activar el sDom se tiene funcionalidad 
    //pero se pierden los estilos
    public function FiltersColumns ($position = 'thead')
    {
        $this->_filtersColumns = true;
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.DataTables.min.js"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.dataTables.ColReorder.min.js"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.dataTables.ColVis.min.js"));
        $this->_positionFilters = $position;
        if (isset($this->_attributesExtras["FixedHeader"])) {
            $this->_positionFilters = 'tfoot';
        }
        $this->_arrayExtrasJs[] = "     
        	/* Add the events etc before DataTables hides a column */
                $('$this->_positionFilters input').keyup( function () {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, oTable.oApi._fnVisibleToColumnIndex( 
                    oTable.fnSettings(), $('$this->_positionFilters input').index(this) ) );
                } );
	
                /*
                 * Support functions to provide a little bit of 'user friendlyness' to the textboxes
                 */
                $('$this->_positionFilters input').each( function (i) {
                        this.initVal = this.value;
                } );

                $('$this->_positionFilters input').focus( function () {
                        if ( this.className == 'search_init' )
                        {
                                this.className = '';
                                this.value = '';
                        }
                } );
	
                $('$this->_positionFilters input').blur( function (i) {
                        if ( this.value == '' )
                        {
                                this.className = 'search_init';
                                this.value = this.initVal;
                        }
                } );";
        $this->_attributesTable["sDom"] = "'RC<\"clear\"><\"fg-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix\"lfr>t<\"fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix\"ip>'";
    }
    /**
     * 
     * Setea el id de la plantilla y define que se van a manejar plantillas
     * @param boolean $Template false no tiene plantillas true en caso contrario
     * @param int $idTemplate id de la plantillla a cargar
     */
    public function setTemplate ($Template = false, $idTemplate = null)
    {
        $this->_Template = $Template;
        $this->_idTemplate = $idTemplate;
    }
    
    /**
     * Configuración de botones para exportar, imprimir o copiar la grilla
     * @param array  $buttons Arreglo de botones que se desean ver
     */
    function buttonsTableTools ($buttons = null)
    {
        $front = Zend_Controller_Front::getInstance();
        $view = $front->getParam('bootstrap')->getResource('view');
        $view->JQuery()->addStylesheet($view->baseUrl("/css/TableTools.css"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.DataTables.min.js"));
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/TableTools.min.js"));
        $this->_attributesTable["sDom"] = "'T<\"clear\"><\"fg-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix\"lfr>t<\"fg-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix\"ip>'";
        $this->setTableTools("sSwfPath", 
        '"' . $view->baseUrl("/media/swf/copy_cvs_xls_pdf.swf") . '"');
        if (! empty($buttons) || !is_null($buttons)) {
            $optionsButtons = "[";
            foreach ($buttons as $button) {
                $optionsButtons .= "{";
                if (! isset($button["sFileName"]) && isset($button["sExtends"]) &&
                 $button["sExtends"] != 'collection') {
                    $optionsButtons .= '"sFileName":"' .
                     Zend_Date::now()->getTimestamp() . $button["sExtends"] .
                     '",' . "\n";
                }
                $buttonProperties = array_keys($button);
                foreach ($buttonProperties as $buttonProperty) {
                    if ($buttonProperty != 'aButtons') {
                        $optionsButtons .= '"' . $buttonProperty . '":';
                        if ($buttonProperty == 'fnClick') {
                            $optionsButtons .= $button[$buttonProperty] . ',' .
                             "\n";
                        } else {
                            $optionsButtons .= '"' . $button[$buttonProperty] .
                             '",' . "\n";
                        }
                    } else {
                        if (is_array($button[$buttonProperty])) {
                            $optionsButtons .= '"' . $buttonProperty . '":[' .
                             "\n";
                            foreach ($button[$buttonProperty] as $collectionButton) {
                                $optionsButtons .= "{\n";
                                foreach ($collectionButton as $i => $propertyCollectionButton) {
                                    $optionsButtons .= '"' . $i . '":';
                                    if ($i != 'fnClick') {
                                        $optionsButtons .= '"' .
                                         $propertyCollectionButton . '",' . "\n";
                                    } else {
                                        $optionsButtons .= $propertyCollectionButton .
                                         ",\n";
                                    }
                                }
                                $optionsButtons .= "},\n";
                            }
                            $optionsButtons .= "]\n,";
                        }
                    }
                }
                if (isset($button["sExtends"]) && $button["sExtends"] == 'csv' ||
                 $button["sExtends"] == 'xls') {
                    $optionsButtons .= '"sCharSet": "utf16le",' . "\n";
                    $optionsButtons .= "sFieldBoundary:" . "'" . '"' . "'," .
                     "\n";
                }
                $optionsButtons = substr($optionsButtons, 0, - 1);
                $optionsButtons .= "},\n";
            }
            $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
            if ($this->_Template) {
                //crear plantillas
                $param = App_Util_SafeUrl::encryptString(
                "idTemplate/$this->_idTemplate");
                $url = $baseUrl . "/settings/templateGrid/index/" . $param;
                $language = App_Util_Language::getTextLanguage("settings");
                $text = $language->templateGrid->list->SelectTemplate;
                $optionsButtons .= '{
                                    "sExtends": "ajax",
                                    "sButtonText": "'.$language->templateGrid->list->CurrentlyTemplate.'",
                                    "fnClick":function ( nButton, oConfig, oFlash ) {
                                       $("#templateDiv").html("");
                                       $("#templateDiv").load("' . $url . '").dialog({
                                            width: 650,
                                            position: "top",
                                            autoOpen: false,
                                            modal: true,
                                            zIndex: 1012
                                        }); 
                                       $("#templateDiv").dialog("open");
                                    },
                                    "sButtonClass": "template_button",
                                    "sButtonClassHover": "template_button_hover"
                                },' . "\n";
                //listar plantillas
                $urlList = $baseUrl .
                 "/settings/templateGrid/list/$param";
                $optionsButtons .= '{
                                    "sExtends": "ajax",
                                    "sButtonText": "' . $text . '",
                                    "fnClick":function ( nButton, oConfig, oFlash ) {
                                       $("#templateDiv").html("");
                                       $("#templateDiv").load("' . $urlList . '").dialog({
                                            width: 650,
                                            position: "top",
                                            autoOpen: false,
                                            modal: true,
                                            zIndex: 1012
                                        }); 
                                       $("#templateDiv").dialog("open");
                                    },
                                    "sButtonClass": "template_listbutton",
                                    "sButtonClassHover": "template_listbutton_hover"
                                },' . "\n";
                //administrar plantillas
                $urlAdmin = $baseUrl .
                 "/settings/templateGrid/admin/" . $param;
                $optionsButtons .= '{
                                    "sExtends": "ajax",
                                    "sButtonText": "'.$language->templateGrid->list->AdminTemplate.'",
                                    "fnClick":function ( nButton, oConfig, oFlash ) {
                                       $("#templateDiv").html("");
                                       $("#templateDiv").load("' . $urlAdmin . '").dialog({
                                            width: 650,
                                            position: "top",
                                            autoOpen: false,
                                            modal: true,
                                            zIndex: 1012
                                        }); 
                                       $("#templateDiv").dialog("open");
                                    },
                                    "sButtonClass": "template_adminbutton",
                                    "sButtonClassHover": "template_adminbutton_hover"
                                }' . "\n";
            }
            $optionsButtons .= "]\n";
            $this->setTableTools("aButtons", $optionsButtons);
        } else {
            $this->setTableTools("aButtons", 
            '[{
                                "sFileName":"' .
             Zend_Date::now()->getTimestamp() . '.csv",
                                "sExtends": "csv",
                                "sCharSet": "utf16le",
                                "sFieldBoundary":' . "'" . '"' . "'" . '
                            },
                            {
                                "sFileName":"' .
             Zend_Date::now()->getTimestamp() . '.xls",
                                "sCharSet": "utf16le",
                                "sExtends": "xls"
                            }, 
                            {
                                "sFileName":"' .
             Zend_Date::now()->getTimestamp() . '.pdf",
                                "sExtends": "pdf",
                                "sPdfOrientation": "landscape"
                            }
                           
                            

                ]');
        }
    }
    /**
     * Renderiza la grilla
     * @param array $data datos a mostrar en la grilla
     * @param string $idTable identificador de la grilla
     * @return string html de la grilla con su respectivo js
     */
    function renderElement ($data = NULL, $idTable = NULL,$properties=array(),$paramUpdate ="")
    {
        $txtHTML = "";
        $aoColumns = "";
        $separadorColumns = "";
        $idioma = App_User::getLanguage();
        $urlLanguage = APPLICATION_PATH . "\langs\dataTable_" . $idioma . ".txt";
        $this->setAttribute("oLanguage", file_get_contents($urlLanguage, true));
        $txtHTML .= "<div id='templateDiv'></div><input type='hidden' id='currentTemplate' name='currentTemplate'/>";
        if (empty($idTable))
            $idTable = "dataTable" . Zend_Date::now()->getTimestamp();
        if (! empty($data)) {
            //dibujar tabla
            $txtHTML .= '<div id="grid"><table class="iceDatTbl" id="' .
             $idTable . '">';
            $txtHTML .= '<thead>
		<tr>';
            if (count($this->_columns) > 0) {
                foreach ($this->_columns as $key => $value) {
                    if ((! isset($this->_columns[$key]["params"]["visible"]) ||
                     $this->_columns[$key]["params"]["visible"] == true)) {
                        $txtHTML .= '<th>' . $this->_columns[$key]["title"] .
                         '</th>';
                    }
                }
            } else {
                foreach ($data[0] as $key => $value) {
                    $txtHTML .= '<th>' . $key . '</th>';
                }
            }
            $txtHTML .= '</tr>';
            if ($this->_filtersColumns && $this->_positionFilters == 'thead') {
                $txtHTML .= '<tr>';
                if (count($this->_columns) > 0) {
                    foreach ($this->_columns as $key => $value) {
                        if (! isset($this->_columns[$key]["params"]["visible"]) ||
                         $this->_columns[$key]["params"]["visible"] == true) {
                            $txtHTML .= '<td><input type="text" name="search_' .
                             $this->_columns[$key]["title"] . 'value="search_' .
                             $this->_columns[$key]["title"] .
                             'class="search_init" /></td>';
                        }
                    }
                } else {
                    $txtHTML .= '<td><input type="text" name="search_' . $key .
                     'value="search_' . $key . 'class="search_init" /></td>';
                }
                $txtHTML .= '</tr>';
            }
            $txtHTML .= '</thead><tbody>';
            if (count($this->_columns) > 0) {
                $keysColumns = array_keys($this->_columns);
                foreach ($data as $indexData => $valueData) {
                    if (is_object($valueData)) {
                        $valueData = $valueData->toArray();
                        $valueData = App_Util_Array::sortArrayByArray(
                        $valueData, $keysColumns);
                    }
                    $i = 0;
                    foreach ($valueData as $key => $value) {
                        if ($i == 0) {
                            $txtHTML .= '<tr id="' . $value . '"';
                           if(isset($properties[$value])){
                                  
                                $arrayproperties=$properties[$value];
                               
                                foreach ($arrayproperties as $property=>$val) {
                                    $txtHTML .=" $property='$val'";
                                }
                            }
                            $txtHTML.='>';
                        }
                        if (! isset($this->_columns[$key]["params"]["visible"]) ||
                         $this->_columns[$key]["params"]["visible"] == true) {
                            $txtHTML .= '<td charName="' . $key . '" ';
                            if (isset($this->_columns[$key]["params"]["class"])) {
                                $txtHTML .= "class='" .
                                 $this->_columns[$key]["params"]["class"] . "'";
                            }
                            $txtHTML .= ">" . $value . "</td>";
                        }
                        $i ++;
                    }
                    $txtHTML .= '</tr>';
                }
            } else {
                foreach ($data as $indexData => $valueData) {
                    if (is_object($valueData)) {
                    	if (method_exists($valueData, 'toArray')) {
                        	$valueData = $valueData->toArray();
                    	}
                    	else{
                        	$valueData=App_Util_Array::object2Array_recursive($valueData);
                    	}
                    }
                    $i = 0;
                    foreach ($valueData as $key => $value) {
                        if ($i == 0) {
                           $txtHTML .= '<tr id="' . $value . '"';
                           if(isset($properties[$value])){
                                  
                                $arrayproperties=$properties[$value];
                               
                                foreach ($arrayproperties as $property=>$val) {
                                    $txtHTML .=" $property='$val'";
                                }
                            }
                            $txtHTML.='>';
                        }
                        if (count($this->_columns) > 0 &&
                         isset($this->_columns[$key])) {
                            if (! isset(
                            $this->_columns[$key]["params"]["visible"]) ||
                             $this->_columns[$key]["params"]["visible"] == true) {
                                $txtHTML .= '<td charName="' . $key . '" ';
                                if (isset(
                                $this->_columns[$key]["params"]["class"])) {
                                    $txtHTML .= "class='" .
                                     $this->_columns[$key]["params"]["class"] .
                                     "'";
                                }
                                $txtHTML .= ">" . $value . "</td>";
                            }
                        } else {
                            $txtHTML .= '<td charName="' . $key . '" >' . $value .
                             "</td>";
                        }
                        $i ++;
                    }
                    $txtHTML .= '</tr>';
                }
            }
            $txtHTML .= '</tbody>';
            if ($this->_filtersColumns && $this->_positionFilters == 'tfoot') {
                $txtHTML .= '<tfoot><tr>';
                if (count($this->_columns) > 0) {
                    foreach ($this->_columns as $key => $value) {
                        if (! isset($value["params"]["visible"]) ||
                         $value["params"]["visible"] == true) {
                            $txtHTML .= '<th><input type="text" name="search_' .
                             $value["title"] . 'value="search_' . $value["title"] .
                             'class="search_init" /></th>';
                        }
                    }
                } else {
                    foreach ($data[0] as $key => $value) {
                        $txtHTML .= '<th><input type="text" name="search_' . $key .
                         'value="search_' . $key . 'class="search_init" /></th>';
                    }
                }
                $txtHTML .= '</tr></tfoot>';
            }
            $txtHTML .= '</table></div>';
        }
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $url = $baseUrl . "/" . $request->getModuleName() . "/" .
         $request->getControllerName();
        $txtHTML .= "<script type=\"text/javascript\">\n";
        $txtHTML .= "var keys='';\n";
        $txtHTML .= "var oTable='';\n";
        $txtHTML .= "var nCelledit='';\n";
         $txtHTML .= '
        
    
        
        $.fn.dataTableExt.oApi.fnFilterClear  = function ( oSettings,redraw )
{
    /* Remove global filter */
    oSettings.oPreviousSearch.sSearch = "";
     
    /* Remove the text of the global filter in the input boxes */
    if ( typeof oSettings.aanFeatures.f != "undefined" )
    {
        var n = oSettings.aanFeatures.f;
        for ( var i=0, iLen=n.length ; i<iLen ; i++ )
        {
            $("input", n[i]).val( "" );
        }
    }
     
    /* Remove the search text for the column filters - NOTE - if you have input boxes for these
     * filters, these will need to be reset
     */
    for ( var i=0, iLen=oSettings.aoPreSearchCols.length ; i<iLen ; i++ )
    {
        oSettings.aoPreSearchCols[i].sSearch = "";
    }
     if(redraw){
        /* Redraw */
        oSettings.oApi._fnReDraw( oSettings );
    }
};';
        $txtHTML .= "$(document).ready(function(){\n";
        $txtHTML .= " oTable = $('#{$idTable}').dataTable( {\n ";
        $arrayExcepciones = array("otherParams");
        foreach ($this->_attributesTable as $attributes => $values) {
            if (($values != "") && ! (in_array($attributes, $arrayExcepciones))) {
                if ($attributes == "fnDrawCallback" ||
                 $attributes == "fnServerData") {
                    $values = str_replace("url", $url, $values);
                    $values = str_replace("idTable", $idTable, $values);
                }
                $txtHTML .= '"' . $attributes . '" : ';
                if (is_array($values)) {
                    $txtHTML .= "{\n";
                    foreach ($values as $attributesValue => $valuesValue) {
                        $txtHTML .= "           \"{$attributesValue}\": {$valuesValue},\n";
                    }
                    $txtHTML = substr($txtHTML, 0, strlen($txtHTML) - 2);
                    $txtHTML .= "},\n";
                } else {
                    $txtHTML .= "{$values},\n";
                }
            }
        }
        $txtHTML = substr($txtHTML, 0, strlen($txtHTML) - 2);
        $txtHTML .= "\n} );\n";
        $txtHTML .= "oTable.fnAdjustColumnSizing();\n";
        //$txtHTML.="oTable.fnColumnIndexToVisible();\n";
        foreach ($this->_arrayExtrasJs as $value) {
            $txtHTML .= $value;
        }
        $txtHTML .= "\n" . '$(".dataTables_wrapper").css("min-height","100px");';
        if ($this->_isEditable) {
            if ($this->_Template) {
                //crear plantillas
                $paramUpdate .= "/" . App_Util_SafeUrl::encryptString(
                "idTemplate/$this->_idTemplate");
            }
            $txtHTML .= "oTable.makeEditable(
            {sUpdateURL: '{$url}/update{$paramUpdate}', sUpdateHttpMethod: 'POST',";
            $txtHTML .="fnOnEditing: function(jInput)
                {       
                 setTimeout( function () {keys.block = false;}, 0);
                 cell= jInput.parents('tr')
                                               .children('td:nth-child(".$this->_column_ant.")')
                                               .text();
                                
                  return true;
                },
                
                  
                oUpdateParameters: {
                                cell: function(){ 
                                return cell; } 
                        },
                
            
                            ";
            $txtHTML .= '"aoColumns":' . $this->_aoColumns . ",";
            $txtHTML .= " });";
            if (! empty($this->_hiddenColumns)) {
                foreach ($this->_hiddenColumns as $hidden) {
                    $txtHTML .= "oTable.fnSetColumnVis( $hidden, false );\n";
                }
            }
        }
        //cargar link dialog
        /* if ("" != $url) {
          $dlgHTML .= ",\n open: function() {\n";
          $dlgHTML .= "$(\"#{$arr_keys [$i]}\").load('{$url}{$parametros}');}";
          } */
        //$txtHTML .= "\n};\n";
        $view = Zend_Controller_Front::getInstance()->getParam(
        'bootstrap')->getResource('view');
        $view->JQuery()->addJavascriptFile(
        $view->baseUrl("/js/jquery/jquery.DataTables.min.js"));
        foreach ($this->_attributesExtras as $attributes => $values) {
            $view->JQuery()->addJavascriptFile(
            $view->baseUrl("/js/jquery/jquery.dataTables.$attributes.min.js"));
            $txtHTML .= ' new ' . $attributes . '(oTable';
            if (is_array($values)) {
                $txtHTML .= ",{ ";
                foreach ($values as $attributesValue => $valuesValue) {
                    $txtHTML .= '        "' . $attributesValue . '": ' .
                     $valuesValue . ",\n";
                }
                $txtHTML = substr($txtHTML, 0, strlen($txtHTML) - 2);
                $txtHTML .= "});\n";
            } else {
                $txtHTML .= ");\n";
            }
        }
        $txtHTML .= "});\n
        ";
        if ($this->_AbleKeys && $this->_isEditable) {
            $txtHTML .= " 
            keys = new KeyTable( {
		'table': document.getElementById('{$idTable}'),
            } );
	/* Apply a return key event to each cell in the table */
	keys.event.action( null, null, function (nCell) {
                nCelledit=keys.fnGetCurrentPosition();
                $(nCell).dblclick()
                if($(nCell).find('input').length){
                     keys.block = true;\n
                }
                else{
                     keys.block = false;\n
                }
                if(keys.block){
                    keys.block = false;\n
                    $(nCell).dblclick();\n
                }
                else{
                    /* Block KeyTable from performing any events while jEditable is in edit mode */
                      keys.block = true;\n
                      $(nCell).dblclick();\n
                }
                if($(nCell).find('input').length){
                     keys.block = true;\n
                }
                else{
                     keys.block = false;\n
                }
	} );\n
        keys.event.blur( null, null, function (nCell) {
            keys.block = false;\n
	} );\n
	// ------------------------------------------------------
		
	";
        }
        if ($this->_hiddenBeforeSearch) {
            $txtHTML .= "$('#grid').hide();";
        }
        $txtHTML .= "</script>\n";
        //Zend_Debug::dump('"-----    Inicio  ----------'.$txtHTML.'"-----    Fin  ----------');
        return $txtHTML;
    }
}