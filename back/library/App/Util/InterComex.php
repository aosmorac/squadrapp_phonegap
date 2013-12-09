<?php
class App_Util_InterComex
{
	private $_UID = "";
	private $_PASSWD = "";
	private $_SERVICE_URL = "";
	private $client = false;

	
	function __construct ($options = Array())
	{
		$this->_SERVICE_URL = "http://iisps01/testws/PaperSaveService.asmx?wsdl";
		try
		{
			$this->client = new Zend_Soap_Client($this->_SERVICE_URL);
			//funciones disponibles en el webservice
			
			//Zend_Debug::dump( $this->client->getFunctions());
			//die;
			//return true;

		}
		catch(Exception $e)
		{
			App_Util::log(Zend_Debug::dump($e->getMessage(),'',false),"error");
		}
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

}