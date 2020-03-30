<?php

namespace App\Presenters;

use Nette,
	Nette\Application\UI\Form,
	App\Model\UserManager;




/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{
	    /**
     *
     * @var UserManager 
     * @inject
     */
    public $userManager;

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('login', 'Login:')
			->setRequired('Vlozte vase jmeno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Vlozte vase heslo.');

		

		$form->addSubmit('send', 'Přihlášení');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = array($this, 'signInFormSucceeded');
		return $form;
	}


	public function signInFormSucceeded($form, $values)
	{
        $values = $form->getValues();
			
		try {
			$this->getUser()->login($values->login, $values->password);
			$this->redirect('Homepage:');

		} catch (Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

	 protected function createComponentRegistrationForm()
    {
        $form = new Nette\Application\UI\Form;
        $form->addText('name', 'Jméno')
                ->setRequired('Jméno je povinné.');
        $form->addText('surname', 'Příjmení')
                ->setRequired('Příjmení je povinné.');
        $form->addText('login', 'Login')
                ->setRequired('Login je povinný.');
        $form->addPassword('password', 'Helso')
                ->setRequired('Heslo je povinné.');
        $form->addSubmit('submit', 'Registrace');
        $form->onSuccess[] = $this->registrationFormSuccessed;
        
        return $form;
   }
    
    public function registrationFormSuccessed(Form $form)
    {
        $values = $form->getValues();
        $values->password = \Nette\Security\Passwords::hash($values->password);
        $this->userManager->add($values);// volame metodu add(), v userManagerovi, ktera ulozi  noveho uzivatele
        
        $this->flashMessage('Uživatel uložen, nynní je možno se přihlásit.', 'success');
        $this->redirect('Sign:in'); // po uspesne registraci presmerujeme uzivatele na prihlasovaci formular
    }
}

