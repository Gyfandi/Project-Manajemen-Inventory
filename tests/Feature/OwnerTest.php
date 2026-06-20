<?php

namespace Tests\Feature;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Buat setelan user default
    }

    /**
     * Test Guest tidak dapat mengakses halaman owner
     */
    public function test_guest_cannot_access_owner_pages(): void
    {
        $this->get(route('owner.index'))->assertRedirect(route('login'));
    }

    /**
     * Test Sekretaris tidak memiliki hak akses (403)
     */
    public function test_secretary_cannot_access_owner_pages(): void
    {
        $secretary = User::factory()->create(['role' => 'sekretaris']);

        $this->actingAs($secretary)
             ->get(route('owner.index'))
             ->assertStatus(403);
    }

    /**
     * Test Admin dan Owner dapat mengakses halaman index owner
     */
    public function test_admin_and_owner_can_access_owner_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ownerUser = User::factory()->create(['role' => 'owner']);
        
        $owner = Owner::create([
            'user_id' => $ownerUser->id,
            'nama' => 'Owner Satu',
            'username' => $ownerUser->username,
            'email' => $ownerUser->email,
            'password' => 'password',
            'no_telp' => '0812',
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)
             ->get(route('owner.index'))
             ->assertOk()
             ->assertSee('Owner Satu');

        $this->actingAs($ownerUser)
             ->get(route('owner.index'))
             ->assertOk()
             ->assertSee('Owner Satu');
    }

    /**
     * Test Admin dapat membuat owner baru
     */
    public function test_admin_can_create_new_owner(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->post(route('owner.store'), [
                'nama' => 'Owner Baru',
                'username' => 'ownerbaru',
                'email' => 'ownerbaru@cvwijaya.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'no_telp' => '089999999',
                'alamat' => 'Alamat Owner Baru',
                'jabatan' => 'Komisaris',
                'status' => 'aktif',
            ]);

        $response->assertRedirect(route('owner.index'))
                 ->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['username' => 'ownerbaru', 'role' => 'owner']);
        $this->assertDatabaseHas('owner', ['nama' => 'Owner Baru', 'jabatan' => 'Komisaris']);
    }

    /**
     * Test Owner tidak dapat membuat owner baru (403)
     */
    public function test_owner_cannot_create_new_owner(): void
    {
        $ownerUser = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($ownerUser)
            ->post(route('owner.store'), [
                'nama' => 'Owner Dua',
                'username' => 'ownerdua',
                'email' => 'ownerdua@cvwijaya.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'no_telp' => '089999999',
                'status' => 'aktif',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test Admin dan Owner itu sendiri dapat mengedit data owner
     */
    public function test_authorized_users_can_update_owner(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ownerUser = User::factory()->create(['role' => 'owner']);
        
        $owner = Owner::create([
            'user_id' => $ownerUser->id,
            'nama' => 'Owner Awal',
            'username' => $ownerUser->username,
            'email' => $ownerUser->email,
            'password' => 'password',
            'no_telp' => '0812',
            'status' => 'aktif',
        ]);

        // 1. Owner update profil dirinya sendiri
        $response1 = $this->actingAs($ownerUser)
            ->put(route('owner.update', $owner->id), [
                'nama' => 'Owner Edit Mandiri',
                'username' => 'owner.updated',
                'email' => 'owner.updated@cvwijaya.com',
                'no_telp' => '081222',
                'status' => 'aktif', // status bernilai aktif (hidden/tetap)
                'jabatan' => $owner->jabatan,
            ]);
        $response1->assertRedirect(route('owner.show', $owner->id));

        $this->assertDatabaseHas('owner', ['nama' => 'Owner Edit Mandiri', 'username' => 'owner.updated']);

        // 2. Admin update profil owner
        $response2 = $this->actingAs($admin)
            ->put(route('owner.update', $owner->id), [
                'nama' => 'Owner Edit Admin',
                'username' => 'owner.adminedit',
                'email' => 'owner.adminedit@cvwijaya.com',
                'no_telp' => '081222',
                'status' => 'nonaktif',
                'jabatan' => 'Mantan Owner',
            ]);
        $response2->assertRedirect(route('owner.index'));

        $this->assertDatabaseHas('owner', ['nama' => 'Owner Edit Admin', 'status' => 'nonaktif', 'jabatan' => 'Mantan Owner']);
    }

    /**
     * Test Owner tidak dapat mengedit profil owner lain
     */
    public function test_owner_cannot_update_other_owner_profile(): void
    {
        $ownerUser1 = User::factory()->create(['role' => 'owner']);
        $ownerUser2 = User::factory()->create(['role' => 'owner']);
        
        $owner1 = Owner::create([
            'user_id' => $ownerUser1->id,
            'nama' => 'Owner Satu',
            'username' => $ownerUser1->username,
            'email' => $ownerUser1->email,
            'password' => 'password',
            'no_telp' => '0812',
            'status' => 'aktif',
        ]);

        $owner2 = Owner::create([
            'user_id' => $ownerUser2->id,
            'nama' => 'Owner Dua',
            'username' => $ownerUser2->username,
            'email' => $ownerUser2->email,
            'password' => 'password',
            'no_telp' => '0813',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($ownerUser1)
            ->put(route('owner.update', $owner2->id), [
                'nama' => 'Hacker Name',
                'username' => 'hacked',
                'email' => 'hacked@cvwijaya.com',
                'no_telp' => '0811',
                'status' => 'aktif',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test Admin dapat menghapus data owner, tapi tidak bisa jika owner aktif terakhir
     */
    public function test_admin_delete_owner_validation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ownerUser1 = User::factory()->create(['role' => 'owner']);
        $ownerUser2 = User::factory()->create(['role' => 'owner']);

        $owner1 = Owner::create([
            'user_id' => $ownerUser1->id,
            'nama' => 'Owner 1',
            'username' => $ownerUser1->username,
            'email' => $ownerUser1->email,
            'password' => 'password',
            'no_telp' => '0812',
            'status' => 'aktif',
        ]);

        $owner2 = Owner::create([
            'user_id' => $ownerUser2->id,
            'nama' => 'Owner 2',
            'username' => $ownerUser2->username,
            'email' => $ownerUser2->email,
            'password' => 'password',
            'no_telp' => '0813',
            'status' => 'aktif',
        ]);

        // Coba hapus owner1 (karena total owner aktif ada 2, harusnya berhasil)
        $response = $this->actingAs($admin)->delete(route('owner.destroy', $owner1->id));
        $response->assertRedirect(route('owner.index'))
                 ->assertSessionHas('success');

        $this->assertDatabaseMissing('owner', ['id' => $owner1->id]);
        $this->assertDatabaseMissing('users', ['id' => $ownerUser1->id]);

        // Coba hapus owner2 (sekarang tinggal 1 owner aktif tersisa, harusnya gagal/dicegah)
        $response2 = $this->actingAs($admin)->delete(route('owner.destroy', $owner2->id));
        $response2->assertRedirect(route('owner.index'))
                  ->assertSessionHas('error');

        $this->assertDatabaseHas('owner', ['id' => $owner2->id]);
    }
}
