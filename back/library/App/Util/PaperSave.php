<?php
class App_Util_PaperSave
{
	private $_UID = "";
	private $_PASSWD = "";
	private $_SERVICE_URL = "";
	private $client = false;

	private $CompanyId = 9000;//interblue
	private $DocumentTypeId = 72;//Otros
	private $Fields = "{}";
	private $HostApplicationId = 9000;//BLUENET
	private $ModuleId = 9000;//Importacion Maritima
	private $ParentId = "";
	private $TransactionTypeId = 9000;//interblue

	function __construct ($options = Array())
	{
		//$this->_SERVICE_URL = "http://pstest02/test/PaperSaveService.asmx?wsdl";
							 //http://iisps01/PaperSaveWS/PaperSaveService.asmx
		$this->_SERVICE_URL = "http://iisps01/testws/PaperSaveService.asmx?wsdl";
		try
		{
			$this->client = new Zend_Soap_Client($this->_SERVICE_URL);
			//funciones disponibles en el webservice
			
			//Zend_Debug::dump( $this->client->getFunctions());
			//die;
			/*$urlreport = "http://bluenet.bluecargogroup.com/globalBluenet/manager/reports/view/report/_BLUENET_INTERBLUE_IMPRIMIBLES_ImprimiblesSIA_Anticipos/DO/201205044/format/PDF";
			$contentreport = file_get_contents($urlreport);
			
			$hostId				= $this->HostApplicationId;
			$companyId			= $this->CompanyId;
			$moduleId			= $this->ModuleId;
			$transactionTypeId	= $this->TransactionTypeId;
			$documentTypeId		= $this->DocumentTypeId;
			$stream 			= $contentreport;
			$name   			= "anticipos.pdf";
			$extension  		= ".pdf";
			//echo "201205044,$urlreport,,$hostId,$companyId,$moduleId,$transactionTypeId,$documentTypeId,$stream,$name,$extension";die;
			$idData = $this->AddDocument("201205044",$urlreport,array("NumeroDO"=>"201205044"),$hostId,$companyId,$moduleId,$transactionTypeId,$documentTypeId,$stream,$name,$extension);
			Zend_Debug::dump($idData);
			die;*/
			//return true;

		}
		catch(Exception $e)
		{
			App_Util::log(Zend_Debug::dump($e->getMessage(),'',false),"error");
		}
	}
	
	/**
	 * @param string $param Nombre del parametro a asignar, valores posibles (CompanyId,DocumentTypeId,Fields,HostApplicationId,ModuleId,ParentId,TransactionTypeId)
	 */
	function setParam($param,$valueParam){
		$this->$param = $valueParam;
		
	}

	/**
	 * @param int Id del objeto en relación
	 * @param string Cadena de parametros del documento en formato json
	 * @param int Tipo de documento que se va a subir
	 * @param int id de la compañía relacionada al objeto
	 * @param int id del host de la compañía
	 * @param int id del módulo
	 * @param int id del tipo de transacción
	 * @return object Retorna objeto Descriptor
	 */
	function objDescriptor($ParentId,$Fields,$DocumentTypeId=null,$CompanyId=null,$HostApplicationId=null,$ModuleId=null,$TransactionTypeId=null){
		$obj = array("CompanyId"			=>($CompanyId)?$CompanyId:$this->CompanyId,
				"DocumentTypeId"		=>($DocumentTypeId)?$DocumentTypeId:$this->DocumentTypeId,
				"Fields"				=>$Fields,
				"HostApplicationId"	=>($HostApplicationId)?$HostApplicationId:$this->HostApplicationId,
				"ModuleId"				=>($ModuleId)?$ModuleId:$this->ModuleId,
				"ParentId"				=>$ParentId,
				"TransactionTypeId"	=>($TransactionTypeId)?$TransactionTypeId:$this->TransactionTypeId
		);
		return $obj;
	}

