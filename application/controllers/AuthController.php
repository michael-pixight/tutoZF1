<?php

class AuthController extends Zend_Controller_Action{
    
    private $_auth;
 
	public function init(){
		//récupération de l'instance d'authentification
		$this->_auth = Zend_Auth::getInstance();
	}
    
    public function indexAction(){
		//création et affichage dans la vue du formulaire
		$loginForm = new Application_Form_Auth_Login();
		$loginForm->setAction($this->view->url(array('controller' => 'auth', 'action' => 'login'), 'default', true));
        
        $this->view->loginForm = $loginForm;
	}
    
    public function loginAction(){        
                    
        //$this->_redirect('/');
        
        //$db = $this->_getParam('db');
        
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        
        
        $loginForm = new Application_Form_Auth_Login();
        
        if( $loginForm->isValid($_POST) ){
            $adapter = new Zend_Auth_Adapter_DbTable(
                    $db,
                    'users',
                    'username',
                    'password',
                    'MD5(CONCAT(?, salt))'
            );
            
            $adapter->setIdentity($loginForm->getValue('username'));
            $adapter->setCredential($loginForm->getValue('password'));
            
            //$auth   = Zend_Auth::getInstance();            
            $result = $this->_auth->authenticate($adapter);
            
            //$this->view->identity = $auth->getIdentity();
            
            if( $result->isValid() ){
                $this->_auth->getStorage()->write($res = $adapter->getResultRowObject(null, 'password'));
                $this->_helper->FlashMessenger('Successful login');
                //permet de regénérer l'identifiant de session
                Zend_Session::regenerateId();
                //$this->_redirect('/');
                //return;
            }else{                
                echo 'Erreur d\'authentification';
            }
        }
        $this->view->loginForm = $loginForm;
    }
    
    public function logoutAction(){
        Zend_Auth::getInstance()->clearIdentity();
    }
}