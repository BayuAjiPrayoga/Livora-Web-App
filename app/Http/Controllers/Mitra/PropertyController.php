<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\BoardingHouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function __construct()
    {
        // Middleware handled at route level
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $properties = Auth::user()->boardingHouses()
            ->withCount(['rooms'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mitra.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mitra.properties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $property = BoardingHouse::create($validated);

        return redirect()->route('mitra.properties.show', $property->id)
            ->with('success', 'Properti berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BoardingHouse $property)
    {
        // Ensure user owns this property
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }
        
        $property->load(['rooms.facilities', 'rooms' => function($query) {
            $query->withCount('bookings');
        }]);

        return view('mitra.properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BoardingHouse $property)
    {
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }
        
        return view('mitra.properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BoardingHouse $property)
    {
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }
        
        // Manual validation to avoid the array key issue
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string', 
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);
        
        // Generate slug if not provided or empty
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Handle image management
        $existingImages = $request->input('existing_images', []);
        $newImages = [];
        
        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $newImages[] = $path;
            }
        }
        
        // Combine existing images (that weren't deleted) with new images
        $allImages = array_merge($existingImages, $newImages);
        
        // Delete images that are no longer in the existing_images array
        if ($property->images && is_array($property->images)) {
            foreach ($property->images as $oldImage) {
                if (!in_array($oldImage, $existingImages)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        }
        
        // Update images in validated data
        $validated['images'] = $allImages;

        $property->update($validated);

        return redirect()->route('mitra.properties.show', $property->id)
            ->with('success', 'Properti berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoardingHouse $property)
    {
        if ($property->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }
        
        // Delete images
        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        
        $property->delete();

        return redirect()->route('mitra.properties.index')
            ->with('success', 'Properti berhasil dihapus!');
    }
}