	/**
	 * @param string Ruta del documento
	 * @param string Datos del archivo a guardar
	 * @param string Nombre del archivo
	 * @param string Extensión del archivo
	 * @return object Retorna objeto archivo
	 */
	function objFile($Path,$Stream=false,$Name=false,$Extension=false){
		if(!$Stream) 	$Stream 	= file_get_contents($Path);
		if(!$Name)   	$Name   	= pathinfo($Path, PATHINFO_FILENAME );
		if(!$Extension) $Extension  = pathinfo($Path, PATHINFO_EXTENSION );
		$obj = array("Extension" =>".".$Extension,
				"Name"		 =>$Name,
				"Path"		 =>$Path,
				"Stream"	 =>$Stream
		);
		return $obj;
	}

	/**
	 * @param object Descriptor del documento
	 * @param object Detales y continido del archivo a adjuntar
	 * @return object Retorna objeto con el id del archivo subido
	 */
	function  AddDocumentFor($descriptor,$file){
		$obj = $this->client->AddDocumentFor($descriptor,$file);
		return $obj;
	}

	/**
	 * @param $parentId int id del objeto,
	 * @param $path     string Ruta fisica del archivo,
	 * @param $fields	array de la metadata del tipo de archivo
	 * @param $hostId	int id del host de la aplicación
	 * @param $companyId	int id de la compañía
	 * @param $moduleId	id del modulo
	 * @param $transactionTypeId	id de la transacción
	 * @param $documentTypeId		id del tipo de documento
	 * @param $stream 			string con el contenido del archivo
	 * @param $name   			string nombre del archivo
	 * @param $extension  		string extensión del archivo
	 * @return object Retorna objeto con el id del archivo subido
	 */
	function  AddDocument($parentId,$path,$fields=array(),$hostId=null,$companyId=null,$moduleId=null,$transactionTypeId=null,$documentTypeId=null,$stream=null,$name=null,$extension=null){
		$hostId				= ($hostId)?$hostId:$this->HostApplicationId;
		$companyId			= ($companyId)?$companyId:$this->CompanyId;
		$moduleId			= ($moduleId)?$moduleId:$this->ModuleId;
		$transactionTypeId	= ($moduleId)?$moduleId:$this->TransactionTypeId;
		$documentTypeId		= ($documentTypeId)?$documentTypeId:$this->DocumentTypeId;
		$stream 			= ($stream)?$stream:file_get_contents($path);
		$name   			= ($name)?$name:pathinfo($path, PATHINFO_FILENAME );
		$extension  		= ($extension)?$extension:pathinfo($path, PATHINFO_EXTENSION );

		/*$fieldsJson="{";
		$separador="";
		foreach ($fields as $key=>$value){
			$comilla = "'";
			if(strpos($key, " ")===false) $comilla = "";
			$fieldsJson.=$separador.$comilla.$key.$comilla.":'".$value."'";
			$separador=",";
		}
		$fieldsJson.="}";*/
		$fieldsFormat=array();
		$fieldsJson = json_encode($fields);
		if(count($fields)==0 || $fieldsJson=='{"":""}') $fieldsJson="{}";
		$extension = strtolower($extension);
		$extension = ".".str_replace(".", "", $extension);
		//$stream = base64_encode($stream);
		$data = array("hostId"=>$hostId,
				"companyId"=>$companyId,
				"moduleId"=>$moduleId,
				"transactionTypeId"=>$transactionTypeId,
				"documentTypeId"=>(int)$documentTypeId,
				"parentId"=>$parentId,
				"fields"=>$fieldsJson,
				"ext"=>$extension,
				"name"=>$name,
				"path"=>$path,
				"stream"=>$stream);
		
		//Zend_debug::dump(array($hostId,$companyId,$moduleId,$transactionTypeId,$documentTypeId,$parentId,$fieldsJson,$extension,$name,$path,count($stream)));
		App_Util::log(Zend_Debug::dump(array($hostId,$companyId,$moduleId,$transactionTypeId,$documentTypeId,$parentId,$fieldsJson,$extension,$name,$path,count($stream)),'',false),"dataPS add");
		//Zend_Debug::dump($data);die;
		//$obj=$this->client->AddDocument(array());
		//die;
		$obj=$this->client->AddDocument($data);
		return $obj->AddDocumentResult;
	}

