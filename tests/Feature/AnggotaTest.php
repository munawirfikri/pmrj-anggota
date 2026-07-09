<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Anggota;
use App\Models\Ikk;
use Database\Seeders\IkkSeeder;
use Database\Seeders\MasterDataSeeder;

class AnggotaTest extends TestCase
{
    use RefreshDatabase;

    private array $createdFiles = [];

    protected function setUp(): void
    {
        parent::setUp();
        // Seed master data
        $this->seed(IkkSeeder::class);
        $this->seed(MasterDataSeeder::class);
    }

    protected function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            $fullPath = storage_path('app/public/' . $file);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
        parent::tearDown();
    }

    public function test_anggota_can_register_successfully()
    {
        $file = UploadedFile::fake()->image('ktp.png');

        $response = $this->post(route('register'), [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Pekanbaru',
            'tanggal_lahir' => '1995-05-15',
            'tempat_lahir' => 'Pekanbaru',
            'jenis_kelamin' => 'Laki-laki',
            'golongan_darah' => 'A+',
            'pekerjaan' => 'Swasta',
            'alamat_jakarta' => 'Jl. Merdeka No. 10',
            'kota_bagian' => 'Jakarta Selatan',
            'no_hp' => '081234567890',
            'status_rumah' => 'Rumah Tetap',
            'foto_ktp' => $file
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('anggota', [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Pekanbaru'
        ]);

        $anggota = Anggota::where('email', 'budi.santoso@gmail.com')->first();
        $this->assertNotNull($anggota->no_anggota);
        $this->assertStringContainsString('PMRJ-01-', $anggota->no_anggota); // 01 is Pekanbaru code
        $this->assertTrue(auth('anggota')->check());
        $this->assertEquals($anggota->id, auth('anggota')->id());

        // Assert file exists in storage and is compressed to .jpg
        $this->assertNotNull($anggota->foto_ktp);
        $this->createdFiles[] = $anggota->foto_ktp;
        $this->assertTrue(file_exists(storage_path('app/public/' . $anggota->foto_ktp)));
        $this->assertStringEndsWith('.jpg', $anggota->foto_ktp);
    }

    public function test_anggota_registration_validation_fails_for_invalid_nik()
    {
        $response = $this->post(route('register'), [
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nik' => '12345', // Invalid: not 16 digits
            'asal_ikk' => 'Kota Pekanbaru'
        ]);

        $response->assertSessionHasErrors('nik');
        $this->assertDatabaseMissing('anggota', [
            'nama_lengkap' => 'Budi Santoso'
        ]);
    }

    public function test_anggota_can_login_with_correct_credentials()
    {
        $password = 'password123';
        $anggota = Anggota::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => Hash::make($password),
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Pekanbaru',
            'status' => 'active'
        ]);

        $response = $this->post(route('login'), [
            'email' => $anggota->email,
            'password' => $password
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(auth('anggota')->check());
        $this->assertEquals($anggota->id, auth('anggota')->id());
    }

    public function test_anggota_login_fails_with_incorrect_password()
    {
        $anggota = Anggota::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => Hash::make('password123'),
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Pekanbaru',
            'status' => 'active'
        ]);

        $response = $this->post(route('login'), [
            'email' => $anggota->email,
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth('anggota')->check());
    }

    public function test_anggota_can_update_profile_and_regenerate_member_number()
    {
        $anggota = Anggota::create([
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi.santoso@gmail.com',
            'password' => Hash::make('password123'),
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Pekanbaru',
            'no_anggota' => 'PMRJ-01-0001',
            'status' => 'active'
        ]);

        auth('anggota')->login($anggota);

        $foto = UploadedFile::fake()->image('profile.png');

        $response = $this->put(route('profile.update'), [
            'nama_lengkap' => 'Budi Santoso Update',
            'email' => 'budi.santoso@gmail.com',
            'nik' => '1234567890123456',
            'asal_ikk' => 'Kota Dumai', // Changing IKK Pekanbaru (01) -> Dumai (02)
            'foto' => $foto
        ]);

        $response->assertRedirect(route('profile'));
        
        $anggota->refresh();
        $this->assertEquals('Budi Santoso Update', $anggota->nama_lengkap);
        $this->assertEquals('Kota Dumai', $anggota->asal_ikk);
        
        // Assert member number regenerates with new IKK code
        $this->assertStringContainsString('PMRJ-02-', $anggota->no_anggota);
        
        // Assert profile image uploaded
        $this->assertNotNull($anggota->foto);
        $this->createdFiles[] = $anggota->foto;
        $this->assertTrue(file_exists(storage_path('app/public/' . $anggota->foto)));
    }
}
