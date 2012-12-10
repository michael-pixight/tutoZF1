<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }
    
    public function indexAction()
    {
        $albums = new Application_Model_DbTable_Albums();
        $this->view->albums = $albums->fetchAll();
    }
    
    public function modifierAction()
    {
        $form = new Application_Form_Album();
        $form->envoyer->setLabel('Sauvegarder');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = $form->getValue('id');
                $artiste = $form->getValue('artiste');
                $titre = $form->getValue('titre');
                $albums = new Application_Model_DbTable_Albums();
                $albums->modifierAlbum($id, $artiste, $titre);
                
                $this->_helper->redirector('index');
            }else {
                $form->populate($formData);
            }
        }else{
            $id = $this->_getParam('id', 0);
            if($id > 0){
                $albums = new Application_Model_DbTable_Albums();
                $form->populate($albums->obtenirAlbum( $id ) );
                
            }
        }
    }

    public function ajouterAction()
    {
        $form = new Application_Form_Album();
        $form->envoyer->setLabel('Ajouter');
        $this->view->form = $form;
        //echo '<pre>', print_r( $this->view->form , 1), '</pre>';

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $artiste = $form->getValue('artiste');
                $titre = $form->getValue('titre');
                $albums = new Application_Model_DbTable_Albums();
                $albums->ajouterAlbum($artiste, $titre);
                
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        }
    }

    public function supprimerAction( $id )
    {
        $albums = new Application_Model_DbTable_Albums();
        $id = $this->_getParam('id', 0);
        $this->view->album = $albums->obtenirAlbum($id);
        
        if ($this->getRequest()->isPost()) {
            $supprimer = $this->getRequest()->getPost('supprimer');
            //echo $supprimer;
            if($supprimer == "Oui"){
                $id = $this->getRequest()->getPost('id');
                $albums->supprimerAlbum($id);
                $this->_helper->redirector('index');
            }
            else{
                $this->_helper->redirector('index');
            }
        }
        

        
        //$artiste = $this->album['artiste'];
        //echo $artiste;
        //$titre = $albums->getValue('titre');
        //$albums = new Application_Model_DbTable_Albums();
        //$albums->delete($id);
    }
}
