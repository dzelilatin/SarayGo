<?php
namespace Dzelitin\SarayGo\services;
use Dzelitin\SarayGo\dao\BaseDao;

class BaseService {
    protected $dao;
    
    public function __construct($dao) {
        $this->dao = $dao;
    }
    
    public function getAll() {
        return $this->dao->getAll();
    }
    
    public function getById($id) {
        if (!is_numeric($id)) {
            throw new \Exception("Invalid ID format");
        }
        return $this->dao->getById($id);
    }
    
    public function create($data) {
        return $this->dao->insert($data);
    }
    
    public function update($id, $data) {
        if (!is_numeric($id)) {
            throw new \Exception("Invalid ID format");
        }
        return $this->dao->update($id, $data);
    }
    
    public function delete($id) {
        if (!is_numeric($id)) {
            throw new \Exception("Invalid ID format");
        }
        return $this->dao->delete($id);
    }
}
?>
