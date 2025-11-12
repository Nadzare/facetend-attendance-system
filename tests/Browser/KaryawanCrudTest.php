<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;

class KaryawanCrudTest extends DuskTestCase
{
    use DatabaseMigrations;

    #[Test]
    public function admin_can_login()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/login')
                ->waitFor('input[name="email"]')
                ->type('email', $admin->email)
                ->type('password', 'password')
                ->press('button[type="submit"]')
                ->waitForLocation('/admin/dashboard', 10)
                ->pause(500)
                ->assertPathIs('/admin/dashboard');
        });
    }

    #[Test]
    public function can_create_karyawan()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->browse(function (Browser $browser) use ($admin, $user) {
        $browser->loginAs($admin)
            ->visit('/admin/karyawan/create')
            ->select('user_id', $user->id)
            ->type('nama', 'Kafah Testing')
            ->type('nik', '1234567890')
            ->type('jabatan', 'Operator Produksi');

        $browser->script([
            "document.getElementById('foto_base64').value = 'data:image/jpeg;base64," . base64_encode('dummy') . "';"
        ]);

        $browser->press('Simpan')
            ->assertPathIs('/admin/karyawan')
            ->assertSee('✅ Karyawan berhasil ditambahkan.');
        });
    }

    #[Test]
    public function karyawan_appears_in_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = Karyawan::factory()->create(['nama' => 'Kafah Testing']);

        $this->browse(function (Browser $browser) use ($admin, $karyawan) {
            $browser->loginAs($admin)
                    ->visit('/admin/karyawan')
                    ->assertSee($karyawan->nama);
        });
    }

    /** @test */
    public function test_can_update_karyawan()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = Karyawan::factory()->create(['nama' => 'Kafah Testing']);

        $this->browse(function (Browser $browser) use ($admin, $karyawan) {
        $browser->loginAs($admin)
            ->visit('/admin/karyawan/' . $karyawan->id . '/edit')
            ->type('nama', 'Kafah Updated')
            ->type('nik', '9876543210')
            ->type('jabatan', 'Supervisor')
            // edit form button text is "Update"
            ->press('Update')
            ->assertPathIs('/admin/karyawan')
            ->assertSee('✅ Karyawan berhasil diperbarui.');
        });
    }

    #[Test]
    public function can_delete_karyawan()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = Karyawan::factory()->create(['nama' => 'Kafah Delete']);

        $this->browse(function (Browser $browser) use ($admin, $karyawan) {
            $browser->loginAs($admin)
                ->visit('/admin/karyawan')
                ->assertSee($karyawan->nama);

            $browser->script([
                "(function(){ var name = '" . addslashes($karyawan->nama) . "'; var rows = document.querySelectorAll('tbody tr'); for(var i=0;i<rows.length;i++){ if(rows[i].innerText.indexOf(name) !== -1){ var f = rows[i].querySelector('form'); if(f){ f.submit(); return; } } } })();"
            ]);

            $browser->waitForText('Karyawan berhasil dihapus.', 10)
                ->assertSee('Karyawan berhasil dihapus.');
        });
    }
}
