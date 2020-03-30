<?php

namespace App\Presenters;

use Nette,
    Nette\Application\UI\Form,
    App\Model\TaskManager,
    App\Model\TaskcommentManager;

class TaskcommentsPresenter extends BasePresenter
{
    private $detail;
    
    private $list;

    private $task;

    /**
     *
     * @var TaskManager
     * @inject
     */
    public $taskManager;
    

    /**
     *
     * @var TaskcommentManager
     * @inject
     */
    public $taskcommentManager;

    protected function startup()
    {
        if(!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
        
        parent::startup();
    }
    
    public function actionDefault($id)
    {   
        $this->task = $id;
        $this->list = $this->taskcommentManager->findAll();
        $this->list = $this->taskcommentManager->findCommentByTaskId($id);
    }

        public function renderDefault($project)
    {   $this->template->projectid = $project;
        $this->template->taskId = $this->task;
        $this->template->userList = $this->list;
    }
    
    public function actionDetail($id) 
    {
        $this->detail = $this->taskcommentManager->findById($id);

        if(!$this->detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

        $this['taskcommentForm']->setDefaults($this->detail);
    }


    public function actionAdd($id)
    {
        $this['taskcommentForm']->setDefaults(array('task_id' => $id));
    }

    protected function createComponentTaskcommentForm()
    {
        $form = new Form;
        $form->addHidden('id');
        $form->addHidden('task_id');
        $form->addText('text' , 'Vas komentar')
                ->setRequired('Vyplnte komentar');             
        $form->addSubmit('submit', 'Ulozit')
                ->getControlPrototype()
                    ->addAttributes(array('class' => 'btn btn-success'));
        $form->onSuccess[] = $this->taskcommentFormSuccessed;
               
        return $form;
    }
    
    public function taskcommentFormSuccessed(Form $form)
    {
        $values = $form->getValues();
        
        if($values->id) {
            $detail = $this->taskcommentManager->findById($values->id);
        
            if(!$this->detail) {
                $this->flashMessage('Pozadovany zaznam s id = "'. $values->id .'" nebyl nalezen.', 'error');
                $this->redirect('default');
            }

            $detail->update($values);      
        } else {
            $this->taskcommentManager->insert($values);      
        }
        $this->flashMessage('Data byla uspesne ulozena', 'success');     
        $this->redirect('Taskcomments:default', array('id' => $values->task_id));
    }

    public function actionDelete($id)
    {   
        $Idzaznamu = $this->taskcommentManager->findById($id);
        $detail = $this->taskcommentManager->findById($id);
        
        if(!$detail) {
            $this->flashMessage('Pozadovany zaznam s id = "'. $id .'" nebyl nalezen.', 'error');
            $this->redirect('default');
        }

        $detail->delete();
        $this->flashMessage('Zaznam byl uspesne vymazan', 'success');    
        $this->redirect('Taskcomments:default', array('id' => $Idzaznamu->task_id));
    }
}
