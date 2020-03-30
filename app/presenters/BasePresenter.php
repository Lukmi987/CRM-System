<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    public function beforeRender()
    {
        parent::beforeRender(); // nezapomente volat metodu predka, stejne jako u startup()
        $this->template->menuItems = array(
            'Domu' => 'Homepage:',
            'Klienti' => 'Clients:default',           
            'Projekty' => 'Projects:default',         
        );
    }


}

//Method beforeRender, as the name suggests, gets invoked before render<View>() and it can contain for example //setup of template, passing variables to template common for more views and so on.