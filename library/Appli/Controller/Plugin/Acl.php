<?php
//Plugin qui suis la norme classique de Zend (Zend_Controller_Plugin_XXXX

class Appli_Contoller_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
    
    protected $_acl = null;
    
    public function __construct() {
        require_once "/Appli/Acl.php";
        
        $this->_acl = new Appli_Acl();
    }
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        //parent::routeShutdown($request);
        $identity = (array) Zend_Auth::getInstance()->getIdentity();
        echo '<pre>', print_r($identity, 1), '</pre>';
        if( is_array($identity) && array_key_exists('name', $identity) ){
            $currentUser = $this->_acl->hasRole($identity['name']) ?  $identity['name'] : 'AUTH';             
        }
        else{
            //n'est pas iddentifié
            $currentUser = 'PUBLIC';
        }
        echo '<pre>', print_r($currentUser, 1), '</pre>';
        $resource = $request->getControllerName() . '_' . $request->getActionName();
        echo '<pre>', print_r($resource, 1), '</pre>';
        
        if( !$this->_acl->has($resource)){
            throw new Exception('Ajoutez la ressource dans les regle ACL');
        }
        
        if( !$this->_acl->isAllowed($currentUser, $resource)){
            throw new Exception('pas d\'acces!');
        }
    }
}