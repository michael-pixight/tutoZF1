<?php

class Application_Model_BlogPost implements Zend_Acl_Resource_Interface{
    
    public function getResourceId() {
        return 'blogPost';
    }
}