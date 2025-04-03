<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao {

    public function __construct() {
        parent::__construct('categories'); // Set the table to 'categories'
    }

    // Create a new category
    public function createCategory($category_name) {
        $data = [
            'category_name' => $category_name
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a category by ID
    public function getCategoryById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all categories
    public function getAllCategories() {
        return $this->getAll(); // Use BaseDao's getAll method
    }

    // Update category information
    public function updateCategory($id, $category_name) {
        $data = [
            'category_name' => $category_name
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a category by ID
    public function deleteCategory($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
