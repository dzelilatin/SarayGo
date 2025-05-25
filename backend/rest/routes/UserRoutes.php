<?php
namespace Dzelitin\SarayGo\routes;

require_once 'BaseRoutes.php';
require_once(__DIR__ . '/../services/UserService.php');
require_once(__DIR__ . '/../middleware/AuthMiddleware.php');

use Dzelitin\SarayGo\services\UserService;
use Dzelitin\SarayGo\middleware\AuthMiddleware;
use Dzelitin\SarayGo\Roles;

/**
 * @OA\Tag(
 *     name="users",
 *     description="User management endpoints"
 * )
 */
class UserRoutes extends BaseRoutes {
    private $authMiddleware;

    public function __construct() {
        parent::__construct(new UserService());
        $this->authMiddleware = new AuthMiddleware();
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"users"},
     *     summary="Get all users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function getAll() {
        // Only admin can see all users
        $this->authMiddleware->authorizeRole(Roles::ADMIN);
        $users = $this->service->getAll();
        \Flight::json($users);
    }

    /**
     * @OA\Get(
     *     path="/users/@id",
     *     tags={"users"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function getById($id) {
        $user = \Flight::get('user');
        // Users can only view their own profile unless they're admin
        if ($user->id != $id && $user->role !== Roles::ADMIN) {
            \Flight::halt(403, 'Access denied: You can only view your own profile');
        }
        $result = $this->service->getById($id);
        \Flight::json($result);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"users"},
     *     summary="Create new user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "password", "role"},
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="role", type="string", enum={"admin", "user"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function create() {
        // Only admin can create new users
        $this->authMiddleware->authorizeRole(Roles::ADMIN);
        $data = \Flight::request()->data->getData();
        $result = $this->service->create($data);
        \Flight::json($result, 201);
    }

    /**
     * @OA\Put(
     *     path="/users/@id",
     *     tags={"users"},
     *     summary="Update user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="role", type="string", enum={"admin", "user"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function update($id) {
        $user = \Flight::get('user');
        // Only admin can update other users
        if ($user->id != $id && $user->role !== Roles::ADMIN) {
            \Flight::halt(403, 'Access denied: Only admin can update other users');
        }
        $data = \Flight::request()->data->getData();
        $result = $this->service->update($id, $data);
        \Flight::json($result);
    }

    /**
     * @OA\Delete(
     *     path="/users/@id",
     *     tags={"users"},
     *     summary="Delete user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     )
     * )
     */
    public function delete($id) {
        // Only admin can delete users
        $this->authMiddleware->authorizeRole(Roles::ADMIN);
        $result = $this->service->delete($id);
        \Flight::json($result);
    }

    public function get_routes() {
        \Flight::route('GET /users', [$this, 'getAll']);
        \Flight::route('GET /users/@id', [$this, 'getById']);
        \Flight::route('POST /users', [$this, 'create']);
        \Flight::route('PUT /users/@id', [$this, 'update']);
        \Flight::route('DELETE /users/@id', [$this, 'delete']);
    }
}
