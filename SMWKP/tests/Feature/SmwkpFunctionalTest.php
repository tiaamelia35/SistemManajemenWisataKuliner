<?php
 
namespace Tests\Feature;
 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
 
class SmwkpFunctionalTest extends TestCase
{
    use RefreshDatabase;
 
    protected function setUp(): void
    {
        parent::setUp();
        // Jalankan seeder pada database in-memory sebelum setiap pengujian
        $this->seed();
    }
 
    /**
     * Uji halaman login dapat diakses dan mengembalikan status 200.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SMWKP');
        $response->assertSee('Masuk');
    }
 
    /**
     * Uji login gagal dengan data tidak valid.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpassword',
        ]);
 
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
 
    /**
     * Uji login berhasil sebagai Wisatawan (Tourist) dan mengarahkan ke halaman jelajah.
     */
    public function test_login_successful_as_tourist(): void
    {
        $response = $this->post('/login', [
            'email' => 'tourist@smwkp.com',
            'password' => 'password',
        ]);
 
        $response->assertRedirect(route('tourist.jelajah'));
        $this->assertAuthenticated();
 
        // Verifikasi akses halaman jelajah setelah login
        $user = User::where('email', 'tourist@smwkp.com')->first();
        $jelajahResponse = $this->actingAs($user)->get('/tourist/jelajah');
        $jelajahResponse->assertStatus(200);
        $jelajahResponse->assertSee('Ampera Culinary');
    }
 
    /**
     * Uji wisatawan tidak boleh mengakses dashboard owner (proteksi role).
     */
    public function test_tourist_cannot_access_owner_dashboard(): void
    {
        $user = User::where('email', 'tourist@smwkp.com')->first();
        $response = $this->actingAs($user)->get('/owner/dashboard');
        
        // Tergantung middleware, biasanya redirect atau 403. Mari kita cek redirect atau status
        $response->assertStatus(302); // Redirect kembali jika tidak memiliki hak akses
    }
 
    /**
     * Uji login berhasil sebagai Pemilik Restoran (Owner).
     */
    public function test_login_successful_as_owner(): void
    {
        $response = $this->post('/login', [
            'email' => 'owner@smwkp.com',
            'password' => 'password',
        ]);
 
        $response->assertRedirect(route('owner.dashboard'));
        $this->assertAuthenticated();
 
        $user = User::where('email', 'owner@smwkp.com')->first();
        $dashboardResponse = $this->actingAs($user)->get('/owner/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Pemilik');
    }
 
    /**
     * Uji login berhasil sebagai Administrator (Admin).
     */
    public function test_login_successful_as_admin(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@smwkp.com',
            'password' => 'password',
        ]);
 
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
 
        $user = User::where('email', 'admin@smwkp.com')->first();
        $dashboardResponse = $this->actingAs($user)->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Konsol Admin Sistem');
    }
}
