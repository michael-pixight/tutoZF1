<?php

class Application_Form_Album extends Zend_Form
{

    public function init()
    {
        $this->setName('album');
        $id = new Zend_Form_Element_Hidden('id');
        $id->addFilter('Int');

        $artiste = new Zend_Form_Element_Text('artiste');
        $artiste->setLabel('Artiste')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');

        $titre = new Zend_Form_Element_Text('titre');
        $titre->setLabel('Titre')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');

        $envoyer = new Zend_Form_Element_Submit('envoyer');
        $envoyer->setAttrib('class', 'btn');

        $this->addElements(array($id, $artiste, $titre, $envoyer));
        
    }


}

