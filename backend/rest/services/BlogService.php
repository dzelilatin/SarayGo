<?php
namespace Dzelitin\SarayGo\services;
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BlogDao.php';
use Dzelitin\SarayGo\dao\BlogDao;

class BlogService extends BaseService {
    private $minTitleLength = 5;
    private $maxTitleLength = 255;
    private $minContentLength = 10;
    private $maxContentLength = 10000;
    private $validStatuses = ['draft', 'published', 'archived'];

    public function __construct() {
        parent::__construct(new BlogDao());
    }

    public function getByAuthor($authorId) {
        if (!is_numeric($authorId)) {
            throw new Exception("Invalid author ID");
        }
        return $this->dao->getByAuthor($authorId);
    }

    public function getByCategory($categoryId) {
        if (!is_numeric($categoryId)) {
            throw new Exception("Invalid category ID");
        }
        return $this->dao->getByCategory($categoryId);
    }

    public function getPopularBlogs($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getPopularBlogs($limit);
    }

    public function searchBlogs($query, $categoryId = null, $authorId = null) {
        if (empty($query)) {
            throw new Exception("Search query cannot be empty");
        }
        return $this->dao->searchBlogs($query, $categoryId, $authorId);
    }

    public function create($data) {
        $this->validateBlogData($data);
        return $this->dao->createBlog(
            $data['user_id'],
            $data['title'],
            $data['content']
        );
    }

    public function update($id, $data) {
        $this->validateBlogData($data, false);
        return $this->dao->updateBlog(
            $id,
            $data['title'],
            $data['content']
        );
    }

    public function getByUserId($userId) {
        if (!is_numeric($userId)) {
            throw new \Exception("Invalid user ID");
        }
        return $this->dao->getBlogsByUserId($userId);
    }

    public function getAllBlogs($limit = null, $offset = null) {
        if ($limit !== null && (!is_numeric($limit) || $limit < 1)) {
            throw new \Exception("Invalid limit value");
        }
        if ($offset !== null && (!is_numeric($offset) || $offset < 0)) {
            throw new \Exception("Invalid offset value");
        }
        return $this->dao->getAllBlogs($limit, $offset);
    }

    private function validateBlogData($data, $isNew = true) {
        // Required fields validation
        $requiredFields = ['title', 'content'];
        if ($isNew) {
            $requiredFields[] = 'user_id';
        }
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                throw new \Exception("Missing required field: $field");
            }
        }

        // Title validation
        if (strlen($data['title']) < $this->minTitleLength || 
            strlen($data['title']) > $this->maxTitleLength) {
            throw new \Exception("Title must be between {$this->minTitleLength} and {$this->maxTitleLength} characters");
        }

        // Content validation
        if (strlen($data['content']) < $this->minContentLength || 
            strlen($data['content']) > $this->maxContentLength) {
            throw new \Exception("Content must be between {$this->minContentLength} and {$this->maxContentLength} characters");
        }

        // User ID validation for new blogs
        if ($isNew && !is_numeric($data['user_id'])) {
            throw new \Exception("Invalid user ID");
        }
    }

    public function getRecentBlogs($limit = 10) {
        if (!is_numeric($limit) || $limit < 1) {
            throw new Exception("Invalid limit value");
        }
        return $this->dao->getRecentBlogs($limit);
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