	/**
	 * @param int Id del documento
	 * @return Obtiene objeto que contiene el documento en relación
	 */
	function  GetDocument($id){
		$obj = $this->client->GetDocument (array("id"=>$id));
		return $obj->GetDocumentResult;
	}

	/**
	 * @param int Id del typo de documento
	 * @param int Id objeto en relacion
	 * @return object Retorna lista de documentos relacionados a un objeto
	 */
	function  GetDocumentsRelatedTo ($documenttypeId,$parentId){
		$obj = $this->client->GetDocumentsRelatedTo (array("documenttypeId"=>$documenttypeId,"parentId"=>$parentId));
		return $obj->GetDocumentsRelatedToResult;
	}

	/**
	 * @param int Id del host
	 * @param int Id del modulo
	 * @return object Retorna lista de Tipos de transacción disponibles para el host y modulo dado
	 */
	function  GetTransactionTypesFor ($hostId,$moduleId){
		$obj = $this->client->GetTransactionTypesFor (array("hostId"=>$hostId,"moduleId"=>$moduleId));
		return $obj;
	}

	/**
	 * @param int Id del tipo de transaccion
	 * @return object Retorna lista de los Tipos de documentos de acuerdo a los modulos
	 */
	function  GetDocumentTypesByTransactionType ($tranTypeId){
                $documentTypes = $this->GetAllDocumentTypes()->DocumentTypeDescriptor;
                $types = array();
                foreach ($documentTypes as $dt) {
                    if ($dt->TransactionTypeID == $tranTypeId) {
                        $types[] = $dt;
                    }
                }
		return $types;
	}
        
        /**
	 * @return object Retorna lista de todos los Tipos de documentos
	 */
	function  GetAllDocumentTypes (){
		$obj = $this->client->GetAllDocumentTypes();
		return $obj->GetAllDocumentTypesResult;
	}

	/**
	 * @param int Id del host
	 * @return object Retorna lista de COmpañias disponibles para el host dado
	 */
	function  GetCompaniesFor(){
		$obj = $this->client->GetCompaniesFor(array("hostId"=>$this->HostApplicationId));
		return $obj;
	}

	/**
	 * @param int Id del host
	 * @return object Retorna lista de Modulos disponibles para el host dado
	 */
	function  GetModulesFor(){
		$obj = $this->client->GetModulesFor(array("hostId"=>$this->HostApplicationId));
		return $obj;
	}


	/**
	 * @return object Retorna lista de Host disponibles en el servidor
	 */
	function  GetAllHostApplications(){
		$obj = $this->client->GetAllHostApplications();
		return $obj;
	}
        
        /**
	 * @param int Id del host
	 */
	function  SetHostId($hostId){
                $this->HostApplicationId = $hostId;
	}

        
        
	function download($id){
		ob_clean();
		header('Content-Description:File Transfer');
		header('Content-Type:'.App_Util_File::getTipoMime("archivo.pdf"));
		header('Content-Disposition:attachment; filename="archivo.pdf"');
		header('Content-Transfer-Encoding:binary');
		header('Expires:0');
		header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
		header('Pragma:public');
		$result = $this->GetDocument($id);
		echo $result->GetDocumentResult;
		die;//10
	}
	
	public static function getFormField($fields){
		$form = new Zend_Form();
		$form->setAttrib('id', 'dataFields');
	
		foreach ($fields as $key => $value) {
			$text = new Zend_Form_Element_Text($key);
			$text->setLabel($key);
			$text->setAttrib("id", $key);
			$text->setAttrib("name", $key);
			if(!empty($value)) $text->setAttrib("readonly", "readonly");
			$text->setValue($value);
			$form->addElement($text);
		}
		return $form;
	}

}