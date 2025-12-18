<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoardingHouseResource;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties
     */
    public function index(Request $request)
    {
        $query = BoardingHouse::query()
            ->with(['rooms'])
            ->withCount([
                'rooms',
                'rooms as available_rooms_count' => function ($q) {
                    $q->where('is_available', true);
                }
            ])
            ->where('is_active', true);

        // Search by name or city
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price_range_start', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_range_end', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['created_at', 'name', 'price_range_start'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $properties = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Properties retrieved successfully',
            'data' => BoardingHouseResource::collection($properties),
            'meta' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
                'from' => $properties->firstItem(),
                'to' => $properties->lastItem(),
            ]
        ], 200);
    }

    /**
     * Display the specified property by slug
     */
    public function show($slug)
    {
        $property = BoardingHouse::where('slug', $slug)
            ->with([
                'rooms.facilities',
                'rooms' => function ($query) {
                    $query->withCount('bookings');
                },
                'owner'
            ])
            ->withCount(['rooms', 'rooms as available_rooms_count' => function ($q) {
                $q->where('is_available', true);
            }])
            ->where('is_active', true)
            ->first();

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Property detail retrieved successfully',
            'data' => new BoardingHouseResource($property)
        ], 200);
    }

    /**
     * Get owner's properties
     */
    public function ownerProperties(Request $request)
    {
        $user = auth()->user();

        $query = BoardingHouse::query()
            ->with(['rooms'])
            ->withCount([
                'rooms',
                'rooms as available_rooms_count' => function ($q) {
                    $q->where('is_available', true);
                }
            ])
            ->where('user_id', $user->id);

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, ['created_at', 'name', 'price_range_start'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $properties = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Owner properties retrieved successfully',
            'data' => BoardingHouseResource::collection($properties),
            'meta' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
                'from' => $properties->firstItem(),
                'to' => $properties->lastItem(),
            ]
        ], 200);
    }
}
