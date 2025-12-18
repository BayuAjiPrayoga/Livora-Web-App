<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Models\Room;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Display the landing page
     */
    public function index()
    {
        // Get featured boarding houses (latest 6)
        $featuredProperties = BoardingHouse::with(['rooms', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Get statistics
        $stats = [
            'total_properties' => BoardingHouse::count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('is_available', true)->count(),
        ];

        return view('public.index', compact('featuredProperties', 'stats'));
    }

    /**
     * Display all boarding houses (browse page)
     */
    public function browse(Request $request)
    {
        $query = BoardingHouse::with(['rooms', 'user']);

        // Search by name or address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->join('rooms', 'boarding_houses.id', '=', 'rooms.boarding_house_id')
                      ->orderBy('rooms.price', 'asc')
                      ->select('boarding_houses.*')
                      ->distinct();
                break;
            case 'price_high':
                $query->join('rooms', 'boarding_houses.id', '=', 'rooms.boarding_house_id')
                      ->orderBy('rooms.price', 'desc')
                      ->select('boarding_houses.*')
                      ->distinct();
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $properties = $query->paginate(12);

        // Get unique cities for filter
        $cities = BoardingHouse::select('city')
            ->distinct()
            ->whereNotNull('city')
            ->pluck('city');

        return view('public.browse', compact('properties', 'cities'));
    }

    /**
     * Display single boarding house detail
     */
    public function show(BoardingHouse $boardingHouse)
    {
        $boardingHouse->load(['rooms' => function($query) {
            $query->where('is_available', true);
        }, 'user']);

        // Get other properties from same owner
        $otherProperties = BoardingHouse::where('user_id', $boardingHouse->user_id)
            ->where('id', '!=', $boardingHouse->id)
            ->with('rooms')
            ->take(3)
            ->get();

        return view('public.show', compact('boardingHouse', 'otherProperties'));
    }

    /**
     * Display about page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('public.contact');
    }
}
