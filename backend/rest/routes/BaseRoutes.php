<?php
namespace Dzelitin\SarayGo\routes;

class BaseRoutes {
    protected $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function get_routes() {
        // To be implemented by child classes
    }
} 