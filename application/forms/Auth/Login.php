<?php
/*
 * Forumlaire d'authenfication
 ============================================*/

class Application_Form_Auth_Login extends Zend_Form{
    
    public function init(){
        $this->setMethod('post');
        //Champs username
        $this->addElement(
                'text',
                'username',
                array(
                    'label'     => 'Username : ',
                    'required'  => true,
                    'filter'    => array('StringTrim'),
                )
            );
        //Champs password
        $this->addElement(
                'password',
                'password',
                array(
                    'label'     => 'Password : ',
                    'required'  => true,
                )
            );
        //Bouton de validation
        $this->addElement(
                'submit',
                'submit',
                array(
                    'ignore'    => true,
                    'label'     => 'Login',
                )
            );
    }
}