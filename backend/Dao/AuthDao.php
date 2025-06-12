<?php


require_once 'BaseDao.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthDao extends BaseDao
{

    protected $table = 'users';


    public function __construct($table = 'users')
    {
        parent::__construct($table);
    }


    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $statement = $this->connection->prepare($sql);

        $statement->bindParam(':email', $email);

        $statement->execute();

        return $statement->fetch();
    }

    public function register($entity)
    {



        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        if (!filter_var($entity['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid Email Address!!!'];
        }



        $email_exists = $this->getUserByEmail($entity['email']);

        if ($email_exists) {
            echo "Email already exists\n";
            return ['success' => false, 'error' => 'Email already registered.'];
        }



        $entity['password'] = password_hash($entity['password'], PASSWORD_BCRYPT);


        $entity = parent::add($entity);


        unset($entity['password']);


        return ['success' => true, 'data' => $entity];
    }



    public static function validateEntity($entity, $fields)
    {
        $missingFields = [];

        foreach ($fields as $field) {
            if (!isset($entity[$field]) || trim($entity[$field]) === '') {
                $missingFields[] = ucfirst($field);
            }
        }

        if (!empty($missingFields)) {
            return [
                'Success' => FALSE,
                'Error' => 'The following fields are required: ' . implode(', ', $missingFields)
            ];
        }

        return ['Success' => TRUE, 'Message' => 'Entity is valid'];
    }



    public function login($entity)
    {
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->getUserByEmail($entity['email']);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid username or password.'];
        }

        if (!$user || !password_verify($entity['password'], $user['password']))
            return ['success' => false, 'error' => 'Invalid username or password.'];

        unset($user['password']);

        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24)
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return ['success' => true, 'data' => array_merge($user, ['user_token' => $token])];
    }
}