<?php
/*aide de vue permettant d'afficher le nom de l'utilisateur s'il est loggé + un lien de logout
 ==============================================================================================*/

class Zend_View_Helper_loggedInAs extends Zend_View_Helper_Abstract{
    
    public function loggedInAs(){
        $auth = Zend_Auth::getInstance();
        
        //Si le client est identifié on lui retourne le message de bienvenue et le liens pour se delogger
        if( $auth->hasIdentity() ){
            //echo '<pre>', print_r($auth->getIdentity(), 1), '</pre>';
            $username = $auth->getIdentity()->username;
            $role = $auth->getIdentity()->role;
            $logoutUrl = $this->view->url(array('controller'=>'auth', 'action'=>'logout'));
            return 'Welcome ' . $username .' role : '. $role .'<a class="btn pull-right" href="' . $logoutUrl . '">Logout</a>';
        }
        
        //Il n'est pas loggé mais sur la page de login, on ne lui retourne donc aucun lien
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllername();
        $action = $request->getActionName();
        
        if($controller == 'auth' && $action == "login"){            
            return '';
        }
        
        //Il n'est pas loggé, on ne lui retourne alors le lien pour se logger
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'login'));
        return '<a class="btn" href="'.$loginUrl.'">Login</a>';
    }
}