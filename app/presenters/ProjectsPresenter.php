<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form,
    App\Model\ProjectManager,
    App\Model\TaskManager,
    App\Model\ClientManager;

class ProjectsPresenter extends BasePresenter
{
    private $detail;
    
    private $list;


  

    /**
     *
     * @var ClientManager
     * @inject
     */
    public $clientManager;

    /**
     *
     * @var TaskManager
     * @inject
     */
    public $taskManager;

    
       /**
     *
     * @var ProjectManager
     * @inject
     */
    public $projectManager;

    protected function startup()
    {
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
        parent::startup();
    }
    
    public function actionDefault()
    {  
        $userId = $this->getUser()->getId();
        $projects = $this->projectManager->findClientsByUserId($userId);       

        $this->list = array(); //deklarace pole 
        foreach($projects as $p) { //prochazime pole cyklem foreach  
            $this->list[$p->id] = array( //konecny jeden zaznam v poli
                'detail' => $p,
                'suma' => $this->taskManager->sumprice($p->id));
        }
    }

    public function renderDefault()
    {
        $this->template->userList = $this->list;
    }
    
    public function actionDetail($id) // stejné jako public function actionEdit($id)
    {       
        $this->detail = $this->projectManager->findById($id);
          
        if(!$this->detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

        $this['projectForm']->setDefaults($this->detail);
    }

    

    protected function createComponentProjectForm()
    {   
        $form = new Form;
        $form->addHidden('id');
        $form->addText('title' , 'Nazev projektu')
                ->setRequired('Vyplnte nazev.');
        $form->addText('note' , 'Poznamka')
                ->setRequired('Vyplnte poznamku.'); 
                if(!$this->detail) {          
                $userId = $this->getUser()->getId();
                $clients = $this->clientManager->findAllToSelect($userId);
                $form->addSelect('client_id', 'Vyberte klienta', $clients);}                
        $form->addSubmit('submit', 'Ulozit')
                ->getControlPrototype()
                    ->addAttributes(array('class' => 'btn btn-success'));
        $form->onSuccess[] = $this->projectFormSuccessed;
               
        return $form;

    }
   
    public function projectFormSuccessed(Form $form)
    {
        $values = $form->getValues();

        if ($values->client_id == 0){
        $this->flashMessage('Prvně musíte uložit nového klienta') ;      
        
       } else {    
        if($values->id) {
            $detail = $this->projectManager->findById($values->id);
        
            if(!$this->detail) {
                $this->flashMessage('Pozadovany zaznam s id = "'. $values->id .'" nebyl nalezen.', 'error');
                $this->redirect('default');
            }

            $detail->update($values);      
        } else {
            $this->projectManager->insert($values);      
        }
    
        $this->flashMessage('Data byla uspesne ulozena', 'success');
        }     
        $this->redirect('default');
    }



    public function actionDelete($id)
    {   
        $detail = $this->projectManager->findById($id);
        $seznam = $this->taskManager->findAll();
        $seznam = $this->taskManager->findTasksByProjectId($id); 

        
        if(!$detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

$seznam = $this->taskManager->findAll(); // ziskani vsech uloh
$seznam = $this->taskManager->findTasksByProjectId($id); //omezeni uloh jen pro projekt
                                                        // ktery chceme smazat
if( count($seznam) > 0 ) { //pomoci funkce count spocitame ulohy v poli
  $this->flashMessage('Nemuzete vymazat projekt má pod sebou další úkoly ' );
  $this->redirect('default');
     } else { // pokud uloh neni vice jak nula, muzeme projekt smazat
            
    $detail->delete();
    $this->flashMessage('Zaznam byl uspesne vymazan', 'success');    
    $this->redirect('default'); }
        
    } 
}
