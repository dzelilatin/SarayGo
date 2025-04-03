<?php
require_once __DIR__ . '/BaseDao.php';

class BlogDao extends BaseDao {

    public function __construct() {
        parent::__construct('blogs'); // Set the table to 'blogs'
    }

    // Create a new blog post
    public function createBlog($user_id, $title, $content) {
        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s') // Set the current timestamp
        ];
        return $this->insert($data); // Use BaseDao's insert method
    }

    // Get a blog post by ID
    public function getBlogById($id) {
        return $this->getById($id); // Use BaseDao's getById method
    }

    // Get all blog posts by a specific user
    public function getBlogsByUserId($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM blogs WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all blog posts (with optional pagination)
    public function getAllBlogs($limit = null, $offset = null) {
        $sql = "SELECT * FROM blogs ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        if ($offset) {
            $sql .= " OFFSET :offset";
        }

        $stmt = $this->connection->prepare($sql);
        
        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset) {
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Update a blog post
    public function updateBlog($id, $title, $content) {
        $data = [
            'title' => $title,
            'content' => $content
        ];
        return $this->update($id, $data); // Use BaseDao's update method
    }

    // Delete a blog post by ID
    public function deleteBlog($id) {
        return $this->delete($id); // Use BaseDao's delete method
    }
}
?>
