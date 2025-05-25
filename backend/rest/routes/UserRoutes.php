<?php
namespace Dzelitin\SarayGo\routes;

require_once 'BaseRoutes.php';
require_once(__DIR__ . '/../services/UserService.php');

use Dzelitin\SarayGo\services\UserService;

/**
 * @OA\Tag(
 *     name="users",
 *     description="User management endpoints"
 * )
 */
class UserRoutes extends BaseRoutes {
    public function __construct() {
        parent::__construct(new UserService());
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"users"},
     *     summary="Get all users",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getAll() {
        $result = $this->service->getAll();
        \Flight::json($result);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"users"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getById($id) {
        $result = $this->service->getById($id);
        if ($result) {
            \Flight::json($result);
        } else {
            \Flight::halt(404, 'User not found');
        }
    }

    public function get_routes() {
        \Flight::route('GET /users', [$this, 'getAll']);
        \Flight::route('GET /users/@id', [$this, 'getById']);
        \Flight::route('POST /users', [$this, 'add']);
        \Flight::route('PUT /users/@id', [$this, 'update']);
        \Flight::route('DELETE /users/@id', [$this, 'delete']);
    }

    public function add() {
        $data = \Flight::request()->data->getData();
        $result = $this->service->add($data);
        \Flight::json($result, 201);
    }

    public function update($id) {
        $data = \Flight::request()->data->getData();
        $result = $this->service->update($id, $data);
        if ($result) {
            \Flight::json($result);
        } else {
            \Flight::halt(404, 'User not found');
        }
    }

    public function delete($id) {
        $result = $this->service->delete($id);
        if ($result) {
            \Flight::json(['message' => 'User deleted successfully']);
        } else {
            \Flight::halt(404, 'User not found');
        }
    }
}
