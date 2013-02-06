<?php
//Je cree les roles et les distribus en fonction de mon fichier acl.ini

class Appli_Acl extends Zend_Acl{
    
    public function __construct() {
        $aclFile = APPLICATION_PATH . '/configs/acl.ini';
        $config = new Zend_Config_Ini($aclFile);
        
        //cration du role public et auth par heritage
        $this->addRole( new Zend_Acl_Role('PUBLIC') );
        $this->addRole( new Zend_Acl_Role('AUTH'), 'PUBLIC');
        
        foreach ($config as $contoller => $actions){
            foreach ($actions as $action => $roles){
                $roles = explode(',', $roles);
                $resource = $contoller . '_' . $action;                
                foreach($roles as $role){
                    $role = $role != '' ? $role : 'PUBLIC';
                    //Le role peut deja avoir été ajouté, si ce n'est pas le cas je l'ajoute
                    if( !$this->hasRole($role) ){
                        $this->addRole(new Zend_Acl_Role($role), 'AUTH');
                    }
                    //Je lie le role à la ressource
                    $this->allow($role, $resource);
                }
            }
        }
    }
}
