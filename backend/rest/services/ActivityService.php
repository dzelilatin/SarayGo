<?php
require_once __DIR__ . '/../dao/ActivityDao.php';
require_once __DIR__ . '/BaseService.php';

class ActivityService extends BaseService {
    public function __construct() {
        parent::__construct(new ActivityDao());
    }

    public function getActivitiesByCategory($categoryId) {
        if (!is_numeric($categoryId)) {
            throw new Exception("Invalid category ID");
        }
        return $this->dao->getByCategory($categoryId);
    }
}
?>
