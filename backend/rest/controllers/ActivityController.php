<?php
require_once __DIR__ . '/../services/ActivityService.php';

class ActivityController {
    private $service;

    public function __construct() {
        $this->service = new ActivityService();
    }

    public function getByCategory() {
        try {
            if (!isset($_GET['category_id'])) {
                throw new Exception("Category ID is required");
            }
            $categoryId = $_GET['category_id'];
            $activities = $this->service->getActivitiesByCategory($categoryId);
            echo json_encode(["activities" => $activities]);
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
}
?>
