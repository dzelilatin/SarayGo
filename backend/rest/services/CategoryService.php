<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
use Dzelitin\SarayGo\dao\CategoryDao;

class CategoryService extends BaseService {
    private $minNameLength = 2;
    private $maxNameLength = 100;
    private $minDescriptionLength = 10;
    private $maxDescriptionLength = 500;
    private $validTypes = ['activity', 'blog', 'recommendation'];

    public function __construct() {
        parent::__construct(new CategoryDao());
    }

    public function get_with_blog_count() {
        return $this->dao->get_with_blog_count();
    }

    public function getByType($type) {
        if (empty($type)) {
            throw new Exception("Category type cannot be empty");
        }
        if (!in_array(strtolower($type), $this->validTypes)) {
            throw new Exception("Invalid category type. Must be one of: " . implode(', ', $this->validTypes));
        }
        return $this->dao->getByType($type);
    }

    public function create($data) {
        $this->validateCategoryData($data);
        return $this->dao->createCategory($data['category_name']);
    }

    public function update($id, $data) {
        $this->validateCategoryData($data);
        return $this->dao->updateCategory($id, $data['category_name']);
    }

    public function getAllCategories() {
        return $this->dao->getAllCategories();
    }

    private function validateCategoryData($data) {
        // Required fields validation
        if (!isset($data['category_name']) || empty($data['category_name'])) {
            throw new \Exception("Category name is required");
        }

        // Name validation
        if (strlen($data['category_name']) < $this->minNameLength || 
            strlen($data['category_name']) > $this->maxNameLength) {
            throw new \Exception("Category name must be between {$this->minNameLength} and {$this->maxNameLength} characters");
        }

        // Type validation
        if (!in_array(strtolower($data['type']), $this->validTypes)) {
            throw new Exception("Invalid category type. Must be one of: " . implode(', ', $this->validTypes));
        }

        // Description validation
        if (strlen($data['description']) < $this->minDescriptionLength || 
            strlen($data['description']) > $this->maxDescriptionLength) {
            throw new Exception("Description must be between {$this->minDescriptionLength} and {$this->maxDescriptionLength} characters");
        }

        // Check for duplicate category name
        if ($this->dao->getByName($data['category_name'])) {
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
