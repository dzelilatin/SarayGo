<?php
namespace Dzelitin\SarayGo\middleware;

class ValidationMiddleware {
    private $rules = [];
    private $errors = [];

    public function __construct($rules = []) {
        $this->rules = $rules;
    }

    public function validateRequest() {
        $data = \Flight::request()->data->getData();
        $this->errors = [];

        foreach ($this->rules as $field => $rule) {
            // Required field validation
            if (isset($rule['required']) && $rule['required'] && !isset($data[$field])) {
                $this->errors[$field] = "Field is required";
                continue;
            }

            // Skip validation if field is not required and not present
            if (!isset($data[$field])) {
                continue;
            }

            // Type validation
            if (isset($rule['type'])) {
                switch ($rule['type']) {
                    case 'email':
                        if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            $this->errors[$field] = "Invalid email format";
                        }
                        break;
                    case 'integer':
                        if (!is_numeric($data[$field]) || !is_int((int)$data[$field])) {
                            $this->errors[$field] = "Must be an integer";
                        }
                        break;
                    case 'string':
                        if (!is_string($data[$field])) {
                            $this->errors[$field] = "Must be a string";
                        }
                        break;
                    case 'boolean':
                        if (!is_bool($data[$field]) && $data[$field] !== '0' && $data[$field] !== '1') {
                            $this->errors[$field] = "Must be a boolean";
                        }
                        break;
                }
            }

            // Length validation
            if (isset($rule['min_length']) && strlen($data[$field]) < $rule['min_length']) {
                $this->errors[$field] = "Minimum length is {$rule['min_length']} characters";
            }
            if (isset($rule['max_length']) && strlen($data[$field]) > $rule['max_length']) {
                $this->errors[$field] = "Maximum length is {$rule['max_length']} characters";
            }

            // Pattern validation
            if (isset($rule['pattern']) && !preg_match($rule['pattern'], $data[$field])) {
                $this->errors[$field] = "Invalid format";
            }

            // Custom validation
            if (isset($rule['custom']) && is_callable($rule['custom'])) {
                $result = $rule['custom']($data[$field], $data);
                if ($result !== true) {
                    $this->errors[$field] = $result;
                }
            }
        }

        if (!empty($this->errors)) {
            \Flight::halt(400, json_encode([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $this->errors
            ]));
        }

        return true;
    }

    public function sanitizeData($data) {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove HTML tags and encode special characters
                $sanitized[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }
        return $sanitized;
    }
} 