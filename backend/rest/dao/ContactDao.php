<?php
namespace Dzelitin\SarayGo\Dao;
require_once __DIR__ . '/BaseDao.php';
use Dzelitin\SarayGo\Dao\BaseDao;

class ContactDao extends BaseDao {

    public function __construct() {
        parent::__construct('contact'); // Set the table to 'contact'
    }

    // Create a new contact message
    public function createContactMessage($user_id, $name, $email, $message) {
        $data = [
            'user_id' => $user_id,
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s') // Set the current timestamp
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a contact message by ID
    public function getContactMessageById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all contact messages
    public function getAllContactMessages() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Get all contact messages for a specific user
    public function getContactMessagesByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM contact WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update a contact message by ID
    public function updateContactMessage($id, $name, $email, $message) {
        $data = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a contact message by ID
    public function deleteContactMessage($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
