<?php

class AuthController extends Zend_Controller_Action{
    
    public function loginAction(){        
                    
        //$this->_redirect('/');
        
        $db = $this->_getParam('db');
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
            
            $auth   = Zend_Auth::getInstance();            
            $result = $auth->authenticate($adapter);
            Zend_Debug::dump($result);
            //$this->view->identity = $auth->getIdentity();
            
            if( $result->isValid() ){
                $auth->getStorage()->write(array('username' => $loginForm->getValue('username') ));
                $this->_helper->FlashMessenger('Successful login');
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