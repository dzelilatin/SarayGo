<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ContactDao.php';

class ContactService extends BaseService {
    private $minNameLength = 2;
    private $maxNameLength = 100;
    private $minMessageLength = 10;
    private $maxMessageLength = 1000;
    private $validStatuses = ['unread', 'read', 'archived'];

    public function __construct() {
        $dao = new ContactDao();
        parent::__construct($dao);
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
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateContactData($data);
        return parent::update($id, $data);
    }

    private function validateContactData($data) {
        // Required fields validation
        $requiredFields = ['name', 'email', 'message'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Name validation
        if (strlen($data['name']) < $this->minNameLength || 
            strlen($data['name']) > $this->maxNameLength) {
            throw new Exception("Name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Message validation
        if (strlen($data['message']) < $this->minMessageLength || 
            strlen($data['message']) > $this->maxMessageLength) {
            throw new Exception("Message must be between {$this->minMessageLength} and {$this->maxMessageLength} characters");
        }

        // Optional status validation
        if (isset($data['status']) && !in_array($data['status'], $this->validStatuses)) {
            throw new Exception("Invalid status. Must be one of: " . implode(', ', $this->validStatuses));
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
