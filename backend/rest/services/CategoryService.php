<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService extends BaseService {
    private $minNameLength = 3;
    private $maxNameLength = 50;
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;

    public function __construct() {
        $dao = new CategoryDao();
        parent::__construct($dao);
    }

    public function get_with_blog_count() {
        return $this->dao->get_with_blog_count();
    }

    public function create($data) {
        $this->validateCategoryData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateCategoryData($data);
        return parent::update($id, $data);
    }

    private function validateCategoryData($data) {
        // Required fields validation
        $requiredFields = ['name', 'description'];
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

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Check for duplicate category name
        if ($this->dao->getByName($data['name'])) {
            throw new Exception("Category name already exists");
        }
    }

    public function getByName($name) {
        if (empty($name)) {
            throw new Exception("Category name cannot be empty");
        }
        return $this->dao->getByName($name);
    }

    public function getCategoriesWithActivityCount() {
        return $this->dao->getCategoriesWithActivityCount();
    }

    public function getPopularCategories($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularCategories($limit);
    }

    public function searchCategories($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchCategories($query);
    }
}
?>
