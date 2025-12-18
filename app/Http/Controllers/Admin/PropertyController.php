<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardingHouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = BoardingHouse::with(['user', 'rooms']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('city', $request->location);
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'verified':
                    $query->where('is_verified', true);
                    break;
                case 'pending':
                    $query->where('is_verified', false);
                    break;
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        // Sort by created_at desc by default
        $query->orderBy('created_at', 'desc');

        $properties = $query->paginate(15)->withQueryString();
        
        // Get unique locations for filter
        $locations = BoardingHouse::distinct()->pluck('city')->filter()->sort();

        return view('admin.properties.index', compact('properties', 'locations'));
    }

    public function create()
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.properties.create', compact('owners'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'price_range_start' => 'required|numeric|min:0',
                'price_range_end' => 'required|numeric|min:0|gte:price_range_start',
                'user_id' => 'required|exists:users,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Generate unique slug
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            
            while (BoardingHouse::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $property = BoardingHouse::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'price_range_start' => $request->price_range_start,
                'price_range_end' => $request->price_range_end,
                'is_verified' => $request->boolean('is_verified', false),
                'is_active' => $request->boolean('is_active', true),
            ]);

            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('boarding-house-images', 'public');
                    $imagePaths[] = $path;
                }
                $property->images = $imagePaths;
                $property->save();
            }

            return redirect()->route('admin.properties.index')
                             ->with('success', 'Property berhasil ditambahkan.');
                             
        } catch (\Exception $e) {
            \Log::error('Property store error: ' . $e->getMessage());
            
            return back()->withInput()
                         ->with('error', 'Terjadi kesalahan saat menyimpan property: ' . $e->getMessage());
        }
    }

    public function show(BoardingHouse $property)
    {
        $property->load(['user', 'rooms']);

        // Get property statistics with error handling
        $stats = [];
        
        try {
            $stats = [
                'total_rooms' => $property->rooms()->count(),
                'occupied_rooms' => $property->rooms()->where('is_occupied', true)->count(),
                'total_bookings' => $property->bookings()->count(),
                'active_bookings' => $property->bookings()->where('status', 'active')->count(),
                'monthly_revenue' => $property->bookings()
                    ->where('status', 'confirmed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('final_amount'),
                'total_revenue' => $property->bookings()
                    ->where('status', 'confirmed')
                    ->sum('final_amount'),
            ];
        } catch (\Exception $e) {
            \Log::error('Property stats error: ' . $e->getMessage());
            $stats = [
                'total_rooms' => 0,
                'occupied_rooms' => 0,
                'total_bookings' => 0,
                'active_bookings' => 0,
                'monthly_revenue' => 0,
                'total_revenue' => 0,
            ];
        }

        return view('admin.properties.show', compact('property', 'stats'));
    }

    public function edit(BoardingHouse $property)
    {
        $owners = User::where('role', 'owner')->get();
        return view('admin.properties.edit', compact('property', 'owners'));
    }

    public function update(Request $request, BoardingHouse $property)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'price_range_start' => 'required|numeric|min:0',
            'price_range_end' => 'required|numeric|min:0|gte:price_range_start',
            'user_id' => 'required|exists:users,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $property->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'price_range_start' => $request->price_range_start,
            'price_range_end' => $request->price_range_end,
            'is_verified' => $request->boolean('is_verified'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $existingImages = $property->images ?? [];
            $newImages = [];
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('boarding-house-images', 'public');
                $newImages[] = $path;
            }
            
            $property->images = array_merge($existingImages, $newImages);
            $property->save();
        }

        return redirect()->route('admin.properties.index')
                         ->with('success', 'Property berhasil diupdate.');
    }

    public function destroy(BoardingHouse $property)
    {
        // Delete property images
        if ($property->images) {
            foreach ($property->images as $imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $property->delete();

        return redirect()->route('admin.properties.index')
                         ->with('success', 'Property berhasil dihapus.');
    }

    public function verify(BoardingHouse $property)
    {
        $property->update(['is_verified' => true]);

        return back()->with('success', 'Property berhasil diverifikasi.');
    }

    public function suspend(BoardingHouse $property)
    {
        $property->update(['is_active' => false]);

        return back()->with('success', 'Property berhasil di-suspend.');
    }

    public function bulkVerify(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:boarding_houses,id'
        ]);

        BoardingHouse::whereIn('id', $request->property_ids)->update(['is_verified' => true]);

        return response()->json(['message' => 'Properties berhasil diverifikasi.']);
    }

    public function bulkSuspend(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:boarding_houses,id'
        ]);

        BoardingHouse::whereIn('id', $request->property_ids)->update(['is_active' => false]);

        return response()->json(['message' => 'Properties berhasil di-suspend.']);
    }

    public function export(Request $request)
    {
        $query = BoardingHouse::with('user');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('city', $request->location);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'verified':
                    $query->where('is_verified', true);
                    break;
                case 'pending':
                    $query->where('is_verified', false);
                    break;
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        $properties = $query->orderBy('created_at', 'desc')->get();

        $csvData = [];
        $csvData[] = ['ID', 'Name', 'Owner', 'City', 'Address', 'Price Range', 'Verified', 'Status', 'Created At'];

        foreach ($properties as $property) {
            $csvData[] = [
                $property->id,
                $property->name,
                $property->user->name,
                $property->city,
                $property->address,
                'Rp ' . number_format($property->price_range_start) . ' - Rp ' . number_format($property->price_range_end),
                $property->is_verified ? 'Yes' : 'No',
                $property->is_active ? 'Active' : 'Inactive',
                $property->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'properties_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Get rooms for a specific property (API endpoint)
     */
    public function getRooms($propertyId)
    {
        $property = BoardingHouse::with('rooms')->findOrFail($propertyId);

        return response()->json([
            'success' => true,
            'property' => $property->name,
            'rooms' => $property->rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'number' => $room->number ?? null,
                    'price' => $room->price,
                    'capacity' => $room->capacity,
                    'size' => $room->size,
                    'description' => $room->description,
                    'is_available' => $room->is_available
                ];
            })
        ]);
    }
}