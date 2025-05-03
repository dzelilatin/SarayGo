<?php
/**
 * @OA\Get(
 *     path="/api/contacts",
 *     tags={"contacts"},
 *     summary="Get all contacts",
 *     @OA\Response(
 *         response=200,
 *         description="List of all contacts",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
 *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *                 @OA\Property(property="message", type="string", example="I have a question about..."),
 *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T12:00:00Z")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /api/contacts', function() {
    $status = Flight::request()->query['status'] ?? null;
    $query = Flight::request()->query['query'] ?? null;
    
    if ($query) {
        Flight::json(Flight::contactService()->searchContacts($query));
    } else if ($status) {
        Flight::json(Flight::contactService()->get_by_status($status));
    } else {
        Flight::json(Flight::contactService()->getAll());
    }
});

/**
 * @OA\Get(
 *     path="/api/contacts/{id}",
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
Flight::route('GET /api/contacts/@id', function($id) {
    $contact = Flight::contactService()->getById($id);
    if ($contact) {
        Flight::json($contact);
    } else {
        Flight::halt(404, 'Contact message not found');
    }
});

/**
 * @OA\Post(
 *     path="/api/contacts",
 *     tags={"contacts"},
 *     summary="Create a new contact message",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "message"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *             @OA\Property(property="message", type="string", example="I have a question about...")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Contact message created successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */
Flight::route('POST /api/contacts', function() {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::contactService()->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/api/contacts/{id}",
 *     tags={"contacts"},
 *     summary="Update a contact message",
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
 *             @OA\Property(property="message", type="string", example="I have a question about...")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact message updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Contact not found"
 *     )
 * )
 */
Flight::route('PUT /api/contacts/@id', function($id) {
    $data = Flight::request()->data->getData();
    try {
        $result = Flight::contactService()->update($id, $data);
        if ($result) {
            Flight::json($result);
        } else {
            Flight::halt(404, 'Contact message not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/api/contacts/{id}",
 *     tags={"contacts"},
 *     summary="Delete a contact message",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Contact message deleted successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Contact not found"
 *     )
 * )
 */
Flight::route('DELETE /api/contacts/@id', function($id) {
    try {
        $result = Flight::contactService()->delete($id);
        if ($result) {
            Flight::halt(204);
        } else {
            Flight::halt(404, 'Contact message not found');
        }
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

// Mark contact as read
Flight::route('PUT /api/contacts/@id/read', function($id) {
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
Flight::route('PUT /api/contacts/@id/unread', function($id) {
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
Flight::route('PUT /api/contacts/@id/archive', function($id) {
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
Flight::route('GET /api/contacts/recent', function() {
    $limit = Flight::request()->query['limit'] ?? 10;
    Flight::json(Flight::contactService()->getRecentContacts($limit));
});

// Get unread count
Flight::route('GET /api/contacts/unread-count', function() {
    Flight::json(['count' => Flight::contactService()->getUnreadCount()]);
});
?> 