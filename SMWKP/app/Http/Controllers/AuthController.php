<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            Log::write(
                $user->id,
                'USER_LOGIN',
                "User {$user->name} ({$user->role}) berhasil masuk ke dalam sistem."
            );

            return $this->redirectBasedOnRole($user);
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])
            ->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:tourist,owner',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'register');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'profile_photo' => 'default.jpg',
        ]);

        Log::write(
            $user->id,
            'USER_REGISTER',
            "Pendaftaran pengguna baru: {$user->name} sebagai {$user->role}."
        );

        if ($user->role === 'owner') {
            Restaurant::create([
                'owner_id' => $user->id,
                'name' => 'Restoran ' . $user->name,
                'description' => 'Silakan edit deskripsi restoran kuliner Palembang Anda di halaman profil pemilik.',
                'address' => 'Jl. Palembang Kuliner, Palembang',
                'latitude' => -2.983333,
                'longitude' => 104.75,
                'image_url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800',
                'certification_status' => 'pending',
                'is_active' => true,
            ]);

            Log::write(
                $user->id,
                'RESTAURANT_CREATE',
                "Restoran baru dibuat otomatis untuk pemilik {$user->name} (Menunggu verifikasi admin)."
            );
        }

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran berhasil. Silakan login untuk melanjutkan.')
            ->with('active_tab', 'login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            Log::write(
                $user->id,
                'USER_LOGOUT',
                "User {$user->name} keluar dari sistem."
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }

    // Role redirection logic
    protected function redirectBasedOnRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'owner') {
            return redirect()->intended(route('owner.dashboard'));
        }

        return redirect()->intended(route('tourist.jelajah'));
    }
}