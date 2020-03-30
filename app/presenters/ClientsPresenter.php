<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form,
    App\Model\ProjectManager,
    App\Model\ClientManager;

class ClientsPresenter extends BasePresenter
{
    private $detail; // deklarovani promene $detail
   
    private $list; // deklarovani promene $list
  
      /**
     *
     * @var ProjectManager 
     * @inject
     */
    public $projectManager;

     /**
     *
     * @var ClientManager //propojujem model s presenterem
     * @inject
     */
    public $clientManager;

    protected function startup() // tato metoda presmeruje uzivatele na prihlasani pokud není zalogovany,Method startup() gets invoked immediately after the presenter is created. It initializes variables or checks user privileges.
    {
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
        parent::startup();//If you write your own startup() method, don't forget to call its parent parent::startup().
    }
    
    public function actionDefault()
    {
        $this->list = $this->clientManager->findAll();//volame funkci findAll() z modelu a $this->list ted obsahuje vsechny data   
        $userId = $this->getUser()->getId();// zjistime id aktualne prihlaseneho uzivatele 
        $this->list = $this->clientManager->findClientsByUserId($userId);//predavame id uzivatele jako parametr funkci findCli...       
    }

    public function renderDefault()// Posila nam data z databaze do sablony, ktera je pak vykresli jako html kod
    {
        $this->template->userList = $this->list;//$this->list obsahuje vyfiltrovane klienty, pro prihlaseneho uzivatele
           
    }
    
    public function actionDetail($id) // ziska nam detail z databaze, kdyz chceme upravit zaznam
    {
        $this->detail = $this->clientManager->findById($id);


        if(!$this->detail) { //kontrola zda pozadovany zaznam existuje
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

       $this['clientForm']->setDefaults($this->detail);// vypisuje detail konkretniho zaznamu do policek formulare na editaci 
    }

    protected function createComponentClientForm() // vytvoreni formulare pro ulozeni a upravy klientu
    {
        $form = new Form; //vytvori novou instanci komponenty Form
        $form->addHidden('id');
        $form->addText('title' , 'Nazev klienta') // vykresli se jako <input type=text name=title> <label>Naz. klien.</label>
                ->setRequired('Vyplnte nazev.'); // policko je povinne vyplnit
        $form->addText('note' , 'Poznamka')
                ->setRequired('Vyplnte poznamku.');
        $form->addSubmit('submit', 'Ulozit') // vykresli se jako <input type=submit>
                ->getControlPrototype()
                    ->addAttributes(array('class' => 'btn btn-success'));
        $form->onSuccess[] = $this->clientFormSuccessed; // po uspesnem odeslani formulare zavolej metodu clientFormSuccessed           
        return $form;
    }
                    //vola se po uspesnem odeslani formulare
    public function clientFormSuccessed(Form $form) //argument je instance formulare, ktery byl odeslan - vytvoren tovarnickou
    {
        $values = $form->getValues(); // ziskani odeslanych hodnot
        
        $values->user_id = $this->getUser()->getId();  //omezeni toho, aby se data ulozili jen pro aktual. prihlasen. uzivate.
        
   
        if($values->id) {
            $detail = $this->clientManager->findById($values->id); // najde pozadovany zaznam, ktery chcem upravit
        
            if(!$this->detail) {  // pokud pozadovany zaznam nebyl nalezen, tak na to upozornime uzivatele
                $this->flashMessage('Pozadovany zaznam s id = "'. $values->id .'" nebyl nalezen.', 'error');
                $this->redirect('default');
            }

            $detail->update($values);   // aktualizuje existujici zaznam do tabulky  
        } else {
            $this->clientManager->insert($values); // ulozeni noveho zaznamu do tabulky     
        }
        $this->flashMessage('Data byla uspesne ulozena', 'success');     
        $this->redirect('default');
    }

    public function actionDelete($id) // metoda, ktera smaze zaznam z tabulky
    {
        $detail = $this->clientManager->findById($id); // vyhladani konkretniho zaznamu v tabulce
        $select = $this->projectManager->findAll();
        $select = $this->projectManager->findProjectByClientId($id);
        
        if(!$detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }
        if( count($select) > 0 ) {
         $this->flashMessage('Nemuzete vymazat klienta číslo '. $id .' má pod sebou projekty' );
         $this->redirect('default');
        } else {
        $detail->delete(); //smazani konkretniho zaznamu
        $this->flashMessage('Zaznam byl uspesne vymazan', 'success');    
        $this->redirect('default');}
    }
}
