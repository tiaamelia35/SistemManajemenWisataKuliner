<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TouristController extends Controller
{
    // Explore/Jelajah page
    public function jelajah(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        // Only show approved restaurants
        $query = Restaurant::where('certification_status', 'approved')->where('is_active', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        if ($category && $category !== 'Semua') {
            $query->whereHas('menus', function($q) use ($category) {
                $q->where('category', $category);
            });
        }

        $restaurants = $query->get();

        // Unique categories from seeded menus for filtering
        $categories = ['Semua', 'Pempek', 'Mie Celor', 'Pindang', 'Minuman'];

        return view('tourist.jelajah', compact('restaurants', 'categories', 'search', 'category'));
    }

    // Detail Restaurant view
    public function detail($id)
    {
        $restaurant = Restaurant::where('certification_status', 'approved')->findOrFail($id);
        
        // Group menus by their category for clear layout grouping
        $menusByCategory = $restaurant->menus->groupBy('category');
        
        $reviews = $restaurant->reviews()->where('status', 'approved')->with('tourist')->latest()->get();

        return view('tourist.detail', compact('restaurant', 'menusByCategory', 'reviews'));
    }

    // Show step-by-step reservation page
    public function showBooking($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $menus = $restaurant->menus;
        return view('tourist.booking', compact('restaurant', 'menus'));
    }

    // Store custom table reservation
    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'booking_date' => 'required|date|after:now',
            'pax_count' => 'required|integer|min:1',
            'items' => 'required|array',
            'items.*' => 'required|integer|min:0',
        ]);

        $restaurant = Restaurant::findOrFail($id);
        $tourist = Auth::user();

        // 1. Double check that at least one item has quantity > 0
        $items = array_filter($request->items, function($qty) {
            return $qty > 0;
        });

        if (empty($items)) {
            return back()->withErrors(['items' => 'Anda harus memilih setidaknya 1 menu makanan/minuman untuk dipesan.'])->withInput();
        }

        // 2. Create the Booking entry
        $booking = Booking::create([
            'tourist_id' => $tourist->id,
            'restaurant_id' => $restaurant->id,
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'booking_date' => $request->booking_date,
            'pax_count' => $request->pax_count,
            'status' => 'pending', // Awaiting owner confirmation
        ]);

        // 3. Populate Booking Menus junction items
        foreach ($items as $menuId => $qty) {
            $menu = Menu::findOrFail($menuId);
            $booking->menus()->attach($menuId, [
                'quantity' => $qty,
                'price_at_booking' => $menu->price
            ]);
        }

        // 4. Audit Log
        Log::write($tourist->id, 'BOOKING_CREATE', "Wisatawan {$tourist->name} memesan di {$restaurant->name} untuk tanggal {$request->booking_date} (Booking ID #{$booking->id}).");

        return redirect()->route('tourist.bookings')->with('success', 'Reservasi Anda telah berhasil diajukan! Menunggu persetujuan pemilik restoran.');
    }

    // List of customer bookings
    public function bookings()
    {
        $bookings = Auth::user()->bookings()->with('restaurant')->latest()->get();
        return view('tourist.history', compact('bookings'));
    }

    // Submit a review for a restaurant
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $restaurant = Restaurant::findOrFail($id);
        $tourist = Auth::user();

        Review::create([
            'tourist_id' => $tourist->id,
            'restaurant_id' => $restaurant->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'status' => 'pending', // Pending Admin review!
        ]);

        Log::write($tourist->id, 'REVIEW_CREATE', "Wisatawan {$tourist->name} memberikan ulasan rating {$request->rating} untuk {$restaurant->name} (Menunggu verifikasi admin).");

        return back()->with('success', 'Ulasan Anda berhasil dikirim! Ulasan akan tampil setelah diperiksa dan disetujui oleh admin.');
    }

    // Profile page
    public function profile()
    {
        return view('tourist.profile', ['user' => Auth::user()]);
    }

    // Edit Profile details
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $filename);
            $user->profile_photo = $filename;
        }

        $user->save();

        Log::write($user->id, 'USER_UPDATE', "Wisatawan {$user->name} memperbarui profil akun mereka.");

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
