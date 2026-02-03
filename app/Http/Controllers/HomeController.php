<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouse;
use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured properties (latest 6)
        $properties = BoardingHouse::with(['rooms'])
            ->withCount(['rooms', 'rooms as available_rooms_count' => function($query) {
                $query->where('is_available', true);
            }])
            ->latest()
            ->take(6)
            ->get();

        // Get statistics
        $stats = [
            'total_properties' => BoardingHouse::count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('is_available', true)->count(),
        ];

        return view('public.index', compact('properties', 'stats'));
    }

    public function browse(Request $request)
    {
        $query = BoardingHouse::with(['rooms'])
            ->withCount(['rooms', 'rooms as available_rooms_count' => function($query) {
                $query->where('is_available', true);
            }]);

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

        // Filter by price range
        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Get the data first before pagination
        $properties = $query->get();
        
        // Sorting - applied after getting the data
        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $properties = $properties->sortBy(function($property) {
                    return $property->rooms->min('price');
                });
                break;
            case 'price_high':
                $properties = $properties->sortByDesc(function($property) {
                    return $property->rooms->min('price');
                });
                break;
            case 'name':
                $properties = $properties->sortBy('name');
                break;
            default: // latest
                $properties = $properties->sortByDesc('created_at');
        }
        
        // Manual pagination
        $perPage = 12;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedItems = $properties->slice($offset, $perPage)->values();
        
        $properties = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            $properties->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get available cities for filter
        $cities = BoardingHouse::select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('public.browse', compact('properties', 'cities'));
    }

    public function show($id)
    {
        $property = BoardingHouse::with(['rooms.facilities', 'user'])
            ->withCount(['rooms', 'rooms as available_rooms_count' => function($query) {
                $query->where('is_available', true);
            }])
            ->findOrFail($id);

        // Get other properties from the same owner (max 3)
        $otherProperties = BoardingHouse::where('user_id', $property->user_id)
            ->where('id', '!=', $property->id)
            ->with(['rooms'])
            ->withCount(['rooms as available_rooms_count' => function($query) {
                $query->where('is_available', true);
            }])
            ->take(3)
            ->get();

        return view('public.show', compact('property', 'otherProperties'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you can save to database or send email
        // For now, just redirect back with success message

        return back()->with('success', 'Terima kasih! Pesan Anda telah dikirim. Kami akan segera menghubungi Anda.');
    }
}
