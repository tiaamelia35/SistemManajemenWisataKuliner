<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Certification;
use App\Models\Log;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Core Users
        $admin = User::create([
            'name' => 'Administrator SMWKP',
            'email' => 'admin@smwkp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone_number' => '081122334455',
            'profile_photo' => 'admin.jpg',
        ]);

        $owner1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'owner@smwkp.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone_number' => '081234567890',
            'profile_photo' => 'owner1.jpg',
        ]);

        $owner2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'owner2@smwkp.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone_number' => '081298765432',
            'profile_photo' => 'owner2.jpg',
        ]);

        $tourist1 = User::create([
            'name' => 'John Doe',
            'email' => 'tourist@smwkp.com',
            'password' => Hash::make('password'),
            'role' => 'tourist',
            'phone_number' => '087766554433',
            'profile_photo' => 'tourist1.jpg',
        ]);

        $tourist2 = User::create([
            'name' => 'Alice Nyberg',
            'email' => 'tourist2@smwkp.com',
            'password' => Hash::make('password'),
            'role' => 'tourist',
            'phone_number' => '089988776655',
            'profile_photo' => 'tourist2.jpg',
        ]);

        // 2. Create Restaurants
        $rest1 = Restaurant::create([
            'owner_id' => $owner1->id,
            'name' => 'RM Ampera Raya',
            'description' => 'Restoran masakan khas Palembang terlengkap dan terpopuler di dekat Jembatan Ampera. Menyajikan Pempek hangat, Mie Celor udang galah, dan Pindang Patin segar.',
            'address' => 'Jl. Jend. Sudirman No. 120, Palembang',
            'latitude' => -2.988647,
            'longitude' => 104.757041,
            'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800',
            'certification_status' => 'approved',
            'is_active' => true,
        ]);

        $rest2 = Restaurant::create([
            'owner_id' => $owner2->id,
            'name' => 'Pempek Beringin',
            'description' => 'Pusat pempek legendaris Palembang sejak 1970. Menggunakan bahan ikan tenggiri segar pilihan dengan resep cuko kental turun-temurun.',
            'address' => 'Jl. Lingkaran I No. 412, Palembang',
            'latitude' => -2.977467,
            'longitude' => 104.764567,
            'image_url' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800',
            'certification_status' => 'approved',
            'is_active' => true,
        ]);

        $rest3 = Restaurant::create([
            'owner_id' => $owner2->id,
            'name' => 'RM Sri Melayu',
            'description' => 'Spesialis Pindang ikan patin, pindang tulang, dan aneka pepes khas Palembang dengan suasana pondokan yang asri dan tradisional.',
            'address' => 'Jl. Demang Lebar Daun No. 1, Palembang',
            'latitude' => -2.969123,
            'longitude' => 104.729123,
            'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800',
            'certification_status' => 'pending', // Seeded as PENDING for admin review trigger!
            'is_active' => true,
        ]);

        // 3. Create Menus
        // RM Ampera Raya Menus
        $m1 = Menu::create([
            'restaurant_id' => $rest1->id,
            'name' => 'Pempek Kapal Selam',
            'description' => 'Pempek ukuran besar dengan isian telur bebek utuh yang gurih, disajikan dengan cuko pedas-manis.',
            'price' => 35000,
            'image_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800',
            'category' => 'Pempek',
        ]);

        $m2 = Menu::create([
            'restaurant_id' => $rest1->id,
            'name' => 'Mie Celor Spesial',
            'description' => 'Mie kuning tebal khas Palembang disiram kuah kaldu udang kental dengan irisan telur rebus dan toge.',
            'price' => 28000,
            'image_url' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=800',
            'category' => 'Mie Celor',
        ]);

        $m3 = Menu::create([
            'restaurant_id' => $rest1->id,
            'name' => 'Pindang Patin Bagus',
            'description' => 'Sup ikan patin pedas manis asam yang segar dengan bumbu rempah aromatik khas daerah seberang ulu.',
            'price' => 45000,
            'image_url' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=800',
            'category' => 'Pindang',
        ]);

        $m4 = Menu::create([
            'restaurant_id' => $rest1->id,
            'name' => 'Es Kacang Merah Komplit',
            'description' => 'Pencuci mulut segar berupa kacang merah lembut dengan serutan es, susu kental manis cokelat, dan sirup merah.',
            'price' => 15000,
            'image_url' => 'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=800',
            'category' => 'Minuman',
        ]);

        // Pempek Beringin Menus
        $m5 = Menu::create([
            'restaurant_id' => $rest2->id,
            'name' => 'Pempek Adaan Premium',
            'description' => 'Pempek bulat dengan bumbu bawang merah yang harum dan adonan ikan tenggiri yang kenyal dan lezat.',
            'price' => 7000,
            'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=800',
            'category' => 'Pempek',
        ]);

        $m6 = Menu::create([
            'restaurant_id' => $rest2->id,
            'name' => 'Pempek Lenjer Potong',
            'description' => 'Pempek berbentuk tabung panjang (lenjer) yang dipotong-potong, digoreng garing di luar lembut di dalam.',
            'price' => 7000,
            'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=800',
            'category' => 'Pempek',
        ]);

        $m7 = Menu::create([
            'restaurant_id' => $rest2->id,
            'name' => 'Pindang Tulang Iga',
            'description' => 'Pindang tulang iga sapi dengan kuah bening pedas-asam bertabur kemangi dan daun bawang.',
            'price' => 50000,
            'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=800',
            'category' => 'Pindang',
        ]);

        // 4. Create Bookings & Link Menus
        // Booking 1 - Confirmed (John Doe)
        $b1 = Booking::create([
            'tourist_id' => $tourist1->id,
            'restaurant_id' => $rest1->id,
            'name' => 'John Doe',
            'phone_number' => '087766554433',
            'booking_date' => Carbon::now()->addHours(2),
            'pax_count' => 4,
            'status' => 'confirmed',
        ]);
        $b1->menus()->attach($m1->id, ['quantity' => 2, 'price_at_booking' => 35000]);
        $b1->menus()->attach($m2->id, ['quantity' => 2, 'price_at_booking' => 28000]);

        // Booking 2 - Pending (Alice Nyberg)
        $b2 = Booking::create([
            'tourist_id' => $tourist2->id,
            'restaurant_id' => $rest1->id,
            'name' => 'Alice Nyberg',
            'phone_number' => '089988776655',
            'booking_date' => Carbon::now()->addDays(1)->setHour(19)->setMinute(0),
            'pax_count' => 2,
            'status' => 'pending',
        ]);
        $b2->menus()->attach($m3->id, ['quantity' => 2, 'price_at_booking' => 45000]);
        $b2->menus()->attach($m4->id, ['quantity' => 2, 'price_at_booking' => 15000]);

        // Booking 3 - Completed (John Doe)
        $b3 = Booking::create([
            'tourist_id' => $tourist1->id,
            'restaurant_id' => $rest2->id,
            'name' => 'John Doe',
            'phone_number' => '087766554433',
            'booking_date' => Carbon::now()->subDays(2),
            'pax_count' => 3,
            'status' => 'completed',
        ]);
        $b3->menus()->attach($m5->id, ['quantity' => 5, 'price_at_booking' => 7000]);
        $b3->menus()->attach($m6->id, ['quantity' => 5, 'price_at_booking' => 7000]);

        // 5. Create Reviews
        Review::create([
            'tourist_id' => $tourist1->id,
            'restaurant_id' => $rest1->id,
            'rating' => 5,
            'review_text' => 'Cuko nya luar biasa mantap! Kental, pedas, dan asam pas sekali di lidah. Pempek kapal selamnya juga kenyal berisi telur utuh bebek.',
            'status' => 'approved',
        ]);

        Review::create([
            'tourist_id' => $tourist2->id,
            'restaurant_id' => $rest1->id,
            'rating' => 4,
            'review_text' => 'Mie celor udangnya mantap dan porsinya mengenyangkan, bumbu kuahnya kaya rasa rempah udang.',
            'status' => 'approved',
        ]);

        Review::create([
            'tourist_id' => $tourist1->id,
            'restaurant_id' => $rest2->id,
            'rating' => 5,
            'review_text' => 'Pempek adaan premium Beringin memang tidak ada tandingannya di Palembang! Selalu beli oleh-oleh dari sini.',
            'status' => 'approved',
        ]);

        Review::create([
            'tourist_id' => $tourist2->id,
            'restaurant_id' => $rest3->id,
            'rating' => 3,
            'review_text' => 'RM Sri Melayu lokasinya bagus asri, pindang patin asam segar, tapi durasi penyajiannya agak lama.',
            'status' => 'pending', // Seeded as PENDING for admin review trigger!
        ]);

        // 6. Create Certifications
        Certification::create([
            'restaurant_id' => $rest1->id,
            'type' => 'Halal',
            'certificate_number' => 'ID31110001234567',
            'issued_by' => 'LPPOM MUI Sumsel',
            'expiry_date' => Carbon::now()->addYears(4),
            'certificate_file' => 'halal_ampera.pdf',
            'status' => 'approved',
        ]);

        Certification::create([
            'restaurant_id' => $rest2->id,
            'type' => 'Halal',
            'certificate_number' => 'ID31110009876543',
            'issued_by' => 'LPPOM MUI Sumsel',
            'expiry_date' => Carbon::now()->addYears(3),
            'certificate_file' => 'halal_beringin.pdf',
            'status' => 'approved',
        ]);

        Certification::create([
            'restaurant_id' => $rest3->id,
            'type' => 'Halal',
            'certificate_number' => 'ID31110005555555',
            'issued_by' => 'Badan Penyelenggara Jaminan Produk Halal',
            'expiry_date' => Carbon::now()->addYears(2),
            'certificate_file' => 'halal_srimelayu.pdf',
            'status' => 'pending', // Seeded as PENDING for admin review trigger!
        ]);

        // 7. Create System Logs
        Log::write($admin->id, 'SYSTEM_START', 'Sistem Manajemen Wisata Kuliner Palembang diinisialisasi.');
        Log::write($owner1->id, 'RESTAURANT_UPDATE', 'Pemilik Budi Santoso memperbarui informasi RM Ampera Raya.');
        Log::write($tourist1->id, 'BOOKING_CREATE', 'Wisatawan John Doe melakukan reservasi meja di RM Ampera Raya.');
        Log::write($owner1->id, 'BOOKING_APPROVE', 'Pemilik menyetujui reservasi John Doe (Booking ID #1).');
        Log::write($tourist2->id, 'REVIEW_CREATE', 'Wisatawan Alice Nyberg menulis review tertunda di RM Sri Melayu.');
    }
}
