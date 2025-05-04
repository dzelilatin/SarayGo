<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ContactDao.php';
use Dzelitin\SarayGo\dao\ContactDao;

class ContactService extends BaseService {
    private $minNameLength = 2;
    private $maxNameLength = 100;
    private $minMessageLength = 10;
    private $maxMessageLength = 1000;
    private $validStatuses = ['unread', 'read', 'archived'];

    public function __construct() {
        parent::__construct(new ContactDao());
    }

    public function get_by_status($status) {
        if (!in_array($status, $this->validStatuses)) {
            throw new Exception("Invalid status. Must be one of: " . implode(', ', $this->validStatuses));
        }
        return $this->dao->get_by_status($status);
    }

    public function mark_as_read($id) {
        if (!is_numeric($id)) {
            throw new Exception("Invalid contact ID");
        }
        return $this->dao->update_status($id, 'read');
    }

    public function mark_as_unread($id) {
        if (!is_numeric($id)) {
            throw new Exception("Invalid contact ID");
        }
        return $this->dao->update_status($id, 'unread');
    }

    public function create($data) {
        $this->validateContactData($data);
        return $this->dao->createContactMessage(
            $data['user_id'] ?? null,
            $data['name'],
            $data['email'],
            $data['message']
        );
    }

    public function update($id, $data) {
        $this->validateContactData($data, false);
        return $this->dao->updateContactMessage(
            $id,
            $data['name'],
            $data['email'],
            $data['message']
        );
    }

    public function getByUserId($userId) {
        if (!is_numeric($userId)) {
            throw new \Exception("Invalid user ID");
        }
        return $this->dao->getContactMessagesByUserId($userId);
    }

    public function getAllMessages() {
        return $this->dao->getAllContactMessages();
    }

    private function validateContactData($data, $allowGuest = true) {
        // Required fields validation
        $requiredFields = ['name', 'email', 'message'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // Name validation
        if (strlen($data['name']) < $this->minNameLength || 
            strlen($data['name']) > $this->maxNameLength) {
            throw new \Exception("Name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }

        // Message validation
        if (strlen($data['message']) < $this->minMessageLength || 
            strlen($data['message']) > $this->maxMessageLength) {
            throw new \Exception("Message must be between {$this->minMessageLength} and {$this->maxMessageLength} characters");
        }

        // User ID validation (if provided)
        if (isset($data['user_id']) && !is_numeric($data['user_id'])) {
            throw new \Exception("Invalid user ID");
        }

        // If not allowing guest messages and no user_id provided
        if (!$allowGuest && !isset($data['user_id'])) {
            throw new \Exception("User ID is required for this operation");
        }
    }

    public function getRecentContacts($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getRecentContacts($limit);
    }

    public function getUnreadCount() {
        return $this->dao->getUnreadCount();
    }

    public function archive($id) {
        if (!is_numeric($id)) {
            throw new Exception("Invalid contact ID");
        }
        return $this->dao->update_status($id, 'archived');
    }

    public function searchContacts($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchContacts($query);
    }
}
?>
