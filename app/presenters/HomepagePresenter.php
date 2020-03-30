<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form,
    App\Model\ClientManager;

class HomepagePresenter extends BasePresenter
{
    
    /**
     *
     * @var ClientManager
     * @inject
     */
    public $clientManager;
    
    protected function startup()
    {
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
        parent::startup();
    }

}

