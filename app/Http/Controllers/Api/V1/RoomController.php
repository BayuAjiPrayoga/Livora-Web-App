<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display the specified room
     */
    public function show($id)
    {
        $room = Room::with([
            'boardingHouse.owner',
            'facilities',
        ])
        ->withCount('bookings')
        ->find($id);

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Room not found',
                'data' => null
            ], 404);
        }

        // Check if boarding house is active
        if (!$room->boardingHouse || !$room->boardingHouse->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Room not available',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Room detail retrieved successfully',
            'data' => new RoomResource($room)
        ], 200);
    }
}
