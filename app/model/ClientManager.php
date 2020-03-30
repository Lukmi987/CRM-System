<?php

namespace App\Model;

class ClientManager   
{
    const TABLE = 'crm_client';// pouzijeme konstantu, aby byl nazev tabulky vzdy stejny, nemenny a nesel splest
   
    private $db; 

    /**
     *
     * @var \Nette\Database\Context 
     */
    public function __construct(\Nette\Database\Context $db) 
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
    public function findAll() // vraci vsechny zaznamy z tabulky
    {
        return $this->db->table(self::TABLE)
                            ->fetchAll();       
    
    }
    
    /**
     * 
     * @param array $data
     * @return IRow|int|bool
     */
    public function insert($data) // vlozi data do databaze
    {
        return $this->db->table(self::TABLE)
                            ->insert($data);
    }

    public function findAllToSelect($userId) //naplni me select ve formulari
    {   
        return $this->db->table(self::TABLE)
                           ->where('user_id', $userId)->fetchPairs('id', 'title');
    }

    public function findClientsByUserId($userId)// najde uzivatele, dle predaneho parametru z presenteru
    {
        return $this->db->table(self::TABLE)
                            ->where('user_id', $userId);
    }
}