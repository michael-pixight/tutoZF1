<?php
/*
 * formulaire d'authentification
 ============================================*/

class Application_Form_Auth_Login extends Zend_Form{
    
    public function init(){
        $this->setName('login');
        $this->setMethod('post');
        
        //Champ username
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