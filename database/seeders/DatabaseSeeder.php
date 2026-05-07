<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'program']);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'hiiroo@admin.com',
            'password' => Hash::make('Hiro3546'),
        ]);
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Bara',
            'email' => 'bara11@user.com',
            'password' => Hash::make('bara1106'),
        ]);
        $user->assignRole('program');
    }
}
