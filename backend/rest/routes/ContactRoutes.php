<?php
/**
 * @OA\Get(
 *     path="/contacts",
 *     tags={"contacts"},
 *     summary="Get all contacts",
 *     @OA\Response(
 *         response=200,
 *         description="List of all contacts"
 *     )
 * )
 */
Flight::route('GET /contacts', function() {
    Flight::json(Flight::contactService()->getAll());
});

/**
 * @OA\Get(
 *     path="/contacts/{id}",
 *     tags={"contacts"},
 *     summary="Get contact by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact details"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Contact not found"
 *     )
 * )
 */
Flight::route('GET /contacts/@id', function($id) {
    $contact = Flight::contactService()->getById($id);
    if ($contact) {
        Flight::json($contact);
    } else {
        Flight::halt(404, 'Contact not found');
    }
});

/**
 * @OA\Post(
 *     path="/contacts",
 *     tags={"contacts"},
 *     summary="Create a new contact",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "message"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="message", type="string", example="I would like to inquire about..."),
 *             @OA\Property(property="subject", type="string", example="General Inquiry"),
 *             @OA\Property(property="phone", type="string", example="+1234567890")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Contact created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /contacts', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactService()->create($data), 201);
});

/**
 * @OA\Put(
 *     path="/contacts/{id}",
 *     tags={"contacts"},
 *     summary="Update contact by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="message", type="string", example="I would like to inquire about..."),
 *             @OA\Property(property="subject", type="string", example="General Inquiry"),
 *             @OA\Property(property="phone", type="string", example="+1234567890"),
 *             @OA\Property(property="status", type="string", enum={"new", "in_progress", "resolved"}, example="in_progress")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Contact not found"
 *     )
 * )
 */
Flight::route('PUT /contacts/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/contacts/{id}",
 *     tags={"contacts"},
 *     summary="Delete contact by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Contact not found"
 *     )
 * )
 */
Flight::route('DELETE /contacts/@id', function($id) {
    Flight::json(Flight::contactService()->delete($id));
});

/**
 * @OA\Get(
 *     path="/contacts/status/{status}",
 *     tags={"contacts"},
 *     summary="Get contacts by status",
 *     @OA\Parameter(
 *         name="status",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", enum={"new", "in_progress", "resolved"}, example="new")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of contacts with specified status"
 *     )
 * )
 */
Flight::route('GET /contacts/status/@status', function($status) {
    Flight::json(Flight::contactService()->getByStatus($status));
});

/**
 * @OA\Get(
 *     path="/contacts/search",
 *     tags={"contacts"},
 *     summary="Search contacts",
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string", example="John")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of matching contacts"
 *     )
 * )
 */
Flight::route('GET /contacts/search', function() {
    $query = Flight::request()->query['query'];
    Flight::json(Flight::contactService()->search($query));
});

// Mark contact as read
Flight::route('PUT /contacts/@id/read', function($id) {
    try {
        $result = Flight::contactService()->mark_as_read($id);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Contact message not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Mark contact as unread
Flight::route('PUT /contacts/@id/unread', function($id) {
    try {
        $result = Flight::contactService()->mark_as_unread($id);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Contact message not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Archive contact
Flight::route('PUT /contacts/@id/archive', function($id) {
    try {
        $result = Flight::contactService()->archive($id);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Contact message not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Get recent contacts
Flight::route('GET /contacts/recent', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::contactService()->getRecentContacts($limit));
});

// Get unread count
Flight::route('GET /contacts/unread-count', function() {
    Flight::json(['count' => Flight::contactService()->getUnreadCount()]);
});
?> 