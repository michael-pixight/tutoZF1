<?php
    //"Default" correspond au namespace que nous avons défini dans le bootstrap
    class Application_Form_Entreprises extends Zend_Form
    {
        //l'initialisation et la configuration des éléments de notre formulaire
        //se trouveront dans le contructeur de la classe,
        //de cette façon tous les éléments de notre formulaire seront créé lors
        //de l'instaniation d'un obje de type Application_Form_Entreprises
        public function  __construct($options = null) {
            parent::__construct($options);
 
            //donne un nom à notre formulaire
            $this->setName('entreprises');
 
            //création d'un élément de input de type hidden
            $id = new Zend_Form_Element_Hidden('id');
 
            //création d'un élément de input de type text
            $name = new Zend_Form_Element_Text('name');
             //indique le label à utiliser pour l'élément
            $name->setLabel('name: ');
            //indique que ce champs est requis et devra contenir une valeur
            $name->setRequired(true);
            //un filtre va effectuer des traitements sur la valeur de l'élément concerné
            //StripTags a le même effet que la fonction PHP strip_tags(),
            //supprime les balises XHTML
            $name->addFilter('StripTags');
            //StringTrim a le même effet que la fonction PHP trim(),
            //supprime les espaces inutiles en début et fin de String
            $name->addFilter('StringTrim');
            //un validateur est une condition sur l'élément qui si elle n'est pas respectée,
            //annule le traitement
            //NotEmpty indique que le champs ne pourra pas être vide
            $name->addValidator('NotEmpty'); 
 
            //création d'un élément submit pour envoyer le formulaire
            $submit = new Zend_Form_Element_Submit('submit');
            //définit l'attribut "id" de l'élément submit
            $submit->setAttribs(array('id' => 'submitBt', 'class' => 'btn' ));
 
            //ajout des éléments au formulaire
            $this->addElements(array($id, $name, $submit));
        }
    }