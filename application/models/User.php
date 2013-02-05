<?php

class Application_Model_User implements Zend_Acl_Role_Interface{
    
    protected $_aclRoleId = null;
    
    public function getRoleId() {
        if($this->_aclRoleId == null){
            return 'guest';
        }
        
        return $this->_aclRoleId;
    }
    
    
}
