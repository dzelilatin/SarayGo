<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/BlogDao.php';

class BlogService extends BaseService {
    private $minTitleLength = 5;
    private $maxTitleLength = 100;
    private $minContentLength = 50;
    private $maxContentLength = 5000;

    public function __construct() {
        $dao = new BlogDao();
        parent::__construct($dao);
    }

    public function get_by_category($category_id) {
        if (!is_numeric($category_id)) {
            throw new Exception("Invalid category ID");
        }
        return $this->dao->get_by_category($category_id);
    }

    public function get_by_user($user_id) {
        if (!is_numeric($user_id)) {
            throw new Exception("Invalid user ID");
        }
        return $this->dao->get_by_user($user_id);
    }

    public function search($query) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->search($query);
    }

    public function create($data) {
        $this->validateBlogData($data);
        return parent::create($data);
    }

    public function update($id, $data) {
        $this->validateBlogData($data);
        return parent::update($id, $data);
    }

    private function validateBlogData($data) {
        // Required fields validation
        $requiredFields = ['title', 'content', 'user_id', 'category_id'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // Title validation
        if (strlen($data['title']) < $this->minTitleLength || 
            strlen($data['title']) > $this->maxTitleLength) {
            throw new Exception("Title must be between {$this->minTitleLength} and {$this->maxTitleLength} characters");
        }

        // Content validation
        if (strlen($data['content']) < $this->minContentLength || 
            strlen($data['content']) > $this->maxContentLength) {
            throw new Exception("Content must be between {$this->minContentLength} and {$this->maxContentLength} characters");
        }

        // User ID validation
        if (!is_numeric($data['user_id'])) {
            throw new Exception("Invalid user ID");
        }

        // Category ID validation
        if (!is_numeric($data['category_id'])) {
            throw new Exception("Invalid category ID");
        }
    }

    public function getRecentBlogs($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getRecentBlogs($limit);
    }

    public function getPopularBlogs($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularBlogs($limit);
    }

    public function getBlogsByTags($tags, $limit = 10) {
        if (empty($tags)) {
            throw new Exception("Tags cannot be empty");
        }
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getBlogsByTags($tags, $limit);
    }
}
?>
