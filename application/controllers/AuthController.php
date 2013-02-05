<?php

class AuthController extends Zend_Controller_Action{
    
    public function loginAction(){
        echo 'resultat valide';
        $db = $this->_getParam('db');
        $loginForm = new Application_Form_Auth_Login();
        
        if( $loginForm->isValid($_POST) ){
            echo 'resultat valide';
            $adapter = new Zend_Auth_Adapter_DbTable(
                    $db,
                    'users',
                    'username',
                    'password'
                    /*'MD5(CONCAT(?, password_salt))'*/
            );
            
            $adapter->setIdentity($loginForm->getValue('username'));
            $adapter->setCredential($loginForm->getValue('password'));
            
            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($adapter);
            
            if( $result->isValid() ){
                echo 'resultat valide';
                $this->_helper->FlashMessenger('Successful login');
                //$this->_redirect('/');
                return;
            }else{
                echo 'resultat invalide';
            }
        }
        $this->view->loginForm = $loginForm;
    }
    
    
}