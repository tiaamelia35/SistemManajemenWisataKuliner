<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\Certification;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    // Fetch owner's restaurant
    protected function getRestaurant()
    {
        $owner = Auth::user();
        $restaurant = Restaurant::where('owner_id', $owner->id)->first();
        
        if (!$restaurant) {
            // Create a default fallback restaurant if somehow missing
            $restaurant = Restaurant::create([
                'owner_id' => $owner->id,
                'name' => 'Restoran ' . $owner->name,
                'description' => 'Silakan edit deskripsi restoran Anda.',
                'address' => 'Jl. Palembang Kuliner, Palembang',
                'latitude' => -2.983333,
                'longitude' => 104.75,
                'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800',
                'certification_status' => 'pending',
                'is_active' => true,
            ]);
        }
        
        return $restaurant;
    }

    // Owner dashboard overview
    public function dashboard()
    {
        $restaurant = $this->getRestaurant();
        
        // Calculate Revenue from completed bookings
        $revenue = $restaurant->bookings()->where('status', 'completed')->get()->sum(function($b) {
            return $b->total;
        });

        // Booking statistics
        $bookingsCount = $restaurant->bookings()->count();
        $pendingBookingsCount = $restaurant->bookings()->where('status', 'pending')->count();
        
        // Listings and reviews count
        $menus = $restaurant->menus;
        $averageRating = $restaurant->average_rating;
        $reviewsCount = $restaurant->reviews_count;

        // Recent bookings (Latest 10)
        $recentBookings = $restaurant->bookings()->with('menus')->latest()->take(10)->get();

        // Check if there are active certifications
        $certifications = $restaurant->certifications;
        $hasApprovedHalal = $restaurant->certifications()->where('type', 'Halal')->where('status', 'approved')->exists();
        $certificationPercentage = $hasApprovedHalal ? 100 : 85; // Custom business logic matching figma mock

        return view('owner.dashboard', compact(
            'restaurant', 
            'revenue', 
            'bookingsCount', 
            'pendingBookingsCount', 
            'menus', 
            'averageRating', 
            'reviewsCount', 
            'recentBookings',
            'certifications',
            'certificationPercentage'
        ));
    }

    // Manage listings page
    public function showMenus()
    {
        $restaurant = $this->getRestaurant();
        $menus = $restaurant->menus;
        return view('owner.menu', compact('restaurant', 'menus'));
    }

    // Add new menu item
    public function storeMenu(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:Pempek,Mie Celor,Pindang,Minuman,Lainnya',
            'image_file' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:500',
        ]);

        $restaurant = $this->getRestaurant();

        $imageUrl = $request->image_url ?: 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'menu_' . time() . '_' . rand(100,999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/menus'), $filename);
            $imageUrl = '/uploads/menus/' . $filename;
        }

        $menu = Menu::create([
            'restaurant_id' => $restaurant->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image_url' => $imageUrl,
            'category' => $request->category,
        ]);

        Log::write(Auth::id(), 'MENU_CREATE', "Pemilik menambahkan menu baru '{$menu->name}' pada {$restaurant->name}.");

        return redirect()->route('owner.dashboard')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // Delete menu listing item
    public function deleteMenu($id)
    {
        $restaurant = $this->getRestaurant();
        $menu = $restaurant->menus()->findOrFail($id);
        
        $name = $menu->name;
        $menu->delete();

        Log::write(Auth::id(), 'MENU_DELETE', "Pemilik menghapus menu '{$name}' dari {$restaurant->name}.");

        return back()->with('success', 'Menu berhasil dihapus!');
    }

    // Update incoming booking state
    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:confirmed,completed,cancelled',
        ]);

        $restaurant = $this->getRestaurant();
        $booking = $restaurant->bookings()->findOrFail($id);

        $oldStatus = $booking->status;
        $booking->status = $request->status;
        $booking->save();

        Log::write(Auth::id(), 'BOOKING_UPDATE', "Status reservasi #{$booking->id} diubah dari {$oldStatus} menjadi {$request->status} oleh Pemilik.");

        return back()->with('success', "Status reservasi berhasil diperbarui menjadi: " . ucfirst($request->status));
    }

    // Update restaurant details & upload certification documents
    public function updateRestaurant(Request $request)
    {
        $restaurant = $this->getRestaurant();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'address' => 'required|string|max:500',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'image_url' => 'nullable|url|max:500',
            'certificate_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        // Update restaurant profile info
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->address = $request->address;
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        if ($request->image_url) {
            $restaurant->image_url = $request->image_url;
        }
        $restaurant->save();

        // Handle certificate submission if filled
        if ($request->certificate_number) {
            $fileName = null;
            if ($request->hasFile('certificate_file')) {
                $file = $request->file('certificate_file');
                $fileName = 'cert_' . $restaurant->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/certificates'), $fileName);
            }

            Certification::create([
                'restaurant_id' => $restaurant->id,
                'type' => 'Halal',
                'certificate_number' => $request->certificate_number,
                'issued_by' => 'BPJPH / MUI',
                'expiry_date' => $request->expiry_date ?: Carbon::now()->addYears(4),
                'certificate_file' => $fileName,
                'status' => 'pending', // Awaiting Admin verification!
            ]);

            // Set certification_status of restaurant back to pending for Admin review
            $restaurant->certification_status = 'pending';
            $restaurant->save();

            Log::write(Auth::id(), 'CERTIFICATE_SUBMIT', "Pemilik mengajukan sertifikat Halal baru #{$request->certificate_number} untuk {$restaurant->name} (Menunggu verifikasi admin).");
        }

        Log::write(Auth::id(), 'RESTAURANT_UPDATE', "Pemilik memperbarui informasi dasar restoran {$restaurant->name}.");

        return back()->with('success', 'Profil restoran berhasil diperbarui!');
    }
}
