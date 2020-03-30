<?php

namespace App\Presenters;// jednou napisu a nemusim psat celou cestu

use Nette,
    Nette\Application\UI\Form,
    App\Model\ProjectManager,
    App\Model\TaskcommentManager,
    App\Model\TaskManager;

class TasksPresenter extends BasePresenter
{
    private $detail;
    
    private $list;
       
    private $Project;
    /**
     *
     * @var ProjectManager
     * @inject
     */
    public $projectManager;

     /**
     *
     * @var TaskcommentManager
     * @inject
     */
    public $taskcommentManager;
    

       /**
     *
     * @var TaskManager
     * @inject
     */
    public $taskManager;

    protected function startup()
    {
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
        parent::startup();
    }


    public function actionDefault($id) // ziskani ukoly k projektu
    {   $this->Project = $id;
        $this->list = $this->taskManager->findAll();  
        $this->list = $this->taskManager->findTasksByProjectId($id); 
        $sumadleprojektu = $this->taskManager->sumprice($id);
       $Allprojekty  = $this->projectManager->findAll();
   }
        public function renderDefault()
        {
        $this->template->userList = $this->list;
         $this->template->IdProjektu = $this->Project;
       $this->template->userList = $this->list;     
        }
    
    public function actionDetail($id) // stejné jako public function actionEdit($id)
    {
        $this->detail = $this->taskManager->findById($id);

        if(!$this->detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

        $this['taskForm']->setDefaults($this->detail);
    }

    public function actionAdd($id) 
    {
        $this['taskForm']->setDefaults(array('project_id' => $id));    
       
    }

    protected function createComponentTaskForm()
    {
        $form = new Form;// toto mi vytvorí ten objekt, ale musim pouzit use
        $form->addhidden('id');
        $form->addhidden('project_id');                        
        $form->addText('title' , 'Nazev ukolu')
                ->setRequired('Vyplnte nazev');
        $form->addText('status' , 'Status')
                ->setRequired('Vyplnte Status');
        $form->addText('price' , 'Cena za úkol')
                ->setRequired('Vyplňte cenu');                                            
        $form->addSubmit('submit', 'Ulozit')
                ->getControlPrototype()
                    ->addAttributes(array('class' => 'btn btn-success'));
        $form->onSuccess[] = $this->taskFormSuccessed;
               
        return $form;
    }
    
    public function taskFormSuccessed(Form $form)
    {
        $values = $form->getValues();       
        if($values->id) {
            $detail = $this->taskManager->findById($values->id);
        
            if(!$this->detail) {
                $this->flashMessage('Pozadovany zaznam s id = "'. $values->id .'" nebyl nalezen.', 'error');
                $this->redirect('default');
            }

            $detail->update($values);      
        } else {
          
            $this->taskManager->insert($values);      
        }
        $this->flashMessage('Data byla uspesne ulozena', 'success'); 

        $this->redirect('Tasks:default', array('id' => $values->project_id));
    }

    public function actionDelete($id)
    {   $idzaznamu = $this->taskManager->findById($id);
        $detail = $this->taskManager->findById($id);
        $select = $this->taskcommentManager->findAll();
        $select = $this->taskcommentManager->findCommentByTaskId($id);
        
        if(!$detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('Tasks:default', array('id' => $idzaznamu->project_id));
        }
        if( count($select) >0 ){
            $this->flashMessage('Nemuzete vymazat úkol cislo "'. $id .'" má pod sebou komentáře ' );
            $this->redirect('Tasks:default', array('id' => $idzaznamu->project_id));
            } else {

        $detail->delete();
        $this->flashMessage('Zaznam byl uspesne vymazan', 'success');    
        $this->redirect('Tasks:default', array('id' => $idzaznamu->project_id));}
    }
}
