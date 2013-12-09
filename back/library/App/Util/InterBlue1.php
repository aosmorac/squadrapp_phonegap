<?php
class App_Util_InterBlue1
{
	private $_user = "";
	private $_password = "";
	private $_SERVICE_URL = "";
	private $client = false;


	function __construct ($url, $usuario, $password)
	{
            $this->_SERVICE_URL = $url;
            $this->_user = $usuario;
            $this->_password = $password;
		try
		{
			$this->client = new Zend_Soap_Client($this->_SERVICE_URL);
		}
		catch(Exception $e)
		{
			App_Util::log(Zend_Debug::dump($e->getMessage(),'',false),"error");
		}
	}
	
	
       
	function  GetClients(){
                //$this->client->setCredentials(array("user"=>$this->_user, "password"=>$this->_password));
            Zend_Debug::dump(array("user"=>$this->_user, "password"=>$this->_password));
                $obj = $this->client->GetClients(array("user"=>$this->_user, "password"=>$this->_password));
		return $obj->GetClientsResult;
	}
        
        function  GetBlsByClient($nit){
                $this->client->setCredentials(array("user"=>$this->_user, "password"=>$this->_password));
                $obj = $this->client->GetBlsByClient(array("user"=>$this->_user, "password"=>$this->_password, "nit"=>$nit));
		return $obj->GetBlsByClientResult;
	}
        
        function  GetBl($bl){
                $this->client->setCredentials(array("user"=>$this->_user, "password"=>$this->_password));
                $obj = $this->client->GetBl(array("user"=>$this->_user, "password"=>$this->_password, "bl"=>$bl));
		return $obj->GetBlResult;
	}

}