<?php
namespace Dzelitin\SarayGo\middleware;

class LoggingMiddleware {
    private $logFile;
    private $errorLogFile;
    private $activityLogFile;

    public function __construct() {
        $this->logFile = __DIR__ . '/../../logs/requests.log';
        $this->errorLogFile = __DIR__ . '/../../logs/errors.log';
        $this->activityLogFile = __DIR__ . '/../../logs/activity.log';
        
        // Create log directory if it doesn't exist
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    public function logRequest() {
        $request = \Flight::request();
        $user = \Flight::get('user');
        
        $log = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $request->method,
            'url' => $request->url,
            'ip' => $request->ip,
            'user_agent' => $request->user_agent,
            'user_id' => $user ? $user->id : null,
            'user_role' => $user ? $user->role : null
        ];
        
        $this->writeLog($this->logFile, $log);
    }

    public function logError($error, $context = []) {
        $log = [
            'timestamp' => date('Y-m-d H:i:s'),
            'error' => $error->getMessage(),
            'code' => $error->getCode(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTraceAsString(),
            'context' => $context
        ];
        
        $this->writeLog($this->errorLogFile, $log);
    }

    public function logActivity($action, $details = []) {
        $user = \Flight::get('user');
        
        $log = [
            'timestamp' => date('Y-m-d H:i:s'),
            'action' => $action,
            'user_id' => $user ? $user->id : null,
            'user_role' => $user ? $user->role : null,
            'details' => $details
        ];
        
        $this->writeLog($this->activityLogFile, $log);
    }

    private function writeLog($file, $data) {
        $logEntry = json_encode($data) . "\n";
        file_put_contents($file, $logEntry, FILE_APPEND);
    }

    public function getRecentLogs($type = 'request', $limit = 100) {
        $file = $this->getLogFileByType($type);
        if (!file_exists($file)) {
            return [];
        }

        $logs = file($file);
        $logs = array_reverse($logs);
        $logs = array_slice($logs, 0, $limit);
        
        return array_map(function($log) {
            return json_decode($log, true);
        }, $logs);
    }

    private function getLogFileByType($type) {
        switch ($type) {
            case 'error':
                return $this->errorLogFile;
            case 'activity':
                return $this->activityLogFile;
            default:
                return $this->logFile;
        }
    }
} 