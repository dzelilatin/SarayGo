<?php
/**
 * @OA\OpenApi(
 *     openapi="3.0.0"
 * )
 */

/**
 * @OA\Info(
 *     title="SarayGo API",
 *     description="Travel and Activity API",
 *     version="1.0",
 *     @OA\Contact(
 *         email="saraygo@gmail.com",
 *         name="SaraGo"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost/SarayGo/backend",
 *     description="Local Development Server"
 * )
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */

/**
 * @OA\Tag(
 *     name="activities",
 *     description="Activity management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="blogs",
 *     description="Blog management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="categories",
 *     description="Category management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="contacts",
 *     description="Contact message endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="moods",
 *     description="Mood management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="recommendations",
 *     description="Recommendation management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="reviews",
 *     description="Review management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="user-moods",
 *     description="User mood management endpoints"
 * )
 */

/**
 * @OA\Tag(
 *     name="users",
 *     description="User management endpoints"
 * )
 */
?> 