<?php

namespace App\Model;

class ProjectManager
{
    const TABLE = 'crm_project';  
   
    private $db;

    /**
     *
     * @var \Nette\Database\Context 
     */
    public function __construct(\Nette\Database\Context $db) //
    {
        $this->db = $db;
    }
    
    /**
     * 
     * @param int $id
     * @return \Nette\Database\Table\ActiveRow         
     */
    public function findById($id) // vraci vyfiltrovane zaznamy na zaklade id
    {
        return $this->db->table(self::TABLE)
                            ->get($id);
    }
    
    /**
     * 
     * @return Nette\Database\Table\Selection
     */
    public function findAll() // vrací vsechny zaznamy z databaze
    {
        return $this->db->table(self::TABLE)
                            ->fetchAll();  //  bude obsahovat vsechny data z tabulky        
    }
    
    /**
     * 
     * @param array $data
     * @return IRow|int|bool
     */
    public function insert($data)
    {
        return $this->db->table(self::TABLE)
                            ->insert($data);
    }

    public function findAllToSelect($userId) 
    {   
        return $this->db->table(self::TABLE)
                           ->where('client.user_id', $userId)->fetchPairs('id','title');
    }

    public function findClientsByUserId($userId) 
         {
            return $this->db->table(self::TABLE)
                            ->where('client.user_id', $userId);
          }

    public function findProjectByClientId($id)
    {
        return $this->db->table(self::TABLE)
                        ->where('client_id', $id);
    }
}

