<?php
class loginController extends ControllerBase
{

    public function index(){
      
		$this->view->show("login.php", array(),false);
		
    }
	 
	public function login()
	{
    	require 'models/loginModel.php';
    	$loginModel = new loginModel();
    	
    	$loginModel->login(gett('user'),gett('pass'));
	}
 
	public function logout()
	{
		require 'models/loginModel.php';
    	$loginModel = new loginModel();
    	$loginModel->logout();
	}
}
?>
