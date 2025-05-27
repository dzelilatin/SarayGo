<?php
namespace Dzelitin\SarayGo\middleware;

class ErrorHandlingMiddleware {
    private $logger;

    public function __construct(LoggingMiddleware $logger) {
        $this->logger = $logger;
    }

    public function handleError($error) {
        // Log the error
        $this->logger->logError($error);

        // Determine error type and create appropriate response
        $response = $this->createErrorResponse($error);

        // Send response
        \Flight::json($response, $response['status']);
    }

    private function createErrorResponse($error) {
        $status = 500;
        $message = 'An unexpected error occurred';
        $details = null;

        // Handle different types of errors
        if ($error instanceof \PDOException) {
            $status = 500;
            $message = 'Database error occurred';
            $details = 'Please try again later';
        } elseif ($error instanceof \Exception) {
            switch ($error->getCode()) {
                case 400:
                    $status = 400;
                    $message = 'Bad Request';
                    $details = $error->getMessage();
                    break;
                case 401:
                    $status = 401;
                    $message = 'Unauthorized';
                    $details = 'Please log in to continue';
                    break;
                case 403:
                    $status = 403;
                    $message = 'Forbidden';
                    $details = 'You do not have permission to perform this action';
                    break;
                case 404:
                    $status = 404;
                    $message = 'Not Found';
                    $details = 'The requested resource was not found';
                    break;
                case 422:
                    $status = 422;
                    $message = 'Validation Error';
                    $details = $error->getMessage();
                    break;
                default:
                    $status = 500;
                    $message = 'Internal Server Error';
                    $details = 'An unexpected error occurred';
            }
        }

        // Create response array
        $response = [
            'error' => true,
            'status' => $status,
            'message' => $message
        ];

        // Add details if available
        if ($details) {
            $response['details'] = $details;
        }

        // Add validation errors if available
        if (isset($error->errors)) {
            $response['errors'] = $error->errors;
        }

        // Add stack trace in development mode
        if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
            $response['debug'] = [
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $error->getTraceAsString()
            ];
        }

        return $response;
    }

    public function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->handleError(new \ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }
} 