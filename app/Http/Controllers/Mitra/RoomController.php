<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\BoardingHouse;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms for a specific property
     */
    public function index(BoardingHouse $property)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        $rooms = $property->rooms()
            ->withCount('bookings')
            ->latest()
            ->paginate(12);

        return view('mitra.rooms.index', compact('property', 'rooms'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create(BoardingHouse $property)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        $facilities = \App\Models\Facility::orderBy('name')->get();

        return view('mitra.rooms.create', compact('property', 'facilities'));
    }

    /**
     * Store a newly created room in storage
     */
    public function store(StoreRoomRequest $request, BoardingHouse $property)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        $validatedData = $request->validated();
        $validatedData['boarding_house_id'] = $property->id;

        // Handle image upload
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $images[] = $path;
            }
            $validatedData['images'] = $images;
        }

        // Remove facilities from validated data as it's a relationship
        $facilities = $validatedData['facilities'] ?? [];
        unset($validatedData['facilities']);

        $room = Room::create($validatedData);

        // Attach facilities to room
        if (!empty($facilities)) {
            $room->facilities()->attach($facilities);
        }

        return redirect()
            ->route('mitra.rooms.index', ['property' => $property->id])
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    /**
     * Display the specified room
     */
    public function show(BoardingHouse $property, Room $room)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if the room belongs to the property
        if ($room->boarding_house_id != $property->id) {
            abort(403, 'Kamar ini bukan milik properti yang dipilih.');
        }

        // Load relationships
        $room->load(['bookings.user', 'tickets']);

        return view('mitra.rooms.show', compact('property', 'room'));
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit(BoardingHouse $property, Room $room)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if the room belongs to the property
        if ($room->boarding_house_id != $property->id) {
            abort(403, 'Kamar ini bukan milik properti yang dipilih.');
        }

        $facilities = \App\Models\Facility::orderBy('name')->get();
        $room->load('facilities');

        return view('mitra.rooms.edit', compact('property', 'room', 'facilities'));
    }

    /**
     * Update the specified room in storage
     */
    public function update(UpdateRoomRequest $request, BoardingHouse $property, Room $room)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if the room belongs to the property
        if ($room->boarding_house_id != $property->id) {
            abort(403, 'Kamar ini bukan milik properti yang dipilih.');
        }

        $validatedData = $request->validated();

        // Handle existing images
        $existingImages = $request->input('existing_images', []);
        
        // Handle new image upload
        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');
                $newImages[] = $path;
            }
            
            // Merge existing and new images
            $validatedData['images'] = array_merge($existingImages, $newImages);
        } else {
            // Keep only existing images
            $validatedData['images'] = $existingImages;
        }

        // Delete removed images from storage
        if ($room->images) {
            $removedImages = array_diff($room->images, $existingImages);
            foreach ($removedImages as $removedImage) {
                Storage::disk('public')->delete($removedImage);
            }
        }

        // Remove facilities from validated data as it's a relationship
        $facilities = $validatedData['facilities'] ?? [];
        unset($validatedData['facilities']);

        $room->update($validatedData);

        // Sync facilities to room
        $room->facilities()->sync($facilities);

        return redirect()
            ->route('mitra.rooms.index', ['property' => $property->id])
            ->with('success', 'Kamar berhasil diupdate!');
    }

    /**
     * Remove the specified room from storage
     */
    public function destroy(BoardingHouse $property, Room $room)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if the room belongs to the property
        if ($room->boarding_house_id != $property->id) {
            abort(403, 'Kamar ini bukan milik properti yang dipilih.');
        }

        // Check if room has active bookings
        if ($room->bookings()->whereIn('status', ['confirmed', 'checked_in'])->exists()) {
            return redirect()
                ->route('mitra.rooms.index', $property->id)
                ->with('error', 'Tidak dapat menghapus kamar yang masih memiliki booking aktif.');
        }

        // Delete room images from storage
        if ($room->images) {
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $room->delete();

        return redirect()
            ->route('mitra.rooms.index', $property->id)
            ->with('success', 'Kamar berhasil dihapus!');
    }

    /**
     * Toggle room availability
     */
    public function toggleAvailability(BoardingHouse $property, Room $room)
    {
        // Check if the property belongs to the authenticated user
        if ($property->user_id != Auth::id()) {
            abort(403, 'Unauthorized access to this property.');
        }

        // Check if the room belongs to the property
        if ($room->boarding_house_id != $property->id) {
            abort(403, 'Kamar ini bukan milik properti yang dipilih.');
        }

        $room->update(['is_available' => !$room->is_available]);

        $status = $room->is_available ? 'tersedia' : 'tidak tersedia';
        
        return redirect()
            ->back()
            ->with('success', "Status kamar berhasil diubah menjadi {$status}!");
    }
}