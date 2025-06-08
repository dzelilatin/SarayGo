<?php

require_once __DIR__ . "/../Dao/BaseDao.php";

class BaseService
{

    protected $dao;


    public function __construct($dao)
    {

        $this->dao = $dao;
    }


    public function getById($id)
    {
        try {
            return $this->dao->getById($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }



    public function getAll()
    {
        try {
            return $this->dao->getAll();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {
        try {
            return $this->dao->delete($id);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function update($id, $data)
    {
        try {
            $this->dao->update($id, $data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            return $this->dao->create($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
